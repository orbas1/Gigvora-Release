<?php

namespace App\Services;

use App\Models\Dispute;
use App\Models\Gig\Gig;
use App\Models\Gig\GigOrder;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Proposal\Proposal;
use App\Models\Seller\SellerPayout;
use Illuminate\Support\Facades\Cache;

class FreelanceWorkspaceService
{
    public function __construct(
        protected FreelanceSearchService $freelanceSearch,
        protected AdvertisementSurfaceService $ads
    ) {
    }

    public function snapshotForUser(?\App\Models\User $user): array
    {
        if (! $user) {
            return [
                'freelancer' => null,
                'client' => null,
            ];
        }

        $profileId = Profile::where('user_id', $user->id)->value('id');

        return $this->snapshotForProfile($profileId);
    }

    public function snapshotForProfile(?int $profileId): array
    {
        if (! $profileId) {
            return [
                'freelancer' => null,
                'client' => null,
            ];
        }

        return [
            'freelancer' => $this->freelancerSnapshot($profileId),
            'client' => $this->clientSnapshot($profileId),
        ];
    }

    protected function freelancerSnapshot(int $profileId): array
    {
        return Cache::tags(['freelance', 'dashboard'])->remember("freelance:freelancer:$profileId", now()->addMinutes(5), function () use ($profileId) {
            $activeGigs = Gig::where('author_id', $profileId)->where('status', 'publish')->count();
            $activeContracts = GigOrder::where('author_id', $profileId)->whereIn('status', ['hired', 'queued'])->count();
            $openProposals = Proposal::where('author_id', $profileId)
                ->whereNotIn('status', ['completed', 'refunded', 'cancelled', 'rejected'])
                ->count();
            $openDisputes = Dispute::where('created_by', $profileId)->where('status', 'open')->count();
            $earningsThisMonth = SellerPayout::where('seller_id', $profileId)
                ->whereMonth('created_at', now()->month)
                ->sum('seller_amount');

            $contracts = GigOrder::with('gig:id,title,slug')
                ->where('author_id', $profileId)
                ->whereIn('status', ['hired', 'queued'])
                ->latest()
                ->take(5)
                ->get()
                ->map(function (GigOrder $order) {
                    return [
                        'title' => optional($order->gig)->title ?? get_phrase('Gig order #:id', ['id' => $order->id]),
                        'status' => ucfirst($order->status ?? 'hired'),
                        'link' => optional($order->gig) ? route('freelance.gigs.activity', ['slug' => $order->gig->slug]) : null,
                    ];
                })
                ->toArray();

            $escrow = SellerPayout::where('seller_id', $profileId)
                ->latest()
                ->take(5)
                ->get()
                ->map(function (SellerPayout $payout) {
                    return [
                        'id' => $payout->id,
                        'title' => optional($payout->project)->project_title ?? optional($payout->gig)->title ?? get_phrase('Escrow'),
                        'amount' => $payout->seller_amount ?? 0,
                        'status' => $payout->status ?? 'pending',
                    ];
                })
                ->toArray();

            return [
                'metrics' => [
                    'active_gigs' => $activeGigs,
                    'open_contracts' => $activeContracts,
                    'open_proposals' => $openProposals,
                    'open_disputes' => $openDisputes,
                    'earnings_month' => $this->formatCurrency($earningsThisMonth),
                ],
                'kpis' => [
                    [
                        'label' => get_phrase('Active gigs'),
                        'value' => number_format($activeGigs),
                        'helper' => get_phrase('Live on marketplace'),
                    ],
                    [
                        'label' => get_phrase('Active contracts'),
                        'value' => number_format($activeContracts),
                        'helper' => get_phrase('In delivery or review'),
                    ],
                    [
                        'label' => get_phrase('Open proposals'),
                        'value' => number_format($openProposals),
                        'helper' => get_phrase('Awaiting response'),
                    ],
                    [
                        'label' => get_phrase('Earnings (month)'),
                        'value' => $this->formatCurrency($earningsThisMonth),
                        'helper' => setting('_general.currency'),
                    ],
                ],
                'contracts' => $contracts,
                'escrow' => $escrow,
                'recommendations' => $this->freelanceSearch->highlightedProjects(null, 3),
                'ads' => $this->ads->forSlot('freelance_dashboard'),
            ];
        });
    }

    protected function clientSnapshot(int $profileId): array
    {
        return Cache::tags(['freelance', 'dashboard'])->remember("freelance:client:$profileId", now()->addMinutes(5), function () use ($profileId) {
            $openProjects = Project::where('author_id', $profileId)->where('status', 'publish')->count();
            $inProgress = Project::where('author_id', $profileId)->whereIn('status', ['hired', 'in_progress'])->count();
            $openDisputes = Dispute::where('created_by', $profileId)->where('status', 'open')->count();
            $escrowVolume = SellerPayout::where('project_id', function ($query) use ($profileId) {
                $query->select('id')
                    ->from('projects')
                    ->whereColumn('projects.id', 'seller_payouts.project_id')
                    ->where('projects.author_id', $profileId);
            })->sum('seller_amount');

            $contracts = Project::where('author_id', $profileId)
                ->whereIn('status', ['hired', 'in_progress'])
                ->latest()
                ->take(5)
                ->get(['project_title', 'status'])
                ->map(function (Project $project) {
                    return [
                        'title' => $project->project_title,
                        'status' => ucfirst($project->status ?? 'hired'),
                    ];
                })
                ->toArray();

            $disputes = Dispute::with('disputeReceiver:id,first_name,last_name')
                ->where('created_by', $profileId)
                ->latest()
                ->take(5)
                ->get()
                ->map(function (Dispute $dispute) {
                    return [
                        'contract' => $dispute->title ?? get_phrase('Dispute #:id', ['id' => $dispute->id]),
                        'counterpart' => optional($dispute->disputeReceiver)->full_name ?? get_phrase('N/A'),
                        'status' => ucfirst($dispute->status ?? 'open'),
                    ];
                })
                ->toArray();

            $freelancers = Profile::select('id', 'first_name', 'last_name', 'tagline', 'slug')
                ->whereHas('role', function ($query) {
                    $query->where('name', config('freelance.roles.seller', 'seller'));
                })
                ->latest()
                ->take(5)
                ->get()
                ->map(function (Profile $profile) {
                    return [
                        'name' => $profile->full_name,
                        'tagline' => $profile->tagline ?? get_phrase('Ready to collaborate'),
                        'link' => route('freelance.sellers.profile', $profile->slug),
                    ];
                })
                ->toArray();

            return [
                'metrics' => [
                    'open_projects' => $openProjects,
                    'active_contracts' => $inProgress,
                    'open_disputes' => $openDisputes,
                    'escrow_volume' => $this->formatCurrency($escrowVolume),
                ],
                'kpis' => [
                    [
                        'label' => get_phrase('Open projects'),
                        'value' => number_format($openProjects),
                        'helper' => get_phrase('Accepting proposals'),
                    ],
                    [
                        'label' => get_phrase('Active contracts'),
                        'value' => number_format($inProgress),
                        'helper' => get_phrase('In delivery'),
                    ],
                    [
                        'label' => get_phrase('Open disputes'),
                        'value' => number_format($openDisputes),
                        'helper' => get_phrase('Awaiting action'),
                    ],
                    [
                        'label' => get_phrase('Escrow released'),
                        'value' => $this->formatCurrency($escrowVolume),
                        'helper' => setting('_general.currency'),
                    ],
                ],
                'contracts' => $contracts,
                'disputes' => $disputes,
                'freelancers' => $freelancers,
                'ads' => $this->ads->forSlot('freelance_dashboard'),
            ];
        });
    }

    protected function formatCurrency(float $amount): string
    {
        return (setting('_general.currency') ?? 'USD').' '.number_format($amount, 2);
    }
}



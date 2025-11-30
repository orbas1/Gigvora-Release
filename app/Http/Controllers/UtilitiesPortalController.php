<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

class UtilitiesPortalController extends Controller
{
    public function hub(): \Illuminate\Contracts\View\View
    {
        $features = config('pro_network_utilities_security_analytics.features', []);

        $cards = [
            [
                'key' => 'network',
                'title' => get_phrase('Connections & My Network'),
                'description' => get_phrase('Discover first, second, and third-degree connections with mutual counts and recommendations.'),
                'enabled' => $this->featureEnabled('connections_graph'),
                'actions' => [
                    [
                        'label' => get_phrase('Open My Network'),
                        'route' => route('utilities.network'),
                        'type' => 'primary',
                    ],
                ],
            ],
            [
                'key' => 'profiles',
                'title' => get_phrase('Professional Profiles & Company Pages'),
                'description' => get_phrase('Unlock upgraded profile layouts, employment visibility, and agency/company shells.'),
                'enabled' => $this->featureEnabled('profile_professional_upgrades'),
                'actions' => [
                    [
                        'label' => get_phrase('My Professional Profile'),
                        'route' => route('utilities.professional'),
                        'type' => 'primary',
                    ],
                    [
                        'label' => get_phrase('Manage Company Page'),
                        'route' => route('utilities.company'),
                        'type' => 'secondary',
                    ],
                ],
            ],
            [
                'key' => 'escrow',
                'title' => get_phrase('Marketplace Escrow & Disputes'),
                'description' => get_phrase('Track order escrow lifecycle, milestones, releases, refunds, and dispute evidence.'),
                'enabled' => $this->featureEnabled('marketplace_escrow'),
                'actions' => [
                    [
                        'label' => get_phrase('View Marketplace Orders'),
                        'route' => url('marketplace'),
                        'type' => 'primary',
                    ],
                    [
                        'label' => get_phrase('Learn about Escrow'),
                        'route' => route('utilities.hub') . '#escrow',
                        'type' => 'secondary',
                    ],
                ],
            ],
            [
                'key' => 'stories',
                'title' => get_phrase('Stories & Post Enhancements'),
                'description' => get_phrase('Create enhanced stories with music, and craft polls, threads, and celebrate posts.'),
                'enabled' => $this->featureEnabled('stories_wrapper') || $this->featureEnabled('post_enhancements'),
                'actions' => [
                    [
                        'label' => get_phrase('Launch Story Creator'),
                        'route' => route('utilities.stories.create'),
                        'type' => 'primary',
                    ],
                    [
                        'label' => get_phrase('Create Poll'),
                        'route' => route('utilities.posts.poll'),
                        'type' => 'secondary',
                    ],
                    [
                        'label' => get_phrase('Create Thread'),
                        'route' => route('utilities.posts.thread'),
                        'type' => 'secondary',
                    ],
                    [
                        'label' => get_phrase('Celebrate Post'),
                        'route' => route('utilities.posts.celebrate'),
                        'type' => 'secondary',
                    ],
                ],
            ],
            [
                'key' => 'hashtags',
                'title' => get_phrase('Reactions & Hashtags'),
                'description' => get_phrase('Use multi-reactions/dislikes with profile scores and normalized tagging across feed/search.'),
                'enabled' => $this->featureEnabled('reactions_dislikes_scores') || $this->featureEnabled('hashtags'),
                'actions' => [
                    [
                        'label' => get_phrase('Jump to Feed'),
                        'route' => route('dashboard'),
                        'type' => 'primary',
                    ],
                    [
                        'label' => get_phrase('Open Hashtag Explorer'),
                        'route' => route('utilities.hashtags'),
                        'type' => 'secondary',
                    ],
                ],
            ],
        ];

        return view('utilities.hub', [
            'features' => $features,
            'cards' => $cards,
        ]);
    }

    public function myNetwork(): RedirectResponse
    {
        return $this->redirectToFeature('connections_graph', '/pro-network/my-network');
    }

    public function professionalProfile(): RedirectResponse
    {
        return $this->redirectToFeature('profile_professional_upgrades', '/pro-network/profile/professional');
    }

    public function companyProfile(): RedirectResponse
    {
        return $this->redirectToFeature('profile_professional_upgrades', '/pro-network/profile/professional/edit');
    }

    public function storiesCreator(): RedirectResponse
    {
        return $this->redirectToFeature('stories_wrapper', '/pro-network/stories/creator');
    }

    public function storiesViewer(): RedirectResponse
    {
        return $this->redirectToFeature('stories_wrapper', '/pro-network/stories/viewer');
    }

    public function postPoll(): RedirectResponse
    {
        return $this->redirectToFeature('post_enhancements', '/pro-network/posts/polls/create');
    }

    public function postThread(): RedirectResponse
    {
        return $this->redirectToFeature('post_enhancements', '/pro-network/posts/threads/create');
    }

    public function postCelebrate(): RedirectResponse
    {
        return $this->redirectToFeature('post_enhancements', '/pro-network/posts/celebrate/create');
    }

    public function hashtags(): RedirectResponse
    {
        return $this->redirectToFeature('hashtags', '/pro-network/hashtags/gigvora');
    }

    public function analytics(): RedirectResponse
    {
        return $this->redirectWithGate('analytics_hub', '/pro-network/analytics', fn () => Gate::allows('viewAnalytics'));
    }

    public function securityLog(): RedirectResponse
    {
        return $this->redirectWithGate('security_hardening', '/pro-network/security/log', fn () => Gate::allows('viewSecurity'));
    }

    public function moderation(): RedirectResponse
    {
        return $this->redirectWithGate('moderation_tools', '/pro-network/moderation', fn () => Gate::allows('moderate'));
    }

    protected function featureEnabled(string $feature): bool
    {
        $features = config('pro_network_utilities_security_analytics.features', []);

        return (bool) data_get($features, $feature, false);
    }

    protected function redirectToFeature(string $feature, string $targetPath): RedirectResponse
    {
        abort_unless($this->featureEnabled($feature), 404);

        return redirect()->to(url($targetPath));
    }

    protected function redirectWithGate(string $feature, string $targetPath, callable $gateCheck): RedirectResponse
    {
        abort_unless($this->featureEnabled($feature), 404);
        abort_unless($gateCheck(), 403);

        return redirect()->to(url($targetPath));
    }
}



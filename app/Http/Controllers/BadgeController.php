<?php

namespace App\Http\Controllers;

use App\Models\Badge;
use App\Services\GigvoraVerifyService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Session;

class BadgeController extends Controller
{
    public function __construct(
        protected GigvoraVerifyService $verify
    ) {
    }

    public function badge()
    {
        $user = auth()->user();
        $report = $this->verify->buildReport($user);

        $page_data['badges'] = Badge::where('user_id', $user->id)
            ->orderBy('id', 'DESC')
            ->get();
        $page_data['verifyReport'] = $report;
        $page_data['activeBadge'] = $report['badge'];
        $page_data['price'] = config('gigvora_verify.price');
        $page_data['view_path'] = 'frontend.badge.badge';

        return view('frontend.index', $page_data);
    }

    public function badge_info()
    {
        $page_data['verifyReport'] = $this->verify->buildReport(auth()->user());
        $page_data['price'] = config('gigvora_verify.price');
        $page_data['view_path'] = 'frontend.badge.badge_info';

        return view('frontend.index', $page_data);
    }

    public function payment_configuration($id, Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);

        $user = auth()->user();
        $report = $this->verify->buildReport($user);

        $existingBadge = $report['badge'];
        if ($existingBadge && $existingBadge->review_status === Badge::REVIEW_UNDER_REVIEW) {
            Session::flash('error_message', get_phrase('Your profile is already under review.'));
            return redirect()->route('badge');
        }

        if (! $report['eligible']) {
            Session::flash('error_message', get_phrase('You must meet all Gigvora Verify requirements before purchasing.'));
            return redirect()->route('badge');
        }

        session([
            'gigvora_verify_snapshot' => [
                'captured_at' => now()->toIso8601String(),
                'counts' => $report['counts'],
                'requirements' => array_values(array_map(
                    fn ($rule) => Arr::only($rule, ['key', 'met']),
                    $report['requirements']
                )),
            ],
        ]);

        $priceConfig = config('gigvora_verify.price');
        $badge_pay = $priceConfig['amount'] ?? get_settings('badge_price');
        $title = $request->title;
        $description = $request->description;
        $start = now();
        $end = now()->copy()->addDays(30);

        $payment_details = [
            'items' => [
                [
                    'id' => $id,
                    'title' => $title,
                    'subtitle' => $description,
                    'price' => $badge_pay,
                    'discount_price' => 0,
                    'discount_percentage' => 0,
                ],
            ],
            'custom_field' => [
                'start_date' => $start->format('Y-m-d H:i:s'),
                'end_date' => $end->format('Y-m-d H:i:s'),
                'user_id' => $user->id,
                'description' => $description,
            ],
            'success_method' => [
                'model_name' => 'Badge',
                'function_name' => 'add_payment_success',
            ],
            'tax' => 0,
            'coupon' => null,
            'payable_amount' => $badge_pay,
            'cancel_url' => route('badge'),
            'success_url' => route('payment.success', ''),
        ];

        session(['payment_details' => $payment_details]);

        return redirect()->route('payment');
    }
}

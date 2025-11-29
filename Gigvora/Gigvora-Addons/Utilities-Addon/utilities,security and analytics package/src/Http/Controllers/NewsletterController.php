<?php

namespace ProNetworkUtilitiesSecurityAnalytics\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use ProNetwork\Services\NewsletterService;
use ProNetworkUtilitiesSecurityAnalytics\Http\Requests\NewsletterSubscribeRequest;
use ProNetworkUtilitiesSecurityAnalytics\Http\Requests\NewsletterUnsubscribeRequest;

class NewsletterController extends Controller
{
    public function __construct(protected NewsletterService $newsletterService)
    {
    }

    public function manage()
    {
        return view('pro_network::newsletters.manage');
    }

    public function subscribe(NewsletterSubscribeRequest $request)
    {
        $subscription = $this->newsletterService->subscribe(
            $request->validated('email'),
            $request->user()->id ?? null,
            $request->validated('source')
        );

        return response()->json([
            'subscribed' => true,
            'subscription' => $subscription,
        ]);
    }

    public function unsubscribe(NewsletterUnsubscribeRequest $request)
    {
        $this->newsletterService->unsubscribe($request->validated('email'));

        return response()->json([
            'unsubscribed' => true,
            'email' => $request->validated('email'),
        ]);
    }

    public function adminIndex()
    {
        return view('pro_network::newsletters.admin.index');
    }
}

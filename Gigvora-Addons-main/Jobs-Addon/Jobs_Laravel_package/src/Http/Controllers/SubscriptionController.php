<?php

namespace Jobs\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Jobs\Models\Subscription;

class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $companyId = $request->integer('company_id');
        $query = Subscription::query();

        if ($companyId) {
            $query->where('company_id', $companyId);
        }

        return response()->json($query->latest()->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'company_id' => ['required', 'integer'],
            'plan' => ['required', 'string'],
            'job_credits' => ['required', 'integer'],
            'renews_at' => ['nullable', 'date'],
            'status' => ['nullable', 'string'],
            'payment_reference' => ['nullable', 'string'],
        ]);

        $subscription = Subscription::create($data);

        return response()->json($subscription, 201);
    }
}

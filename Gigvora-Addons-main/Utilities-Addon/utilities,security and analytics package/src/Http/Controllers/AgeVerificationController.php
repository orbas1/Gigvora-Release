<?php

namespace ProNetworkUtilitiesSecurityAnalytics\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use ProNetwork\Models\AgeVerification;
use ProNetwork\Services\AgeVerificationService;
use ProNetworkUtilitiesSecurityAnalytics\Http\Requests\StartAgeVerificationRequest;

class AgeVerificationController extends Controller
{
    public function __construct(protected AgeVerificationService $ageVerificationService)
    {
    }

    public function status(Request $request)
    {
        $verification = AgeVerification::firstOrCreate(
            ['user_id' => $request->user()->id],
            [
                'status' => 'unverified',
                'provider' => config('pro_network_utilities_security_analytics.age_verification.provider'),
            ]
        );

        return response()->json([
            'status' => $verification->status,
            'provider' => $verification->provider,
            'verified_at' => $verification->verified_at,
        ]);
    }

    public function start(StartAgeVerificationRequest $request)
    {
        $response = $this->ageVerificationService->start($request->user()->id, $request->validated());

        return response()->json([
            'status' => $response->status,
            'provider_reference' => $response->providerReference,
        ]);
    }

    public function callback(Request $request)
    {
        $status = $request->input('status', 'pending');
        $verificationId = (int) $request->input('verification_id');

        $verification = AgeVerification::find($verificationId);

        if ($verification) {
            $updated = $this->ageVerificationService->complete($verification, $status, $request->all());
        }

        return response()->json([
            'handled' => isset($updated),
            'status' => $updated->status ?? $status,
        ]);
    }
}

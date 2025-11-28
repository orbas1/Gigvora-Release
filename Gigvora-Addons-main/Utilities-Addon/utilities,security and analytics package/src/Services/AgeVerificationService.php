<?php

namespace ProNetwork\Services;

use ProNetwork\Models\AgeVerification;
use ProNetwork\Models\AgeVerificationLog;
use ProNetwork\Support\DTOs\AgeVerificationResponseDto;

class AgeVerificationService
{
    public function start(int $userId, array $payload = []): AgeVerificationResponseDto
    {
        $verification = AgeVerification::updateOrCreate(
            ['user_id' => $userId],
            [
                'status' => 'pending',
                'provider' => config('pro_network_utilities_security_analytics.age_verification.provider'),
                'payload' => $payload,
            ]
        );

        $this->log($verification, 'started', $payload);

        return new AgeVerificationResponseDto($verification->status, $verification->provider_reference);
    }

    public function complete(AgeVerification $verification, string $status, array $meta = []): AgeVerification
    {
        $verification->update([
            'status' => $status,
            'verified_at' => $status === 'verified' ? now() : null,
            'rejected_at' => $status === 'rejected' ? now() : null,
            'payload' => $meta + ($verification->payload ?? []),
        ]);

        $this->log($verification, 'updated', ['status' => $status] + $meta);

        return $verification->fresh();
    }

    public function enforceIfEnabled(AgeVerification $verification): bool
    {
        return config('pro_network_utilities_security_analytics.features.age_verification')
            ? $verification->status === 'verified'
            : true;
    }

    protected function log(AgeVerification $verification, string $event, array $meta = []): AgeVerificationLog
    {
        return AgeVerificationLog::create([
            'age_verification_id' => $verification->id,
            'event' => $event,
            'meta' => $meta,
        ]);
    }
}

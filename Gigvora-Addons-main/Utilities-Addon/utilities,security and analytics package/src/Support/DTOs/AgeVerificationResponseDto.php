<?php

namespace ProNetwork\Support\DTOs;

class AgeVerificationResponseDto
{
    public function __construct(
        public readonly string $status,
        public readonly ?string $providerReference = null,
        public readonly ?string $message = null,
        public readonly array $meta = []
    ) {
    }
}

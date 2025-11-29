<?php

namespace ProNetwork\Support\DTOs;

class RecommendationResultDto
{
    /**
     * @param array<int, mixed> $items
     */
    public function __construct(
        public readonly string $type,
        public readonly array $items
    ) {
    }
}

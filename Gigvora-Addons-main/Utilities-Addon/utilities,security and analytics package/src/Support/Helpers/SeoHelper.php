<?php

namespace ProNetwork\Support\Helpers;

class SeoHelper
{
    public static function metaFor(string $title, ?string $description = null, array $keywords = []): array
    {
        return [
            'title' => $title,
            'description' => $description ?? substr($title, 0, 150),
            'keywords' => implode(', ', array_unique(array_map([TagHelper::class, 'normalize'], $keywords))),
        ];
    }
}

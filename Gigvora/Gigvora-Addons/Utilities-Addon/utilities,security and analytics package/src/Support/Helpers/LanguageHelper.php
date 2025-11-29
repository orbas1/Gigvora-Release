<?php

namespace ProNetwork\Support\Helpers;

class LanguageHelper
{
    public const SUPPORTED = [
        'en', 'fr', 'de', 'es', 'pt', 'ar', 'ru', 'hr', 'it', 'yo', 'af', 'zh', 'ja', 'hi', 'ur', 'ta', 'si'
    ];

    public static function enabledLocales(array $overrides = []): array
    {
        return array_values(array_unique(array_filter($overrides ?: self::SUPPORTED)));
    }
}

<?php

namespace ProNetwork\Services;

use ProNetwork\Support\Helpers\LanguageHelper;

class MultiLanguageService
{
    public function availableLocales(): array
    {
        return LanguageHelper::enabledLocales();
    }
}

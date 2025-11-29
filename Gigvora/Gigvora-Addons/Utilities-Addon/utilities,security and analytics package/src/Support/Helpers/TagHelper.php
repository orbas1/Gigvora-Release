<?php

namespace ProNetwork\Support\Helpers;

use Illuminate\Support\Str;

class TagHelper
{
    public static function normalize(string $tag): string
    {
        $clean = trim(Str::lower($tag));
        $clean = preg_replace('/[^a-z0-9\-\s_]/i', '', $clean) ?? $clean;

        return Str::slug($clean, '-');
    }
}

<?php

use Illuminate\Support\Str;

if (!function_exists('getIdWithPregMatch')) {
    function getIdsWithPregMatch($data, $pattern, $replacePattern)
    {
        return Str::of($data['content'])->matchAll($pattern)->unique()->transform(
            fn ($id) => Str::of($id)->replaceMatches($replacePattern, '')->value()
        );
    }
}

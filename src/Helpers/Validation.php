<?php

namespace Gyaaniguy\Upworktest\Helpers;

class Validation
{
    public static function isEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public static function hasJpg(string $str): bool
    {
        return preg_match('/\.jpg\b/i', $str);
    }
}
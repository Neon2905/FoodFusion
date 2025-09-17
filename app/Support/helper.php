<?php

if (!function_exists('mask_email')) {
    /**
     * Mask an email like: t****@g****.com
     */
    function mask_email(string $email, string $pad = '****'): string
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $email;
        }

        [$local, $domain] = explode('@', $email, 2);

        $localMasked = mb_substr($local, 0, 1) . $pad;

        $pos = strpos($domain, '.');
        if ($pos !== false) {
            $name = mb_substr($domain, 0, $pos);
            $tld = substr($domain, $pos); // includes the dot
        } else {
            $name = $domain;
            $tld = '';
        }

        $domainMasked = mb_substr($name, 0, 1) . $pad . $tld;

        return $localMasked . '@' . $domainMasked;
    }
}

if (!function_exists('mask_name')) {
    /**
     * Mask a name like: J*** D***
     */
    function mask_name(string $name, string $pad = '***'): string
    {
        $parts = explode(' ', $name);
        $maskedParts = array_map(function ($part) use ($pad) {
            return mb_substr($part, 0, 1) . $pad;
        }, $parts);

        return implode(' ', $maskedParts);
    }
}

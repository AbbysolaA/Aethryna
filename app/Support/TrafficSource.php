<?php

namespace App\Support;

/**
 * "LinkedIn", "Instagram", "Direct or unknown": where a visitor came from,
 * in the words the team would use, from a campaign tag or, failing that,
 * the site that sent them.
 */
class TrafficSource
{
    /** Tag values and referring hosts, to one platform name each. */
    private const PLATFORMS = [
        'LinkedIn'      => ['linkedin', 'lnkd.in'],
        'Instagram'     => ['instagram', 'ig'],
        'Facebook'      => ['facebook', 'fb', 'fb.com', 'fb.me', 'meta'],
        'TikTok'        => ['tiktok'],
        'X'             => ['x', 'x.com', 'twitter', 't.co'],
        'WhatsApp'      => ['whatsapp', 'wa.me'],
        'YouTube'       => ['youtube', 'youtu.be'],
        'Email'         => ['email', 'newsletter', 'emailoctopus'],
        'Google search' => ['google'],
        'Bing search'   => ['bing'],
    ];

    public static function label(?string $utmSource, ?string $referrerHost = null): string
    {
        if ($utmSource) {
            return self::platformFor(strtolower($utmSource)) ?? ucfirst($utmSource);
        }

        if ($referrerHost) {
            return self::platformFor(strtolower($referrerHost)) ?? $referrerHost;
        }

        return 'Direct or unknown';
    }

    private static function platformFor(string $value): ?string
    {
        foreach (self::PLATFORMS as $platform => $names) {
            foreach ($names as $name) {
                // Exact for tags; for hosts, the name or any subdomain of it,
                // so l.instagram.com counts but notinstagram.com does not.
                if ($value === $name
                    || str_ends_with($value, '.'.$name)
                    || preg_match('/(^|\.)'.preg_quote($name, '/').'\.[a-z.]+$/', $value)) {
                    return $platform;
                }
            }
        }

        return null;
    }

    /** The platforms the admin offers ready-tagged share links for. */
    public static function shareLinkPlatforms(): array
    {
        return [
            'linkedin'  => ['LinkedIn', 'social'],
            'instagram' => ['Instagram', 'social'],
            'facebook'  => ['Facebook', 'social'],
            'tiktok'    => ['TikTok', 'social'],
            'x'         => ['X', 'social'],
            'whatsapp'  => ['WhatsApp', 'social'],
            'email'     => ['Email or newsletter', 'email'],
        ];
    }
}

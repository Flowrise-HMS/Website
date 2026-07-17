<?php

namespace Modules\Website\Classes\Support;

use Modules\Website\Contracts\BookingCtaResolver;
use Modules\Website\Settings\WebsiteSettings;

class NullBookingCtaResolver implements BookingCtaResolver
{
    /**
     * @return array{phone: ?string, whatsapp: ?string, message: ?string, url: ?string}
     */
    public function resolve(): array
    {
        try {
            $settings = app(WebsiteSettings::class);
        } catch (\Throwable) {
            return [
                'phone' => null,
                'whatsapp' => null,
                'message' => null,
                'url' => null,
            ];
        }

        $phone = $settings->booking_cta_phone;
        $whatsapp = $settings->booking_cta_whatsapp;
        $message = $settings->booking_cta_message;

        $url = null;
        if (filled($whatsapp)) {
            $digits = preg_replace('/\D+/', '', $whatsapp) ?? '';
            $query = filled($message) ? '?text='.rawurlencode($message) : '';
            $url = $digits !== '' ? "https://wa.me/{$digits}{$query}" : null;
        }

        return [
            'phone' => $phone,
            'whatsapp' => $whatsapp,
            'message' => $message,
            'url' => $url,
        ];
    }
}

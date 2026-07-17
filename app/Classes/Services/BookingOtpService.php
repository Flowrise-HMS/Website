<?php

namespace Modules\Website\Classes\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Modules\Website\Classes\Support\BookingContactNotifiable;
use Modules\Website\Notifications\BookingOtpNotification;

class BookingOtpService
{
    public const TTL_SECONDS = 600;

    /**
     * @param  array{email?: ?string, phone?: ?string, patient_id?: ?string}  $routes
     * @return array{challenge_id: string, masked_destinations: array{email: ?string, phone: ?string}}
     */
    public function send(array $routes): array
    {
        $email = $routes['email'] ?? null;
        $phone = $routes['phone'] ?? null;

        if (! filled($email) && ! filled($phone)) {
            throw ValidationException::withMessages([
                'identifier' => __('No email or phone is available to send a verification code.'),
            ]);
        }

        $rateKey = 'website-booking-otp:'.sha1(($email ?? '').'|'.($phone ?? ''));
        if (RateLimiter::tooManyAttempts($rateKey, 5)) {
            throw ValidationException::withMessages([
                'otp' => __('Too many verification attempts. Please try again later.'),
            ]);
        }
        RateLimiter::hit($rateKey, 60);

        $code = (string) random_int(100000, 999999);
        $challengeId = (string) Str::uuid();

        Cache::put($this->cacheKey($challengeId), [
            'hash' => hash('sha256', $code),
            'email' => $email,
            'phone' => $phone,
            'patient_id' => $routes['patient_id'] ?? null,
            'verified' => false,
        ], self::TTL_SECONDS);

        (new BookingContactNotifiable($email, $phone))
            ->notify(new BookingOtpNotification($code));

        return [
            'challenge_id' => $challengeId,
            'masked_destinations' => [
                'email' => $this->maskEmail($email),
                'phone' => $this->maskPhone($phone),
            ],
        ];
    }

    /**
     * @return array{hash: string, email: ?string, phone: ?string, patient_id: ?string, verified: bool}
     */
    public function verify(string $challengeId, string $code): array
    {
        $payload = Cache::get($this->cacheKey($challengeId));

        if (! is_array($payload)) {
            throw ValidationException::withMessages([
                'otp' => __('Verification code expired. Please request a new one.'),
            ]);
        }

        if (! hash_equals((string) $payload['hash'], hash('sha256', $code))) {
            throw ValidationException::withMessages([
                'otp' => __('Invalid verification code.'),
            ]);
        }

        $payload['verified'] = true;
        Cache::put($this->cacheKey($challengeId), $payload, self::TTL_SECONDS);

        return $payload;
    }

    public function patientIdIfVerified(string $challengeId): ?string
    {
        $payload = Cache::get($this->cacheKey($challengeId));

        if (! is_array($payload) || ! ($payload['verified'] ?? false)) {
            return null;
        }

        return $payload['patient_id'] ?? null;
    }

    protected function cacheKey(string $challengeId): string
    {
        return 'website.booking.otp.'.$challengeId;
    }

    protected function maskEmail(?string $email): ?string
    {
        if (! filled($email) || ! str_contains($email, '@')) {
            return null;
        }

        [$local, $domain] = explode('@', $email, 2);
        $visible = substr($local, 0, 1);

        return $visible.'***@'.$domain;
    }

    protected function maskPhone(?string $phone): ?string
    {
        if (! filled($phone)) {
            return null;
        }

        $digits = preg_replace('/\D+/', '', $phone) ?? '';
        if (strlen($digits) < 4) {
            return '***';
        }

        return str_repeat('*', max(strlen($digits) - 4, 0)).substr($digits, -4);
    }
}

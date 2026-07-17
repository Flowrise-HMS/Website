<?php

namespace Modules\Website\Classes\Support;

use Illuminate\Notifications\Notifiable;

class BookingContactNotifiable
{
    use Notifiable;

    public function __construct(
        public ?string $email = null,
        public ?string $phone = null,
    ) {}

    public function getKey(): string
    {
        return sha1(($this->email ?? '').'|'.($this->phone ?? ''));
    }

    public function routeNotificationForMail(): ?string
    {
        return filled($this->email) ? $this->email : null;
    }

    public function routeNotificationForSms(): ?string
    {
        return filled($this->phone) ? $this->phone : null;
    }
}

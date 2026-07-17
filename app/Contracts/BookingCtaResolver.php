<?php

namespace Modules\Website\Contracts;

interface BookingCtaResolver
{
    /**
     * @return array{phone: ?string, whatsapp: ?string, message: ?string, url: ?string}
     */
    public function resolve(): array;
}

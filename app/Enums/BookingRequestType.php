<?php

namespace Modules\Website\Enums;

enum BookingRequestType: string
{
    case PreferredTime = 'preferred_time';
    case Waitlist = 'waitlist';
}

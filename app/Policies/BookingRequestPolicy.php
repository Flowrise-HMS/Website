<?php

declare(strict_types=1);

namespace Modules\Website\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;
use Modules\Website\Models\BookingRequest;

class BookingRequestPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny BookingRequest');
    }

    public function view(AuthUser $authUser, BookingRequest $bookingRequest): bool
    {
        return $authUser->can('View BookingRequest');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create BookingRequest');
    }

    public function update(AuthUser $authUser, BookingRequest $bookingRequest): bool
    {
        return $authUser->can('Update BookingRequest');
    }

    public function delete(AuthUser $authUser, BookingRequest $bookingRequest): bool
    {
        return $authUser->can('Delete BookingRequest');
    }

    public function restore(AuthUser $authUser, BookingRequest $bookingRequest): bool
    {
        return $authUser->can('Restore BookingRequest');
    }

    public function forceDelete(AuthUser $authUser, BookingRequest $bookingRequest): bool
    {
        return $authUser->can('ForceDelete BookingRequest');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny BookingRequest');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny BookingRequest');
    }

    public function replicate(AuthUser $authUser, BookingRequest $bookingRequest): bool
    {
        return $authUser->can('Replicate BookingRequest');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder BookingRequest');
    }
}

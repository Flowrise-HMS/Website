<?php

declare(strict_types=1);

namespace Modules\Website\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;
use Modules\Website\Models\GalleryAlbum;

class GalleryAlbumPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny GalleryAlbum');
    }

    public function view(AuthUser $authUser, GalleryAlbum $galleryAlbum): bool
    {
        return $authUser->can('View GalleryAlbum');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create GalleryAlbum');
    }

    public function update(AuthUser $authUser, GalleryAlbum $galleryAlbum): bool
    {
        return $authUser->can('Update GalleryAlbum');
    }

    public function delete(AuthUser $authUser, GalleryAlbum $galleryAlbum): bool
    {
        return $authUser->can('Delete GalleryAlbum');
    }

    public function restore(AuthUser $authUser, GalleryAlbum $galleryAlbum): bool
    {
        return $authUser->can('Restore GalleryAlbum');
    }

    public function forceDelete(AuthUser $authUser, GalleryAlbum $galleryAlbum): bool
    {
        return $authUser->can('ForceDelete GalleryAlbum');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny GalleryAlbum');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny GalleryAlbum');
    }

    public function replicate(AuthUser $authUser, GalleryAlbum $galleryAlbum): bool
    {
        return $authUser->can('Replicate GalleryAlbum');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder GalleryAlbum');
    }
}

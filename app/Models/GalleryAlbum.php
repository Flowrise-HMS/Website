<?php

namespace Modules\Website\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Website\Database\Factories\GalleryAlbumFactory;

class GalleryAlbum extends Model
{
    /** @use HasFactory<GalleryAlbumFactory> */
    use HasFactory;

    protected $table = 'website_gallery_albums';

    protected $fillable = [
        'title',
        'slug',
        'description',
        'cover_image',
        'is_published',
        'sort_order',
    ];

    /**
     * @var array<string, mixed>
     */
    protected $attributes = [
        'is_published' => false,
        'sort_order' => 0,
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function items(): HasMany
    {
        return $this->hasMany(GalleryItem::class, 'album_id')->orderBy('sort_order');
    }

    protected static function newFactory(): GalleryAlbumFactory
    {
        return GalleryAlbumFactory::new();
    }

    /**
     * @param  Builder<GalleryAlbum>  $query
     * @return Builder<GalleryAlbum>
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }
}

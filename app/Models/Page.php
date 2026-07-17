<?php

namespace Modules\Website\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Website\Database\Factories\PageFactory;
use Modules\Website\Enums\PageType;

class Page extends Model
{
    /** @use HasFactory<PageFactory> */
    use HasFactory, SoftDeletes;

    protected $table = 'website_pages';

    protected $fillable = [
        'title',
        'slug',
        'type',
        'layout',
        'is_published',
        'meta_title',
        'meta_description',
        'og_image',
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
            'type' => PageType::class,
            'is_published' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    protected static function newFactory(): PageFactory
    {
        return PageFactory::new();
    }

    public function sections(): HasMany
    {
        return $this->hasMany(PageSection::class, 'page_id')->orderBy('sort_order');
    }

    public function menuItems(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'page_id');
    }

    /**
     * @param  Builder<Page>  $query
     * @return Builder<Page>
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }
}

<?php

namespace Modules\Website\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Website\Enums\SectionType;

class PageSection extends Model
{
    protected $table = 'website_page_sections';

    protected $fillable = [
        'page_id',
        'type',
        'payload',
        'sort_order',
        'is_visible',
    ];

    /**
     * @var array<string, mixed>
     */
    protected $attributes = [
        'sort_order' => 0,
        'is_visible' => true,
    ];

    protected function casts(): array
    {
        return [
            'type' => SectionType::class,
            'payload' => 'array',
            'sort_order' => 'integer',
            'is_visible' => 'boolean',
        ];
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class, 'page_id');
    }
}

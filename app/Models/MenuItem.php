<?php

namespace Modules\Website\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Website\Enums\MenuItemType;

class MenuItem extends Model
{
    protected $table = 'website_menu_items';

    protected $fillable = [
        'menu_id',
        'label',
        'type',
        'page_id',
        'url',
        'sort_order',
        'is_visible',
        'open_in_new_tab',
    ];

    /**
     * @var array<string, mixed>
     */
    protected $attributes = [
        'sort_order' => 0,
        'is_visible' => true,
        'open_in_new_tab' => false,
    ];

    protected function casts(): array
    {
        return [
            'type' => MenuItemType::class,
            'sort_order' => 'integer',
            'is_visible' => 'boolean',
            'open_in_new_tab' => 'boolean',
        ];
    }

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class, 'page_id');
    }
}

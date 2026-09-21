<?php

namespace Modules\Website\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Website\Database\Factories\MenuFactory;

class Menu extends Model
{
    /** @use HasFactory<MenuFactory> */
    use HasFactory;

    protected $table = 'website_menus';

    protected $fillable = [
        'name',
        'location',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'menu_id')->orderBy('sort_order');
    }

    protected static function newFactory(): MenuFactory
    {
        return MenuFactory::new();
    }
}

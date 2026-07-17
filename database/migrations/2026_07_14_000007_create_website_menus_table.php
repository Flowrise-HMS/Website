<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('website_menus', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('location')->unique();
            $table->timestamps();
        });

        Schema::create('website_menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')
                ->constrained('website_menus')
                ->cascadeOnDelete();
            $table->string('label');
            $table->string('type');
            $table->foreignId('page_id')
                ->nullable()
                ->constrained('website_pages')
                ->nullOnDelete();
            $table->string('url')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_visible')->default(true);
            $table->boolean('open_in_new_tab')->default(false);
            $table->timestamps();

            $table->index(['menu_id', 'sort_order']);
            $table->index('is_visible');
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('website_menu_items');
        Schema::dropIfExists('website_menus');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('website_page_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')
                ->constrained('website_pages')
                ->cascadeOnDelete();
            $table->string('type');
            $table->json('payload')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_visible')->default(true);
            $table->timestamps();

            $table->index(['page_id', 'sort_order']);
            $table->index('type');
            $table->index('is_visible');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('website_page_sections');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('properties', function (Blueprint $table) {
        $table->index(['is_published', 'sort_order']);
        $table->index(['type']);
        $table->index(['bedrooms']);
        $table->index(['bathrooms']);
    });

    Schema::table('property_images', function (Blueprint $table) {
        $table->index(['property_id', 'is_primary', 'sort_order']);
    });

    Schema::table('faqs', function (Blueprint $table) {
        $table->index(['is_published', 'sort_order']);
    });

    Schema::table('testimonials', function (Blueprint $table) {
        $table->index(['is_published', 'sort_order']);
    });

}

public function down(): void
{
    Schema::table('properties', function (Blueprint $table) {
        $table->dropIndex(['is_published', 'sort_order']);
        $table->dropIndex(['type']);
        $table->dropIndex(['bedrooms']);
        $table->dropIndex(['bathrooms']);
    });
}
};

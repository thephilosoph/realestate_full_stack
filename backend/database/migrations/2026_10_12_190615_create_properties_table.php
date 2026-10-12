<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); 
        
        $table->json('title');
        $table->json('description')->nullable();
        $table->decimal('price', 15, 2);
        $table->json('location')->nullable();
        $table->integer('bedrooms')->default(0);
        $table->integer('bathrooms')->default(0);
        $table->string('type')->nullable(); 
        $table->boolean('is_published')->default(true);
        $table->integer('sort_order')->default(0);
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};

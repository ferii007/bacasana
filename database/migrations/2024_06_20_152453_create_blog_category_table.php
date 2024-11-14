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
        Schema::create('blog_category', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blog_id')->constrained(
                table: 'blog',
                indexName: 'blog_blog_id'
            )->onDelete('cascade');
            $table->foreignId('category_id')->constrained(
                table: 'categories',
                indexName: 'category_category_id'
            )->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_category');
    }
};

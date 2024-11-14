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
        Schema::create('blog', function (Blueprint $table) {
            $table->id()->primary();
            $table->foreignId('author_id')->constrained(
                table: 'users',
                indexName: 'blog_author_id'
            )->onDelete('cascade');
            $table->string('title')->unique();
            $table->string('slug')->unique();
            $table->longText('content');
            $table->bigInteger('read_duration');
            $table->string('read_duration_type', 25)->nullable();
            $table->text('image')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog');
    }
};

<?php

// database/migrations/2025_01_01_000002_create_posts_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();

            // Métadonnées
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('content')->nullable();
            $table->string('cover_image')->nullable();

            // Relations
            $table->foreignId('author_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete(); // nullable() avant constrained() [22][28]

            // Publication
            $table->enum('status', ['draft','scheduled','published'])->default('draft');
            $table->dateTime('published_at')->nullable();
            $table->unsignedInteger('reading_time')->nullable();
            
            $table->timestamps();
            $table->softDeletes();

            // Index
            $table->index(['status', 'published_at']);
            $table->index('category_id');
            $table->index('author_id');
        });
    }
    public function down(): void {
        Schema::dropIfExists('posts');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lab_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lab_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->timestamps();
            $table->unique(['lab_id','name']);
        });

        Schema::create('lab_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lab_category_id')->constrained('lab_categories')->cascadeOnDelete();
            $table->string('name');
            $table->timestamps();
            $table->unique(['lab_category_id','name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lab_types');
        Schema::dropIfExists('lab_categories');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('commercial_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commercial_id')->constrained('commercials')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['commercial_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commercial_user');
    }
};

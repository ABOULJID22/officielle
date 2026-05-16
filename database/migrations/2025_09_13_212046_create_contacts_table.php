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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
                $table->string('phone')->nullable();
            $table->string('user_type');
            $table->string('user_other')->nullable();
            $table->text('message');
                // Ajout des champs de réponse
                $table->text('reply_message')->nullable();
                $table->timestamp('replied_at')->nullable();
                $table->foreignId('replied_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};

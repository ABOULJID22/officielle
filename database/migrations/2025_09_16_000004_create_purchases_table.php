<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('lab_id')->constrained('labs')->cascadeOnDelete();
            $table->foreignId('lab_category_id')->nullable()->constrained('lab_categories')->nullOnDelete();
            $table->foreignId('lab_type_id')->nullable()->constrained('lab_types')->nullOnDelete();
            $table->string('type')->nullable();
            $table->foreignId('commercial_id')->nullable()->constrained('commercials')->nullOnDelete();
            $table->date('last_order_date')->nullable();
            $table->decimal('last_order_value', 12, 2)->nullable();
            $table->date('next_order_date')->nullable();
            $table->decimal('annual_target', 12, 2)->nullable();
            $table->string('status')->default('en_attente'); // en_attente, livree, annulee
            $table->json('attachments')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};

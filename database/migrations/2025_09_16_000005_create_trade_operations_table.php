<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('trade_operations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('lab_id')->constrained('labs')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->date('challenge_start')->nullable();
            $table->date('challenge_end')->nullable();
            $table->decimal('compensation', 12, 2)->nullable();
            $table->string('compensation_type')->default('amount'); // amount or percent
            $table->date('sent_at')->nullable();
            $table->string('via')->nullable();
            $table->string('contract_path')->nullable();
            $table->boolean('received')->default(false);
            $table->json('photos')->nullable();
            $table->json('attachments')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trade_operations');
    }
};

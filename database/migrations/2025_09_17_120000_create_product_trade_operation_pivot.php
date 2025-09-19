<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('product_trade_operation', function (Blueprint $table) {
            $table->unsignedBigInteger('trade_operation_id');
            $table->unsignedBigInteger('product_id');
            $table->primary(['trade_operation_id', 'product_id'], 'pto_primary');

            $table->foreign('trade_operation_id')->references('id')->on('trade_operations')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });

        // Backfill from existing single product_id column if present
            if (Schema::hasColumn('trade_operations', 'product_id')) {
                DB::table('trade_operations')
                    ->whereNotNull('product_id')
                    ->orderBy('id')
                    ->chunkById(500, function ($rows) {
                        foreach ($rows as $row) {
                            DB::table('product_trade_operation')->updateOrInsert([
                                'trade_operation_id' => $row->id,
                                'product_id' => $row->product_id,
                            ], []);
                        }
                    });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('product_trade_operation');
    }
};

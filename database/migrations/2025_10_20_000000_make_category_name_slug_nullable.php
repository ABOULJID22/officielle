<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // Note: running this migration requires the doctrine/dbal package: composer require doctrine/dbal
        Schema::table('categories', function (Blueprint $table) {
            $table->string('name')->nullable()->change();
            $table->string('slug')->nullable()->change();
        });
    }

    public function down(): void {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('name')->nullable(false)->change();
            $table->string('slug')->nullable(false)->change();
        });
    }
};

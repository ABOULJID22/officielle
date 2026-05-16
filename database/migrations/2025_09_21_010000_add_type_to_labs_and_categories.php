<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('labs', function (Blueprint $table) {
            if (! Schema::hasColumn('labs', 'type')) {
                $table->string('type')->default('trade')->after('name'); // 'trade' or 'purchase'
            }
        });

        Schema::table('lab_categories', function (Blueprint $table) {
            if (! Schema::hasColumn('lab_categories', 'category_type')) {
                $table->string('category_type')->nullable()->after('name'); // optional type for category
            }
        });
    }

    public function down(): void
    {
        Schema::table('labs', function (Blueprint $table) {
            if (Schema::hasColumn('labs', 'type')) {
                $table->dropColumn('type');
            }
        });

        Schema::table('lab_categories', function (Blueprint $table) {
            if (Schema::hasColumn('lab_categories', 'category_type')) {
                $table->dropColumn('category_type');
            }
        });
    }
};

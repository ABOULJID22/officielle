<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pharmacist_requests', function (Blueprint $table) {
            $table->string('applicant_name')->nullable()->after('user_id');
            $table->string('applicant_email')->nullable()->after('applicant_name');
            $table->string('phone')->nullable()->after('applicant_email');
            $table->string('pharmacy_name')->nullable()->after('phone');
            $table->text('pharmacy_address')->nullable()->after('pharmacy_name');
        });
    }

    public function down(): void
    {
        Schema::table('pharmacist_requests', function (Blueprint $table) {
            $table->dropColumn([
                'applicant_name','applicant_email','phone','pharmacy_name','pharmacy_address'
            ]);
        });
    }
};

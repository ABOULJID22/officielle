<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->string('bgvideo_url')->nullable()->after('video_id');
            $table->string('presentationvideo_url')->nullable()->after('bgvideo_url');
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn(['bgvideo_url', 'presentationvideo_url']);
        });
    }
};

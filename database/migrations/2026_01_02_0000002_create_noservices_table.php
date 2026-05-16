<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('noservices', function (Blueprint $table) {
            $table->id();
            // English fields (optional)
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->json('details')->nullable();
            $table->text('result')->nullable();
            // French fields (required)
            $table->string('titre')->nullable();
            $table->string('soustitre')->nullable();
            $table->json('detalserivces')->nullable();
            $table->text('resultats')->nullable();
        
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('noservices');
    }
};

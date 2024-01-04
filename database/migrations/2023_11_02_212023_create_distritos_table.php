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
        // Create Distritos Table with foreign Id relationship to Provincias and Cantones
        Schema::create('distritos', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('DistritoNumber');
            $table->string('distrito');
            $table->foreignId('provincias_id')->constrained();
            $table->foreignId('cantones_id')->constrained();
    
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distritos');
    }
};

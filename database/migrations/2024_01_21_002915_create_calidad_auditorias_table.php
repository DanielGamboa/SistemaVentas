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
        Schema::create('calidad_auditorias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('calidad_id')->constrained('calidads')->nullable();
            $table->unsignedInteger('sort')->nullable(); // Sort order for the grabaciones repeater
            // $table->foreignId('calidad_id')->constrained()->restrictOnDelete()->nullable();
            // $table->foreignId('user_id')->constrained('users')->nullable()
            $table->unsignedBigInteger('cargo_audios')->restrictOnDelete();
            $table->foreign('cargo_audios')->references('id')->on('users');;
            // $table->foreignId('user_id')->constrained()->restrictOnDelete()->nullable();
            $table->string('grabacion');
            $table->string('original_filename');
            $table->date('fecha_llamada')->nullable();
            $table->time('dia_hora_inicio')->nullable();
            $table->time('dia_hora_final')->nullable();
            $table->string('duracion');
            $table->unsignedInteger('durationseconds')->nullable();
            $table->unsignedInteger('hours')->nullable();
            $table->unsignedInteger('minutes')->nullable();
            $table->unsignedInteger('seconds')->nullable();
            
            $table->softDeletes();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calidad_auditoria');
    }
};

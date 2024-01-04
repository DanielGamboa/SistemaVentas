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
        Schema::create('calidads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->date('fecha_llamada');
            $table->time('dia_hora_inicio', 0)->nullable();
            $table->time('dia_hora_final', 0)->nullable();
            $table->string('motivo_evaluacion');
            $table->foreignId('venta_lineas_id')->constrained()->restrictOnDelete();
            $table->text('observaciones');
            $table->boolean('evaluacion_completa')->default(0);
            // Preguntas de evaluacion
            $table->json('bienvenida')->array();
            $table->json('empatia');
            $table->json('diccion');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calidad');
    }
};

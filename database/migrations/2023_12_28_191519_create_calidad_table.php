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
            // Backoffice that made the audit
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            // Agent that was audited
            $table->unsignedMediumInteger('agente')->constrained(table: 'users', indexName: 'id')->restrictOnDelete();
            // Reason for the audit
            $table->string('motivo_evaluacion');
            // In case of sales, related sale Id
            $table->foreignId('venta_lineas_id')->constrained()->restrictOnDelete();
            // Was the audit completed
            $table->boolean('evaluacion_completa')->default(0);
            // Obesrvations by backoffice regarding the audit
            $table->text('observaciones');
            
            // Preguntas de evaluacion
            $table->json('bienvenida')->array();
            $table->json('empatia')->nullable();
            $table->json('diccion')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }


    // Table Calidad_Audiorias related table
                // $table->date('fecha_llamada')->nullable();
                // $table->time('dia_hora_inicio', 0)->nullable();
                // $table->time('dia_hora_final', 0)->nullable();


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calidads');
    }
};

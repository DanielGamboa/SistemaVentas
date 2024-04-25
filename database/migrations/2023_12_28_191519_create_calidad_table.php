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
            // $table->unsignedMediumInteger('agente')->constrained(table: 'users', indexName: 'id')->restrictOnDelete();
            $table->unsignedBigInteger('agente');
            $table->foreign('agente')->references('id')->on('users');
            // Reason for the audit
            $table->string('motivo_evaluacion');
            // Phone nunmber called, to be audited
            $table->unsignedBigInteger('tlf')->nullable();
            // In case of sales, related sale Id
            // Had to change the type of the column to unsignedBigInteger bevause ->constrained() was not working
            // if was making the field not nullable.
            $table->unsignedBigInteger('venta_lineas_id')->nullable()->restrictOnDelete();
            $table->foreign('venta_lineas_id')->references('id')->on('venta_lineas');
            // $table->foreignId('venta_lineas_id')->nullable()->constrained()->restrictOnDelete();
            $table->string('ventas_telefono')->nullable();
            // Was the audit completed
            $table->boolean('evaluacion_completa')->default(0);
            // Obesrvations by backoffice regarding the audit
            $table->text('observaciones');
            
            // Preguntas de evaluacion
            /**
             * Create a json column for each question
             * 
             * bienvenida
             * empatia
             * sondeo
             * escucha_activa
             * oferta_comercial
             * numero_alternativo
             * aclara_dudas_cliente
             * manejo_objeciones
             * genera_ventas_irregulares
             * aceptacion_servicio
             * tecnicas_cierre
             * utiliza_tecnicas_cierre
             * validacion_venta
             * diccion
             * empatia_evalucion_agente
             * espera_vacios
             * escucha_activa_evaluacion_agente
             * evita_maltrato
             * abandono_llamada
             * liberty_negativo 
            */
            $table->json('bienvenida')->nullable();
            $table->json('empatia')->nullable();
            $table->json('sondeo')->nullable();
            $table->json('escucha_activa')->nullable();
            $table->json('oferta_comercial')->nullable();
            $table->json('numero_alternativo')->nullable();
            $table->json('aclara_dudas_cliente')->nullable();
            $table->json('manejo_objeciones')->nullable();
            $table->json('genera_ventas_irregulares')->nullable();
            $table->json('aceptacion_servicio')->nullable();
            $table->json('tecnicas_cierre')->nullable();
            $table->json('utiliza_tecnicas_cierre')->nullable();
            $table->json('validacion_venta')->nullable();
            $table->json('diccion')->nullable();
            $table->json('empatia_evalucion_agente')->nullable();
            $table->json('espera_vacios')->nullable();
            $table->json('escucha_activa_evaluacion_agente')->nullable();
            $table->json('evita_maltrato')->nullable();
            $table->json('abandono_llamada')->nullable();
            $table->json('liberty_negativo')->nullable();

            $table->string('calificacion')->nullable();


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

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('calidads', function (Blueprint $table) {
            //
            $table->string('ventas_telefono')->nullable();
            $table->string('grabacion')->nullable();
            // $table->date('fecha_llamada')->nullable();
            // $table->time('dia_hora_inicio')->nullable();
            // $table->time('dia_hora_final')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('venta_lineas', function (Blueprint $table) {
            //
            // $table->dropColumn('cliente_id');
        });
    }
};

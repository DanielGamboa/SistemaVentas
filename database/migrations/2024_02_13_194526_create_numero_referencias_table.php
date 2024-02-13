<?php

use App\Models\NumeroReferencia;
use App\Models\VentaLinea;
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
        Schema::create('numero_referencias', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('numeroreferencia');
            $table->string('contacto');
            $table->timestamps();
        });

    // set up pivot table between VentasLineas and NumeroReferencias numero_referencia_venta_linea
        Schema::create('numero_referencia_venta_linea', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(NumeroReferencia::class);
            $table->foreignIdFor(VentaLinea::class);
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('numero_referencias');
        // drop pivot table
        Schema::dropIfExists('numero_referencia_venta_linea');
    }
};

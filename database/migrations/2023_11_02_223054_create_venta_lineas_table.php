<?php

use App\Enums\VentaLineasEnum;
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
        Schema::create('venta_lineas', function (Blueprint $table) {
            $table->id();
            $table->string('VentaLinea', 15)->nullable(false); // VentaLineasEnum
            $table->string('plan', 17)->nullable(false); // PlanesLibertyLineasEnum
            $table->unsignedDecimal('precio', 8, 2)->nullable(false);  // PreciosPlanesLibertyLineasEnum
            $table->string('Estatus', 50)->nullable(false)->default('Evaluación Crediticia'); // EstatusVentaLineaEnum
            $table->unsignedBigInteger('tlf'); // Foreign ID --> numero de telefono llamado
            $table->boolean('tlf_venta_distinto'); // Boolean --- Telefono marcado es o no es igual al servicio vendido
            $table->unsignedBigInteger('tlf_marcado')->nullable(); // Cuando el numero marcado no es la misma linea que se vende
            $table->boolean('entrega_distinta'); // Bool direccion entrega vs. direccion facturacion
            $table->text('direccion_entrega')->nullable(); // direccion de entrega cuando es distinto al de facturacion
            // Provincias Table
            $table->unsignedBigInteger('provincias_id')->nullable();
            $table->foreign('provincias_id')->references('id')->on('provincias');
            // Cantones Table
            $table->unsignedBigInteger('cantones_id')->nullable();
            $table->foreign('cantones_id')->references('id')->on('cantones')->nullable();
            // $table->foreign('cantones_id')->references('id')->on('cantones');
            // Distritos table
            $table->unsignedBigInteger('distritos_id')->nullable();
            $table->foreign('distritos_id')->references('id')->on('distritos');
            $table->foreignId('user_id')->constrained()->restrictOnDelete();

            /*
            *  Cliente
            *       TipoDocumento Enum
            *       Documento (numero de cedula, pasaporte etc)
            *
            */
            $table->timestamp('created_at')->now();
            $table->timestamp('updated_at')->now();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('venta_lineas');
    }
};

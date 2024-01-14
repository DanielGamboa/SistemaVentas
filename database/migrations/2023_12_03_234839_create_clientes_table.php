<?php

use App\Models\Cliente;
use App\Models\User;
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
        // Create table for Clientes Model
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_documento');
            $table->string('documento', 30)->unique();
            $table->string('primer_nombre');
            $table->string('segundo_nombre')->nullable();
            $table->string('primer_apellido');
            $table->string('segundo_apellido')->nullable();
            // Virtual Columns
            $table->string('nombre_completo')->virtualAs('concat(primer_nombre, \' \',segundo_nombre, \' \', primer_apellido, \' \',segundo_apellido)')->nullable();
            $table->string('documento_nombre_completo')->virtualAs('concat(documento,\' \',primer_nombre,\' \', primer_apellido)');
            // Provincias Table
            $table->unsignedBigInteger('provincias_id');
            $table->foreign('provincias_id')->references('id')->on('provincias');
            // Cantones Table
            $table->unsignedBigInteger('cantones_id');
            $table->foreign('cantones_id')->references('id')->on('cantones');
            // $table->foreign('cantones_id')->references('id')->on('cantones');
            // Distritos table
            $table->unsignedBigInteger('distritos_id');
            $table->foreign('distritos_id')->references('id')->on('distritos');
            $table->text('direccion');

            // Document Images
            $table->boolean('documento_completo');
            $table->string('documento_img')->nullable();
            $table->string('imagen_doc')->nullable();

            // $table->boolean('entrega_distinta'); --> On succesfull migration delete
            // $table->text('direccion_entrega')->nullable(); -->on succesfull migration delete
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        // Create the Migration for ClienteDocumento model
        // Combined, these two migrations pluss their models, will allow us to make dinamic document loading
        // Schema::create('cliente_documento', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('documento_url')->nullable();
        //     $table->date('fecha_ingreso')->nullable();
        //     $table->boolean('documento_completo')->default(0);
        //     $table->foreignIdFor(Cliente::class)->index();
        //     $table->foreignIdFor(User::class)->index();
        //     $table->timestamps();
        //     $table->softDeletes();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
        // Schema::dropIfExists('cliente_documento');
    }
};

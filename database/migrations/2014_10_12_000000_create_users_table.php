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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nombre y apellido del vendedor Ej: Daniel Gamboa
            $table->string('cedula', 25); // Documento de identidad Ej: 19560922
            $table->string('usuario', 20)->unique(); // Usuario Ej: dgamboa
            $table->bigInteger('tlf'); // Telefono de contacto del agente personal
            $table->string('email')->unique(); // Correo electronico del vendedor Ej: dgamboa@test.com
            $table->timestamp('email_verified_at')->nullable(); // Hora de validacion del correo
            $table->date('fecha_ingreso');
            $table->string('estado', 8); // Usuario Activo, Inactivo
            $table->string('role', 20); // Role para permisos del usuario.
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

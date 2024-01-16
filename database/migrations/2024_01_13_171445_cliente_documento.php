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
        // Pivot table for ClienteDocumento
        Schema::create('cliente_documento', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->nullable();
            // Target media table id
            $table->foreignId('media_id')->nullable();
            // Target Media table -> model_id column
            $table->foreignId('model_id')->constrained(table: 'media', indexName: 'model_id')->onDelete('cascade')->autoIncrement()->nullable();
            // $table->unsignedBigInteger('model_id')->nullable();
            // $table->foreign('model_id')->references('model_id')->on('media')->onDelete('cascade');
            // $table->index('model_id');
            // $table->foreign('model_id')->references('model_id')->on('media')->constrained();
            // $table->foreign('model_id')->references('model_id')->on('media')->nullable();
            // $table->foreignId('model_id');
            $table->string('documento_img')->nullable();
            $table->string('imagen_doc')->nullable();
            $table->timestamps();
        });
    }
    /*
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cliente_documento');
    }

};

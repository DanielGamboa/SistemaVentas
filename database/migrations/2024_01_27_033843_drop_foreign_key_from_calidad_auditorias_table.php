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
        Schema::table('calidad_auditorias', function (Blueprint $table) {
            //Drop foreign key from calidad_auditorias table
            // $table->dropForeign('calidad_auditorias_calidad_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        
        Schema::table('calidad_auditorias', function (Blueprint $table) {
            //
            // $table->foreign('calidad_id')->references('id')->on('calidads')->onDelete('restrict');
        });
    }
};

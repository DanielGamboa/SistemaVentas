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
        Schema::create('estatus_count', function (Blueprint $table) {
            $table->id();
            $table->string('estatus', 50);
            $table->unsignedBigInteger('count')->default(0);
            $table->unsignedBigInteger('count_week')->default(0);
            $table->unsignedBigInteger('count_month')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estatus_count');
    }
};

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
        Schema::table('estatus_count', function (Blueprint $table) {
            $table->unsignedBigInteger('total_sale')->default(0)->after('count_month');
            $table->unsignedBigInteger('month_sale')->default(0)->after('total_sale');
            $table->unsignedBigInteger('week_sale')->default(0)->after('month_sale');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('estatus_count', function (Blueprint $table) {
            //
            $table->dropColumn('total_sale');
            $table->dropColumn('month_sale');
            $table->dropColumn('week_sale');
        });
    }
};

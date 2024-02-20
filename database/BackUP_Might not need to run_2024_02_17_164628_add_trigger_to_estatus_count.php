<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create triggers for estatus_count table.
        Schema::table('estatus_count', function (Blueprint $table) {
            //  After insert
            DB::unprepared('CREATE TRIGGER update_estatus_count_after_insert AFTER INSERT ON venta_lineas
                FOR EACH ROW
                BEGIN
                    DECLARE estatus_value VARCHAR(50);
                    SET estatus_value = NEW.Estatus;

                    UPDATE estatus_count
                    SET count = count + 1
                    WHERE estatus COLLATE utf8mb4_unicode_ci = estatus_value COLLATE utf8mb4_unicode_ci;
                END
            ');
            // After update
            DB::unprepared('CREATE TRIGGER update_estatus_count_after_update AFTER UPDATE ON venta_lineas
                FOR EACH ROW
                BEGIN
                    DECLARE old_estatus_value VARCHAR(50);
                    DECLARE new_estatus_value VARCHAR(50);
                    SET old_estatus_value = OLD.Estatus;
                    SET new_estatus_value = NEW.Estatus;

                    IF old_estatus_value <> new_estatus_value THEN
                        UPDATE estatus_count
                        SET count = count - 1
                        WHERE estatus COLLATE utf8mb4_unicode_ci = old_estatus_value COLLATE utf8mb4_unicode_ci;

                        UPDATE estatus_count
                        SET count = count + 1
                        WHERE estatus COLLATE utf8mb4_unicode_ci = new_estatus_value COLLATE utf8mb4_unicode_ci;
                    END IF;
                END
            ');

            // After delete
            DB::unprepared('CREATE TRIGGER update_estatus_count_after_delete AFTER DELETE ON venta_lineas
            FOR EACH ROW
            BEGIN
                DECLARE estatus_value VARCHAR(50);
                SET estatus_value = OLD.Estatus;
        
                UPDATE estatus_count
                SET count = count - 1
                WHERE estatus COLLATE utf8mb4_unicode_ci = estatus_value COLLATE utf8mb4_unicode_ci;
            END
        ');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('estatus_count', function (Blueprint $table) {
            //
            DB::unprepared('DROP TRIGGER IF EXISTS update_estatus_count_after_insert');
            DB::unprepared('DROP TRIGGER IF EXISTS update_estatus_count_after_update');
            DB::unprepared('DROP TRIGGER IF EXISTS update_estatus_count_after_delete');
        });
    }
};

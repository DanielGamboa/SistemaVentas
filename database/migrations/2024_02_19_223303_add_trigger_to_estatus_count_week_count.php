<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * In these triggers, I added IF conditions to check if the created_at date of the record being inserted, updated, or deleted
     * falls within the current week, month, year. If it does, the triggers update the count_week column, count_month and the count column. 
     * The WEEK(date, mode) function returns the week number for a given date, with the mode parameter determining the first day of the week.
     * In this case, I used 1 as the mode parameter to set Monday as the first day of the week.
     * 
     * These triggers use the IF(count > 0, count - 1, 0) and IF(count_week > 0, count_week - 1, 0) and SET count_month = IF(count_month > 0, count_month - 1, 0) 
     * expressions to ensure that count and count_week never go below 0. If the current value is greater than 0, it subtracts 1;
     * otherwise, it sets the value to 0.
     * 
     * In these triggers, I added YEAR(NEW.created_at) = YEAR(CURDATE()) AND and YEAR(OLD.created_at) = YEAR(CURDATE()) AND to the IF conditions 
     * to check if the created_at date of the record being inserted, updated, or deleted falls within the current year as well as the current week.
     */
    public function up(): void
    {
        Schema::table('estatus_count', function (Blueprint $table) {
            // After insert
            DB::unprepared('CREATE TRIGGER update_estatus_count_week_after_insert AFTER INSERT ON venta_lineas
                FOR EACH ROW
                BEGIN
                    DECLARE estatus_value VARCHAR(50);
                    SET estatus_value = NEW.Estatus;

                    UPDATE estatus_count
                    SET count = count + 1
                    WHERE estatus COLLATE utf8mb4_unicode_ci = estatus_value COLLATE utf8mb4_unicode_ci;
                
                    IF YEAR(NEW.created_at) = YEAR(CURDATE()) AND WEEK(NEW.created_at, 1) = WEEK(CURDATE(), 1) THEN
                        UPDATE estatus_count
                        SET count_week = count_week + 1
                        WHERE estatus COLLATE utf8mb4_unicode_ci = estatus_value COLLATE utf8mb4_unicode_ci;
                    END IF;
                
                    IF YEAR(NEW.created_at) = YEAR(CURDATE()) AND MONTH(NEW.created_at) = MONTH(CURDATE()) THEN
                        UPDATE estatus_count
                        SET count_month = count_month + 1
                        WHERE estatus COLLATE utf8mb4_unicode_ci = estatus_value COLLATE utf8mb4_unicode_ci;
                    END IF;
                END
            ');

            
            // After update
            DB::unprepared('CREATE TRIGGER update_estatus_count_week_after_update AFTER UPDATE ON venta_lineas
                FOR EACH ROW
                BEGIN
                    DECLARE old_estatus_value VARCHAR(50);
                    DECLARE new_estatus_value VARCHAR(50);
                    SET old_estatus_value = OLD.Estatus;
                    SET new_estatus_value = NEW.Estatus;

                    IF old_estatus_value <> new_estatus_value THEN
                        UPDATE estatus_count
                                SET count = IF(count > 0, count - 1, 0)
                                WHERE estatus COLLATE utf8mb4_unicode_ci = old_estatus_value COLLATE utf8mb4_unicode_ci;

                                UPDATE estatus_count
                                SET count = count + 1
                                WHERE estatus COLLATE utf8mb4_unicode_ci = new_estatus_value COLLATE utf8mb4_unicode_ci;


                        IF YEAR(OLD.created_at) = YEAR(CURDATE()) AND WEEK(OLD.created_at, 1) = WEEK(CURDATE(), 1) THEN
                            UPDATE estatus_count
                            SET count_week = IF(count_week > 0, count_week - 1, 0)
                            WHERE estatus COLLATE utf8mb4_unicode_ci = old_estatus_value COLLATE utf8mb4_unicode_ci;
                        END IF;

                        IF YEAR(OLD.created_at) = YEAR(CURDATE()) AND MONTH(OLD.created_at) = MONTH(CURDATE()) THEN
                            UPDATE estatus_count
                            SET count_month = IF(count_month > 0, count_month - 1, 0)
                            WHERE estatus COLLATE utf8mb4_unicode_ci = old_estatus_value COLLATE utf8mb4_unicode_ci;
                        END IF;

                        IF YEAR(NEW.created_at) = YEAR(CURDATE()) AND WEEK(NEW.created_at, 1) = WEEK(CURDATE(), 1) THEN
                            UPDATE estatus_count
                            SET count_week = count_week + 1
                            WHERE estatus COLLATE utf8mb4_unicode_ci = new_estatus_value COLLATE utf8mb4_unicode_ci;
                        END IF;

                        IF YEAR(NEW.created_at) = YEAR(CURDATE()) AND MONTH(NEW.created_at) = MONTH(CURDATE()) THEN
                            UPDATE estatus_count
                            SET count_month = count_month + 1
                            WHERE estatus COLLATE utf8mb4_unicode_ci = new_estatus_value COLLATE utf8mb4_unicode_ci;
                        END IF;
                    END IF;
                END
            ');


            // After delete
            DB::unprepared('CREATE TRIGGER update_estatus_count_week_after_delete AFTER DELETE ON venta_lineas
            FOR EACH ROW
            BEGIN
                DECLARE estatus_value VARCHAR(50);
                SET estatus_value = OLD.Estatus;

                UPDATE estatus_count
                SET count = IF(count > 0, count - 1, 0)
                WHERE estatus COLLATE utf8mb4_unicode_ci = estatus_value COLLATE utf8mb4_unicode_ci;

        
                IF YEAR(OLD.created_at) = YEAR(CURDATE()) AND WEEK(OLD.created_at, 1) = WEEK(CURDATE(), 1) THEN
                    UPDATE estatus_count
                    SET count_week = IF(count_week > 0, count_week - 1, 0)
                    WHERE estatus COLLATE utf8mb4_unicode_ci = estatus_value COLLATE utf8mb4_unicode_ci;
                END IF;
        
                IF YEAR(OLD.created_at) = YEAR(CURDATE()) AND MONTH(OLD.created_at) = MONTH(CURDATE()) THEN
                    UPDATE estatus_count
                    SET count_month = IF(count_month > 0, count_month - 1, 0)
                    WHERE estatus COLLATE utf8mb4_unicode_ci = estatus_value COLLATE utf8mb4_unicode_ci;
                END IF;
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
            DB::unprepared('DROP TRIGGER IF EXISTS update_estatus_count_week_after_insert');
            DB::unprepared('DROP TRIGGER IF EXISTS update_estatus_count_week_after_update');
            DB::unprepared('DROP TRIGGER IF EXISTS update_estatus_count_week_after_delete');
            // If there are any problems with the triggers, you can drop them using the following code:
            // DB::unprepared('DROP TRIGGER IF EXISTS update_estatus_count_after_insert');
            // DB::unprepared('DROP TRIGGER IF EXISTS update_estatus_count_after_update');
            // DB::unprepared('DROP TRIGGER IF EXISTS update_estatus_count_after_delete');
        });
    }
};

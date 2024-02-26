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
        Schema::table('estatus_count', function (Blueprint $table) {
            // After Insert
            // DB::unprepared('CREATE TRIGGER update_estatus_count_sales_after_insert AFTER INSERT ON venta_lineas
            //     FOR EACH ROW
            //     BEGIN
            //         DECLARE estatus_value VARCHAR(50);
            //         SET estatus_value = NEW.Estatus;

            //         UPDATE estatus_count
            //         SET total_sale = total_sale + NEW.precio
            //         WHERE estatus COLLATE utf8mb4_unicode_ci = estatus_value COLLATE utf8mb4_unicode_ci;

            //         IF YEAR(NEW.created_at) = YEAR(CURDATE()) AND WEEK(NEW.created_at, 1) = WEEK(CURDATE(), 1) THEN
            //             UPDATE estatus_count
            //             SET week_sale = week_sale + NEW.precio
            //             WHERE estatus COLLATE utf8mb4_unicode_ci = estatus_value COLLATE utf8mb4_unicode_ci;
            //         END IF;

            //         IF YEAR(NEW.created_at) = YEAR(CURDATE()) AND MONTH(NEW.created_at) = MONTH(CURDATE()) THEN
            //             UPDATE estatus_count
            //             SET month_sale = month_sale + NEW.precio
            //             WHERE estatus COLLATE utf8mb4_unicode_ci = estatus_value COLLATE utf8mb4_unicode_ci;
            //         END IF;
            //     END
            // ');
            DB::unprepared('CREATE TRIGGER update_estatus_count_sales_after_insert AFTER INSERT ON venta_lineas
                FOR EACH ROW
                BEGIN
                    DECLARE estatus_value VARCHAR(50);
                    SET estatus_value = NEW.Estatus;

                    UPDATE estatus_count
                    SET total_sale = total_sale + NEW.precio,
                        week_sale = IF(YEAR(NEW.created_at) = YEAR(CURDATE()) AND WEEK(NEW.created_at, 1) = WEEK(CURDATE(), 1), week_sale + NEW.precio, week_sale),
                        month_sale = IF(YEAR(NEW.created_at) = YEAR(CURDATE()) AND MONTH(NEW.created_at) = MONTH(CURDATE()), month_sale + NEW.precio, month_sale)
                    WHERE estatus COLLATE utf8mb4_unicode_ci = estatus_value COLLATE utf8mb4_unicode_ci;
                END
            ');
            // After update
            // DB::unprepared('CREATE TRIGGER update_estatus_count_sales_after_update AFTER UPDATE ON venta_lineas
            //     FOR EACH ROW
            //     BEGIN
            //         DECLARE estatus_value VARCHAR(50);
            //         SET estatus_value = NEW.Estatus;

            //         IF OLD.precio <> NEW.precio THEN
            //             UPDATE estatus_count
            //             SET total_sale = IF(total_sale > OLD.precio, total_sale - OLD.precio, 0) + NEW.precio
            //             WHERE estatus COLLATE utf8mb4_unicode_ci = estatus_value COLLATE utf8mb4_unicode_ci;

            //             IF YEAR(NEW.created_at) = YEAR(CURDATE()) AND WEEK(NEW.created_at, 1) = WEEK(CURDATE(), 1) THEN
            //                 UPDATE estatus_count
            //                 SET week_sale = IF(week_sale > OLD.precio, week_sale - OLD.precio, 0) + NEW.precio
            //                 WHERE estatus COLLATE utf8mb4_unicode_ci = estatus_value COLLATE utf8mb4_unicode_ci;
            //             END IF;

            //             IF YEAR(NEW.created_at) = YEAR(CURDATE()) AND MONTH(NEW.created_at) = MONTH(CURDATE()) THEN
            //                 UPDATE estatus_count
            //                 SET month_sale = IF(month_sale > OLD.precio, month_sale - OLD.precio, 0) + NEW.precio
            //                 WHERE estatus COLLATE utf8mb4_unicode_ci = estatus_value COLLATE utf8mb4_unicode_ci;
            //             END IF;
            //         END IF;
            //     END
            // ');
        //     DB::unprepared('CREATE TRIGGER update_estatus_count_sales_after_update AFTER UPDATE ON venta_lineas
        //     FOR EACH ROW
        //     BEGIN
        //         DECLARE old_estatus_value VARCHAR(50);
        //         DECLARE new_estatus_value VARCHAR(50);
        //         SET old_estatus_value = OLD.Estatus;
        //         SET new_estatus_value = NEW.Estatus;
        
        //         IF OLD.estatus <> NEW.estatus OR OLD.precio <> NEW.precio THEN
        //             -- Subtract OLD.precio from OLD.estatus count
        //             UPDATE estatus_count
        //             SET total_sale = IF(total_sale > OLD.precio, total_sale - OLD.precio, 0),
        //                 week_sale = IF(week_sale > OLD.precio AND YEAR(OLD.created_at) = YEAR(CURDATE()) AND WEEK(OLD.created_at, 1) = WEEK(CURDATE(), 1), week_sale - OLD.precio, week_sale),
        //                 month_sale = IF(month_sale > OLD.precio AND YEAR(OLD.created_at) = YEAR(CURDATE()) AND MONTH(OLD.created_at) = MONTH(CURDATE()), month_sale - OLD.precio, month_sale)
        //             WHERE estatus COLLATE utf8mb4_unicode_ci = old_estatus_value COLLATE utf8mb4_unicode_ci;
        
        //             -- Add NEW.precio to NEW.estatus count
        //             UPDATE estatus_count
        //             SET total_sale = total_sale + NEW.precio,
        //                 week_sale = IF(YEAR(NEW.created_at) = YEAR(CURDATE()) AND WEEK(NEW.created_at, 1) = WEEK(CURDATE(), 1), week_sale + NEW.precio, week_sale),
        //                 month_sale = IF(YEAR(NEW.created_at) = YEAR(CURDATE()) AND MONTH(NEW.created_at) = MONTH(CURDATE()), month_sale + NEW.precio, month_sale)
        //             WHERE estatus COLLATE utf8mb4_unicode_ci = new_estatus_value COLLATE utf8mb4_unicode_ci;
        //         END IF;
        //     END
        // ');

//         DB::unprepared('CREATE TRIGGER update_estatus_count_sales_after_update AFTER UPDATE ON venta_lineas
//     FOR EACH ROW
//     BEGIN
//         DECLARE old_estatus_value VARCHAR(50);
//         DECLARE new_estatus_value VARCHAR(50);
//         SET old_estatus_value = OLD.Estatus;
//         SET new_estatus_value = NEW.Estatus;

//         IF OLD.estatus <> NEW.estatus OR OLD.precio <> NEW.precio THEN
//             -- Subtract OLD.precio from OLD.estatus count
//             UPDATE estatus_count
//             SET total_sale = IF(total_sale > OLD.precio, total_sale - OLD.precio, 0),
//                 week_sale = IF(week_sale > OLD.precio AND YEAR(OLD.created_at) = YEAR(NEW.created_at) AND WEEK(OLD.created_at, 1) = WEEK(NEW.created_at, 1), week_sale - OLD.precio, week_sale),
//                 month_sale = IF(month_sale > OLD.precio AND YEAR(OLD.created_at) = YEAR(NEW.created_at) AND MONTH(OLD.created_at) = MONTH(NEW.created_at), month_sale - OLD.precio, month_sale)
//             WHERE estatus COLLATE utf8mb4_unicode_ci = old_estatus_value COLLATE utf8mb4_unicode_ci;

//             -- Add NEW.precio to NEW.estatus count
//             UPDATE estatus_count
//             SET total_sale = total_sale + NEW.precio,
//                 week_sale = IF(YEAR(NEW.created_at) = YEAR(CURDATE()) AND WEEK(NEW.created_at, 1) = WEEK(CURDATE(), 1), week_sale + NEW.precio, week_sale),
//                 month_sale = IF(YEAR(NEW.created_at) = YEAR(CURDATE()) AND MONTH(NEW.created_at) = MONTH(CURDATE()), month_sale + NEW.precio, month_sale)
//             WHERE estatus COLLATE utf8mb4_unicode_ci = new_estatus_value COLLATE utf8mb4_unicode_ci;
//         END IF;
//     END
// ');

DB::unprepared('CREATE TRIGGER update_estatus_count_sales_after_update AFTER UPDATE ON venta_lineas
    FOR EACH ROW
    BEGIN
        DECLARE old_estatus_value VARCHAR(50);
        DECLARE new_estatus_value VARCHAR(50);
        SET old_estatus_value = OLD.Estatus;
        SET new_estatus_value = NEW.Estatus;

        IF OLD.estatus <> NEW.estatus OR OLD.precio <> NEW.precio THEN
            -- Subtract OLD.precio from OLD.estatus count
            UPDATE estatus_count
            SET total_sale = IF(total_sale > OLD.precio, total_sale - OLD.precio, 0),
                week_sale = IF(YEAR(OLD.created_at) = YEAR(NEW.created_at) AND WEEK(OLD.created_at, 1) = WEEK(NEW.created_at, 1), week_sale - OLD.precio, 0),
                month_sale = IF(month_sale > OLD.precio AND YEAR(OLD.created_at) = YEAR(NEW.created_at) AND MONTH(OLD.created_at) = MONTH(NEW.created_at), month_sale - OLD.precio, month_sale)
            WHERE estatus COLLATE utf8mb4_unicode_ci = old_estatus_value COLLATE utf8mb4_unicode_ci;

            -- Add NEW.precio to NEW.estatus count
            UPDATE estatus_count
            SET total_sale = total_sale + NEW.precio,
                week_sale = IF(YEAR(NEW.created_at) = YEAR(CURDATE()) AND WEEK(NEW.created_at, 1) = WEEK(CURDATE(), 1), week_sale + NEW.precio, week_sale),
                month_sale = IF(YEAR(NEW.created_at) = YEAR(CURDATE()) AND MONTH(NEW.created_at) = MONTH(CURDATE()), month_sale + NEW.precio, month_sale)
            WHERE estatus COLLATE utf8mb4_unicode_ci = new_estatus_value COLLATE utf8mb4_unicode_ci;
        END IF;
    END
');

            // After delete
            // DB::unprepared('CREATE TRIGGER update_estatus_count_sales_after_delete AFTER DELETE ON venta_lineas
            //     FOR EACH ROW
            //     BEGIN
            //         DECLARE estatus_value VARCHAR(50);
            //         SET estatus_value = OLD.Estatus;

            //         UPDATE estatus_count
            //         SET total_sale = IF(total_sale > OLD.precio, total_sale - OLD.precio, 0)
            //         WHERE estatus COLLATE utf8mb4_unicode_ci = estatus_value COLLATE utf8mb4_unicode_ci;

            //         IF YEAR(OLD.created_at) = YEAR(CURDATE()) AND WEEK(OLD.created_at, 1) = WEEK(CURDATE(), 1) THEN
            //             UPDATE estatus_count
            //             SET week_sale = IF(week_sale > OLD.precio, week_sale - OLD.precio, 0)
            //             WHERE estatus COLLATE utf8mb4_unicode_ci = estatus_value COLLATE utf8mb4_unicode_ci;
            //         END IF;

            //         IF YEAR(OLD.created_at) = YEAR(CURDATE()) AND MONTH(OLD.created_at) = MONTH(CURDATE()) THEN
            //             UPDATE estatus_count
            //             SET month_sale = IF(month_sale > OLD.precio, month_sale - OLD.precio, 0)
            //             WHERE estatus COLLATE utf8mb4_unicode_ci = estatus_value COLLATE utf8mb4_unicode_ci;
            //         END IF;
            //     END
            // ');

            // week_sale = IF(week_sale > OLD.precio AND YEAR(OLD.created_at) = YEAR(CURDATE()) AND WEEK(OLD.created_at, 1) = WEEK(CURDATE(), 1), week_sale - OLD.precio, week_sale),
            // month_sale = IF(month_sale > OLD.precio AND YEAR(OLD.created_at) = YEAR(CURDATE()) AND MONTH(OLD.created_at) = MONTH(CURDATE()), month_sale - OLD.precio, month_sale)
            DB::unprepared('CREATE TRIGGER update_estatus_count_sales_after_delete AFTER DELETE ON venta_lineas
            FOR EACH ROW
            BEGIN
                DECLARE estatus_value VARCHAR(50);
                SET estatus_value = OLD.Estatus;
        
                UPDATE estatus_count
                SET total_sale = IF(total_sale > OLD.precio, total_sale - OLD.precio, 0),
                    week_sale = IF((SELECT COUNT(*) FROM venta_lineas WHERE YEAR(created_at) = YEAR(CURDATE()) AND WEEK(created_at, 1) = WEEK(CURDATE(), 1) AND estatus = OLD.estatus) > 0, IF(week_sale > OLD.precio, week_sale - OLD.precio, 0), 0),
                    month_sale = IF((SELECT COUNT(*) FROM venta_lineas WHERE YEAR(created_at) = YEAR(CURDATE()) AND MONTH(created_at) = MONTH(CURDATE()) AND estatus = OLD.estatus) > 0, IF(month_sale > OLD.precio, month_sale - OLD.precio, 0), 0)
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
            // Drop triggers
            DB::unprepared('DROP TRIGGER IF EXISTS update_estatus_count_sales_after_insert');
            DB::unprepared('DROP TRIGGER IF EXISTS update_estatus_count_sales_after_update');
            DB::unprepared('DROP TRIGGER IF EXISTS update_estatus_count_sales_after_delete');
        });
    }
};

-- DO NOT RUN THIS FILE!!!! IT IS FOR NOTES ONLY
--
--
--
-- NotesTriggers
--
-- Mysql trigger syntax

CREATE TRIGGER name
	[BEFORE, AFTER] [INSERT, UPDATE, DELETE] ON TableName
	FOR EACH ROW (this can be one or more rows)
	SET NEW.salary = (NEW.hourly_pay * 2080);

SHOW TRIGGERS;

-- Modify one record on this table
UPDATE employees
SET hourly_pay = 50
WHERE employee_id = 1;
SELECT * FROM employees;

-- Modify all records on this table by adding 1 to each row
UPDATE employees
SET hourly_pay = hourly_pay + 1
SELECT * FROM employees;

-- Before insert when creating a new record
CREATE TRIGGER before_hourly_pay_insert
BEFORE INSERT ON employees
FOR EACH ROW
SET NEW.salary = (NEW.hourly_pay * 2080);

========================================================
Example:

CREATE TABLE expenses (
	expense_id int primary key,
	expense_name varchar(50),
	expense_total decimal (10, 2)
	);

INSERT INTO expenses 
VALUE   (1, "salaries", 0),
	(2, "supplies", 0),
	(3, "taxed", 0);

-- Add total for column salary from employees table
UPDATE expenses
SET expense_total = (SELECT SUM(salary) FROM employees)
WHERE expense_name = "salaries";

-- Turn this function into a trigger for Create, Update and delete
-- This updates on delete
CREATE TRIGGER after_salary_delete
AFTER DELETE ON employees
FOR EACH ROW
UPDATE expenses 
SET expense_total = expense_total - OLD.salary
WHERE expense_namme = "salaries";

-- This updates on create
CREATE TRIGGER after_salary_insert
AFTER INSERT ON employees
FOR EACH ROW
UPDATE expenses
SET expense_total = expense_total + NEW.salary
WHERE expense_name = "salaries"

-- This will update expense salaries on update 
CREATE TRIGGER after_salary_update
AFTER UPDATE ON employees
FOR EACH ROW
UPDATE expenses
SET expense_total = expense_total + (NEW.salary - OLD.salary)
WHERE expense_name = "salaries";


-------------------------------------------------------- WORKING OVER THIS EXAMPLE --------------------------------------------------------
-- Migration: Create a table for planes_counts
public function up()
{
    Schema::create('planes_counts', function (Blueprint $table) {
        $table->string('planes');
        $table->integer('count')->default(0);
        $table->timestamps();
    });
}

-- Next, you'll need to create a database trigger to update the count column whenever a row is:
	-- inserted, 
	-- updated,
	-- deleted 
-- in the linea_ventas table.
-- Creating a trigger involves writing raw SQL, in the migration. 
-- You can use the DB::unprepared method to execute raw SQL queries.
-- This should be placed in a new migration file, not the one that created the planes_counts table.
-- All triggers should be created in the up method of the migration file.
-- All triggers should be removed in the down method of the migration file.
-- All triggers should be named uniquely, so that they can be removed in the down method of the migration file.
-- All triggers should be created after all related tables have been created.
-- Here's an example for MySQL:

-- Create / insert
DB::unprepared('
    CREATE TRIGGER update_planes_count_after_insert AFTER INSERT ON linea_ventas
    FOR EACH ROW
    BEGIN
        UPDATE planes_counts
        SET count = count + 1
        WHERE planes = NEW.estatus;
    END
');

-- Edit / update
DB::unprepared('
    CREATE TRIGGER update_planes_count_after_update AFTER UPDATE ON linea_ventas
    FOR EACH ROW
    BEGIN
        IF NEW.estatus <> OLD.estatus THEN
            UPDATE planes_counts
            SET count = count - 1
            WHERE planes = OLD.estatus;

            UPDATE planes_counts
            SET count = count + 1
            WHERE planes = NEW.estatus;
        END IF;
    END
');

-- Delete
DB::unprepared('
    CREATE TRIGGER update_planes_count_after_delete AFTER DELETE ON linea_ventas
    FOR EACH ROW
    BEGIN
        UPDATE planes_counts
        SET count = count - 1
        WHERE planes = OLD.estatus;
    END
');

-------------------------------------------------------- WORKING OVER THIS EXAMPLE --------------------------------------------------------

-- Migration: Create a table for planes_counts
public function up()
{
	Schema::create('planes_counts', function (Blueprint $table) {
		$table->string('planes');
		$table->integer('count')->default(0);
		$table->timestamps();
	});
}

-- Next, you'll need to create a database trigger to update the count column whenever a row is:
	-- inserted, 
	-- updated,
	-- deleted
-- in the linea_ventas table.

-- Creating a trigger involves writing raw SQL, in the migration.
-- You can use the DB::unprepared method to execute raw SQL queries.
-- This should be placed in a new migration file, not the one that created the planes_counts table.
-- All triggers should be created in the up method of the migration file.
-- All triggers should be removed in the down method of the migration file.
-- All triggers should be named uniquely, so that they can be removed in the down method of the migration file.
-- All triggers should be created after all related tables have been created.
-- Here's an example for MySQL:

-- To achieve this, you can create three triggers: AFTER INSERT, AFTER UPDATE, and AFTER DELETE. 
-- These triggers will update the estatus_count table whenever a row is inserted, updated, or deleted in the venta_lineas table.

-- These triggers use MySQL variables (estatus_value, old_estatus_value, new_estatus_value) to store the Estatus value from the inserted,
-- updated, or deleted row. The estatus_count table is then updated based on these values.


-- Create / insert
-- After insert
DB::unprepared('
    CREATE TRIGGER update_estatus_count_after_insert AFTER INSERT ON venta_lineas
    FOR EACH ROW
    BEGIN
        DECLARE estatus_value VARCHAR(255);
        SET estatus_value = NEW.Estatus;

        UPDATE estatus_count
        SET estatus_count = estatus_count + 1
        WHERE estatus = estatus_value;
    END
');

-- After update
DB::unprepared('
    CREATE TRIGGER update_estatus_count_after_update AFTER UPDATE ON venta_lineas
    FOR EACH ROW
    BEGIN
        DECLARE old_estatus_value VARCHAR(255);
        DECLARE new_estatus_value VARCHAR(255);
        SET old_estatus_value = OLD.Estatus;
        SET new_estatus_value = NEW.Estatus;

        IF old_estatus_value <> new_estatus_value THEN
            UPDATE estatus_count
            SET estatus_count = estatus_count - 1
            WHERE estatus = old_estatus_value;

            UPDATE estatus_count
            SET estatus_count = estatus_count + 1
            WHERE estatus = new_estatus_value;
        END IF;
    END
');

-- After delete
DB::unprepared('
    CREATE TRIGGER update_estatus_count_after_delete AFTER DELETE ON venta_lineas
    FOR EACH ROW
    BEGIN
        DECLARE estatus_value VARCHAR(255);
        SET estatus_value = OLD.Estatus;

        UPDATE estatus_count
        SET estatus_count = estatus_count - 1
        WHERE estatus = estatus_value;
    END
');


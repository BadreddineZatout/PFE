<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeftoversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement($this->createView());
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement($this->dropView());
    }

    /**
     * Create the view.
     *
     * @return void
     */

    private function createView(): string

    {
        return <<<SQL
                CREATE VIEW leftovers AS 
                    SELECT menus.id, 
                    menus.quantity, 
                    (quantity - SUM( CASE WHEN has_ate = true THEN 1 ELSE 0 END )) as leftovers, 
                    menus.created_at 
                    FROM menus JOIN food_reservations 
                    ON menus.id = food_reservations.menu_id 
                    GROUP BY menus.id, menus.quantity, menus.created_at
            SQL;
    }

    /**
     * Drop The View.
     *
     * @return void
     */

    private function dropView(): string
    {
        return <<<SQL
            DROP VIEW IF EXISTS `leftovers`;
            SQL;
    }
}

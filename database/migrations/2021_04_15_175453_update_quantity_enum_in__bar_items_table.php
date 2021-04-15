<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateQuantityEnumInBarItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // DB::statement("ALTER TABLE bar_items CHANGE COLUMN quantity quantity ENUM('30ML', '60ML', 'QTR', 'HALF', 'FULL', 'GLASS', 'PER PC', 'PACKET') NULL ");
        DB::statement("ALTER TABLE bar_items MODIFY COLUMN quantity ENUM('30ML', '60ML', 'QTR', 'HALF', 'FULL', 'GLASS', 'PER PC', 'PACKET') NULL ");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE bar_items MODIFY COLUMN quantity ENUM('30ML', '60ML', 'QRT', 'HALF', 'FULL', 'GLASS', 'PER PC', 'PACKET') NULL ");
    }
}

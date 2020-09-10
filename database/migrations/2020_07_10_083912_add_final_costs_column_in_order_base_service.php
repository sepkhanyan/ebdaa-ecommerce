<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFinalCostsColumnInOrderBaseService extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mshop_order_base_service', function (Blueprint $table) {
            $table->decimal('final_costs', 12, 2)->default(0.00)->after('tax');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mshop_order_base_service', function (Blueprint $table) {
            $table->dropColumn('final_costs');
        });
    }
}

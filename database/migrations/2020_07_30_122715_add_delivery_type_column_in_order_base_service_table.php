<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeliveryTypeColumnInOrderBaseServiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mshop_order_base_service', function (Blueprint $table) {
            $table->string('delivery_type')->nullable()->after('final_costs');
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
            $table->dropColumn('delivery_type');
        });
    }
}

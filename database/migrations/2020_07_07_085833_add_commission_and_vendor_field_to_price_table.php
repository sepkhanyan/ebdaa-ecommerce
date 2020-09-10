<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCommissionAndVendorFieldToPriceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mshop_price', function (Blueprint $table) {
            $table->decimal('cost_price', 12, 2)->default(0.00)->after('taxrate');
            $table->decimal('commission', 12, 2)->default(0.00)->after('cost_price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mshop_price', function (Blueprint $table) {
            $table->dropColumn('cost_price');
            $table->dropColumn('commission');
        });
    }
}

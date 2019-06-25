<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSkuAndActiveToProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pars_products', function (Blueprint $table) {
            $table->integer('sku')->default(0);
            $table->boolean('active')->default(false);;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pars_products', function (Blueprint $table) {
            $table->dropColumn('sku');
            $table->dropColumn('active');

        });
    }
}

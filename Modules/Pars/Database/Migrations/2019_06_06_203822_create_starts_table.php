<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pars_starts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('shop_id')->comment('Ссылка на магазин');
            $table->foreign('shop_id')->references('id')->on('pars_shops')->onDelete('cascade');
            $table->unsignedBigInteger('status_id')->comment('Ссылка на статус');
            $table->foreign('status_id')->references('id')->on('pars_statuses');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pars_starts');
    }
}

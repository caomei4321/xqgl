<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCityPartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('city_parts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('things')->comment('物品名');
            $table->string('num')->comment('编号');
            $table->integer('kind_id')->comment('种类');
            $table->text('info')->comment('物品信息');
            $table->unsignedTinyInteger('status')->default(0)->comment('状态');
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
        Schema::dropIfExists('city_parts');
    }
}

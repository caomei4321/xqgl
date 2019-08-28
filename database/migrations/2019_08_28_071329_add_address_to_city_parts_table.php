<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAddressToCityPartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('city_parts', function (Blueprint $table) {
            $table->string('address')->nullable()->comment('地址');
            $table->string('longitude')->nullable()->comment('经度');
            $table->string('latitude')->nullable()->comment('纬度');
            $table->string('image')->nullable()->comment('图片');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('city_parts', function (Blueprint $table) {
            $table->dropColumn('address');
            $table->dropColumn('longitude');
            $table->dropColumn('latitude');
            $table->dropColumn('image');
        });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hats', function (Blueprint $table) {
            $table->increments('id');
            $table->string('device_serial')->comment('设备序列号');
            $table->string('alarm_info')->comment('报警信息');
            $table->string('sum')->comment('识别人数');
            $table->timestamp('alarm_time')->comment('报警时间');
            $table->string('alarm_img_url')->comment('标识好的图片');
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
        Schema::dropIfExists('hats');
    }
}

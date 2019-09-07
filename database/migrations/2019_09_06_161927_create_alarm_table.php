<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlarmTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alarms', function (Blueprint $table) {
            $table->increments('id');
            $table->string('alarm_id')->comment('消息ID');
            $table->string('channel_name')->comment('告警源名称');
            $table->string('alarm_type')->comment('告警类型');
            $table->timestamp('alarm_start')->comment('告警开始时间');
            $table->string('device_serial')->comment('设备序列号');
            $table->string('alarm_pic_url')->comment('告警图片地址');
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
        Schema::dropIfExists('alarm');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAlarmFieldToMattersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('matters', function (Blueprint $table) {
            $table->string('alarm_id')->nullable(true)->comment('消息ID');
            $table->string('channel_name')->nullable(true)->comment('告警源名称');
            $table->string('alarm_type')->nullable(true)->comment('告警类型');
            $table->timestamp('alarm_start')->nullable(true)->comment('告警开始时间');
            $table->string('device_serial')->nullable(true)->comment('设备序列号');
            $table->string('alarm_pic_url')->nullable(true)->comment('告警图片地址');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('matters', function (Blueprint $table) {
            $table->dropColumn('alarm_id');
            $table->dropColumn('channel_name');
            $table->dropColumn('alarm_type');
            $table->dropColumn('alarm_start');
            $table->dropColumn('device_serial');
            $table->dropColumn('alarm_pic_url');
        });
    }
}

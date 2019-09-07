<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlarmUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alarm_users', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('alarm_id')->comment('告警id');
            $table->unsignedInteger('user_id')->comment('用户id');
            $table->unsignedInteger('category_id')->nullable()->comment('分类id');
            $table->string('see_image')->nullable()->comment('现场处理图片');
            $table->text('information')->nullable()->comment('处理信息');
            $table->unsignedTinyInteger('status')->default(0)->comment('处理状态，0=完成处理， 1=完成处理，2=无权处理');
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
        Schema::dropIfExists('alarm_users');
    }
}

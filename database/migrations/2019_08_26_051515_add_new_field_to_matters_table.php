<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewFieldToMattersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('matters', function (Blueprint $table) {
            $table->string('accept_num')->nullable()->comment('受理员编号');
            $table->dateTime('time_limit')->nullable()->comment('办结时限');
            $table->string('work_num')->nullable()->comment('工单编号');
            $table->string('level')->nullable()->comment('紧急程度');
            $table->string('type')->nullable()->comment('来电类别');
            $table->string('source')->nullable()->comment('信息来源');
            $table->unsignedTinyInteger('is_reply')->default('0')->comment('是否回复 0=NO 1=Yes');
            $table->unsignedTinyInteger('is_secret')->default('0')->comment('是否保密 0=No 1=Yes');
            $table->string('contact_name')->nullable()->comment('联系人');
            $table->string('contact_phone')->nullable()->comment('联系电话');
            $table->string('reply_remark')->nullable()->comment('回复备注');
            $table->unsignedBigInteger('category_id')->nullable()->comment('问题分类');
            $table->text('suggestion')->nullable()->comment('转办意见');
            $table->string('approval')->nullable()->comment('领导批示');
            $table->string('result')->nullable()->comment('办理结果');
            $table->unsignedTinyInteger('form')->default('1')->nullable()->comment('类型 1=任务导入 2=巡查员发现问题 3=民众发现问题');
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
            $table->dropColumn('accept_num');
            $table->dropColumn('time_limit');
            $table->dropColumn('work_num');
            $table->dropColumn('level');
            $table->dropColumn('type');
            $table->dropColumn('source');
            $table->dropColumn('is_reply');
            $table->dropColumn('is_secret');
            $table->dropColumn('contact_name');
            $table->dropColumn('contact_phone');
            $table->dropColumn('reply_remark');
            $table->dropColumn('category_id');
            $table->dropColumn('suggestion');
            $table->dropColumn('approval');
            $table->dropColumn('result');
            $table->dropColumn('form');
        });
    }
}

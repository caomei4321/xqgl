<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('category_id')->comment('任务类别');
            $table->integer('author_id')->comment('任务提交人id');
            $table->string('longitude')->comment('经度地址');
            $table->string('latitude')->comment('纬度地址');
            $table->string('address')->comment('任务地址');
            $table->string('title')->index()->comment('任务标题');
            $table->text('show')->comment('任务说明');
            $table->string('bad_img')->comment('任务问题图片');
            $table->integer('executor_id')->comment('任务执行人');
            $table->string('good_img')->comment('任务完成图片');
            $table->text('reply')->comment('任务完成回复');
            $table->text('reason')->comment('无权上报原因');
            $table->unsignedTinyInteger('status')->default('0')->comment('任务状态 0=未完成 1=完成 2=无权上报');
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
        Schema::dropIfExists('tasks');
    }
}

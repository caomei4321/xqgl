<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserHasMattersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_has_matters', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('matter_id')->comment('任务id');
            $table->foreign('matter_id')->references('id')->on('matters')->onDelete('cascade');
            $table->unsignedInteger('user_id')->comment('用户id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedInteger('category_id')->comment('分类id');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->string('see_image')->comment('现场处理图片');
            $table->text('information')->comment('处理信息');
            $table->unsignedTinyInteger('status')->default(0)->comment('处理状态， 0=完成处理，1=无权处理');
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
        Schema::dropIfExists('user_has_matters');
    }
}

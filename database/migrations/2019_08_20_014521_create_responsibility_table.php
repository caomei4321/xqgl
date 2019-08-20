<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResponsibilityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('responsibility', function (Blueprint $table) {
            $table->increments('id');
            $table->string('category_id')->comment('分类id');
            $table->string('item')->comment('具体事项');
            $table->text('county')->comment('县级部门职责');
            $table->text('town')->comment('乡镇街道职责');
            $table->string('legal_doc')->comment('法律法规及文件依据');
            $table->unsignedTinyInteger('subject_duty')->default('0')->comment('主体责任 0=部门 1=镇街');
            $table->unsignedTinyInteger('cooperate_duty')->default('1')->comment('配合责任 0=部门 1=镇街');
            $table->unsignedTinyInteger('status')->default('0')->comment('状态');
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
        Schema::dropIfExists('responsibility');
    }
}

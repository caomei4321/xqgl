<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMattersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matters', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable()->comment('标题');
            $table->string('address')->nullable()->comment('地址');
            $table->text('content')->nullable()->comment('内容');
            $table->string('image')->nullable()->comment('问题图片');
            $table->unsignedTinyInteger('status')->default('0')->comment('现场查看? 0=No 1=Yes');
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
        Schema::dropIfExists('matters');
    }
}

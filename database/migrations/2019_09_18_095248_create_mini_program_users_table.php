<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMiniProgramUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mini_program_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('open_id')->nullable();
            $table->string('weixin_session_key')->nullable();
            $table->unsignedInteger('integral')->default(0)->commont ;
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
        Schema::dropIfExists('mini_program_users');
    }
}

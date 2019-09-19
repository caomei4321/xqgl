<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNickAndAvatarurlToMiniPeogramUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mini_program_users', function (Blueprint $table) {
            $table->string('nickname')->nullable(true);
            $table->string('avatarurl')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mini_program_users', function (Blueprint $table) {
            $table->dropColumn('nickname');
            $table->dropColumn('avatarurl');
        });
    }
}

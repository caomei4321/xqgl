<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAllocateToMattersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('matters', function (Blueprint $table) {
            $table->unsignedTinyInteger('allocate')->default('0')->comment('是否分配');
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
            $table->dropColumn('allocate');
        });
    }
}

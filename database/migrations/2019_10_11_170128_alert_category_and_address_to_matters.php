<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlertCategoryAndAddressToMatters extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('matters', function (Blueprint $table) {
            $table->string('category')->nullable(true)->change();
            $table->string('address')->nullable(true)->change();
            $table->string('title')->nullable(true)->change();
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
            $table->string('category')->nullable(false)->change();
            $table->string('address')->nullable(false)->change();
            $table->string('title')->nullable(false)->change();
        });
    }
}

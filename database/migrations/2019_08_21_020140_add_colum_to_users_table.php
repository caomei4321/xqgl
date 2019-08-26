<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('age')->after('name');
            $table->string('position')->alter('age');
            $table->string('responsible_area')->after('position');
            $table->string('resident_institution')->nullable(true)->after('responsible_area');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('age');
            $table->dropColumn('position');
            $table->dropColumn('responsible_area');
            $table->dropColumn('resident_institution');
        });
    }
}

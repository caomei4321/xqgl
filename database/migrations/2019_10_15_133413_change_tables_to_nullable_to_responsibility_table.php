<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTablesToNullableToResponsibilityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('responsibility', function (Blueprint $table) {
            $table->text('county')->nullable(true)->comment('县级部门职责')->change();
            $table->text('town')->nullable(true)->comment('乡镇街道职责')->change();
            $table->string('legal_doc')->nullable(true)->comment('法律法规及文件依据')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('responsibility', function (Blueprint $table) {
            $table->text('county')->nullable(false)->comment('县级部门职责')->change();
            $table->text('town')->nullable(false)->comment('乡镇街道职责')->change();
            $table->string('legal_doc')->nullable(false)->comment('法律法规及文件依据')->change();
        });
    }
}

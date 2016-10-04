<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTasksignCheckdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tasksigns', function (Blueprint $table) {
            $table->date('checkdate');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tasksigns', function (Blueprint $table) {
            $table->dropColumn('checkdate');
        });
    }
}

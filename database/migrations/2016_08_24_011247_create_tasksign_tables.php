<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksignTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasksignheaders', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('uid');
            $table->date('begindate');
            $table->date('enddate');
            $table->timestamps();
        });
        Schema::create('tasksignheadertasks', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('tasksignheader_id');
            $table->unsignedInteger('task_id');
            $table->timestamps();
        });
        Schema::create('tasksigns', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->unsignedInteger('task_id');
            $table->string('grade');
            $table->text('comment');
            $table->text('reason');
            $table->unsignedInteger('tasksignheader_id');
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
        Schema::drop('tasksigns');
        Schema::drop('tasksignheadertasks');
        Schema::drop('tasksignheaders');
    }
}

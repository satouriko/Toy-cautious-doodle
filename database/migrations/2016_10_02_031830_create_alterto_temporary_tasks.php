<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Task;
use App\Tasksignheader;

class CreateAltertoTemporaryTasks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('families', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('uid');
            $table->string('title');
            $table->text('description');
            $table->text('destination');
            $table->integer('priority');
            $table->timestamps();
        });
        Schema::table('tasks', function (Blueprint $table) {
            $table->unsignedInteger('family_id');
            $table->string('type');
            $table->integer('priority');
        });
        Schema::create('ongoingtasks', function (Blueprint $table) {
            $table->increments('id');
            $table->date('taskdate');
            $table->string('detail');
            $table->unsignedInteger('task_id');
            $table->integer('priority');
            $table->timestamps();
        });
        Schema::table('tasksigns', function (Blueprint $table) {
            $table->date('taskdate');
            $table->string('detail');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('family_id');
            $table->dropColumn("type");
            $table->dropColumn('priority');
        });
        Schema::table('tasksigns', function (Blueprint $table) {
            $table->dropColumn('taskdate');
            $table->dropColumn('detail');
        });
        Schema::drop('ongoingtasks');
        Schema::drop('families');
    }
}

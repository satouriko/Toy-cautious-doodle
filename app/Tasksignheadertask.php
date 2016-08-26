<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tasksignheadertask extends Model
{
    public function task()
    {
        return $this->belongsTo('App\Task');
    }

    public function tasksignheader()
    {
        return $this->belongsTo('App\Tasksignheader');
    }
}

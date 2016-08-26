<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tasksign extends Model
{
    public function task()
    {
        return $this->belongsTo('App\Task');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ongoingtask extends Model
{
    public function task()
    {
        return $this->belongsTo('App\Task');
    }

    protected $fillable = ['task_id', 'taskdate'];
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tasksignheader extends Model
{
    public function tasksignheadertasks()
    {
        return $this->hasMany('App\Tasksignheadertask');
    }

    public function tasksigns()
    {
        return $this->hasMany('App\Tasksign');
    }

    protected $fillable = ['uid', 'begindate'];
}

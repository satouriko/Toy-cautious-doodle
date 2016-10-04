<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    public function family()
    {
        return $this->belongsTo('App\Family');
    }
}

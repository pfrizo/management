<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticable
{
    use Notifiable;

    public function detail(){
        return $this->hasOne(UserDetail::class);
    }

    public function department(){
        return $this->belongsTo(Department::class);
    }
}

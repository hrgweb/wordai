<?php

namespace App;

use App\Word;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function setNameAttribute($name)
    {	
    	$this->attributes['name'] = ucwords($name);
    }

    public function words()
    {
    	return $this->hasMany(Word::class);
    }
}

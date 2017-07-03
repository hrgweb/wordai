<?php

namespace App;

use App\Article;
use App\ProtectedTerm;
use App\UserLevel;
use App\Word;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'firstname', 'lastname', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function setFirstnameAttribute($firstname)
    {	
    	$this->attributes['firstname'] = ucwords($firstname);
    }

    public function setLastnameAttribute($lastname)
    {	
    	$this->attributes['lastname'] = ucwords($lastname);
    }

    public function full_name()
    {
    	return $this->firstname . ' ' . $this->lastname;
    }

    public function words()
    {
    	return $this->hasMany(Word::class);
    }

    public function articles()
    {
    	return $this->hasMany(Article::class);
    }

    public function domains()
    {
    	return $this->hasMany(Domain::class);
    }

    public function terms()
    {
    	return $this->hasMany(ProtectedTerm::class);
    }

    public function user_level()
    {
    	return $this->hasOne(UserLevel::class);
    }
}

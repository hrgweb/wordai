<?php

namespace App;

use App\Article;
use App\ArticleType;
use App\DomainGroup;
use App\ProtectedTerm;
use App\UserLevel;
use App\UserStatus;
use App\Word;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'user_level_id', 'firstname', 'lastname', 'email', 'password', 'status_id', 'has_peditor_access'
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

    public function check_if_admin_or_manager()
    {
    	return ($this->user_level_id === 1 || $this->user_level_id === 2) ? true : false;
    }

    public function access_name()
    {
    	switch ($this->user_level_id) {
    		case 1:
    			echo 'Manager';
    			break;
    		case 2:
    			echo 'Admin';
    			break;
    		case 3:
    			echo 'Writer';
    			break;
    		case 4:
    			echo 'Editor';
    			break;
    		case 5:
    			echo 'Curator';
    			break;
    		case 6:
    			echo 'Proofing';
    			break;
    		case 7:
    			echo 'Poster';
    			break;
    	}
    }

    /*=============== Relationships ===============*/

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

    public function level()
    {
    	return $this->belongsTo(UserLevel::class);
    }

    public function status()
    {
    	return $this->belongsTo(UserStatus::class);
    }

    public function domain_group() {
        return $this->belongsTo(DomainGroup::class);
    }
}

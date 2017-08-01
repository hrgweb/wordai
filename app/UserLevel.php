<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class UserLevel extends Model
{
    protected $fillable = ['user_level'];
    public $timestamps = false;

    public function user()
    {
    	return $this->hasOne(User::class, 'user_level_id');
    }
}

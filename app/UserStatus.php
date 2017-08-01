<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class UserStatus extends Model
{
    protected $fillable = ['status'];

    public function user()
    {
    	return $this->belongsTo(User::class);
    }
}

<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = ['article'];

    public function user()
    {
    	return $this->belongsTo(User::class);
    }
}

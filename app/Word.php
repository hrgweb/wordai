<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Word extends Model
{
    protected $fillable = ['doc_title', 'dom_name', 'spintax'];

    public function writer()
    {
    	return $this->belongsTo(User::class);
    }
}

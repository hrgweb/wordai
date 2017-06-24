<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Word extends Model
{
    protected $fillable = [
    	'doc_title', 
    	'keyword',
    	'lsi_terms',
    	'domain_protected',
    	'article',
    	'dom_name', 
    	'protected',
    	'synonyms'
    ];

    public function writer()
    {
    	return $this->belongsTo(User::class);
    }
}

<?php

namespace App;

use App\ArticleType;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Word extends Model
{
    protected $fillable = [
    	'article_type_id',
    	'doc_title', 
    	'keyword',
    	'lsi_terms',
    	'domain_protected',
    	'article',
    	'dom_name', 
    	'protected',
    	'synonym'
    ];

    public function writer()
    {
    	return $this->belongsTo(User::class);
    }

    public function article_types()
    {
    	return $this->hasMany(ArticleType::class);
    }
}

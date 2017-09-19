<?php

namespace App;

use App\ArticleType;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Word extends Model
{
    protected $fillable = [
    	'article_type_id',
        'domain_id',
    	'group_id',
    	'doc_title',
    	'keyword',
    	'lsi_terms',
    	'domain_protected',
    	'article',
    	'spintax',
    	'spin',
    	'protected',
    	'isUserEdit',
    	'isEditorEdit',
    	'synonym'
    ];

    public function setDocTitleAttribute($title)
    {
    	$this->attributes['doc_title'] = ucwords($title);
    }

    public function setKeywordAttribute($keyword)
    {
    	$this->attributes['keyword'] = ucwords($keyword);
    }

    public function writer()
    {
    	return $this->belongsTo(User::class);
    }

    public function article_types()
    {
    	return $this->hasMany(ArticleType::class);
    }
}

<?php

namespace App;

use App\Word;
use Illuminate\Database\Eloquent\Model;

class ArticleType extends Model
{
    protected $fillable = ['article_type'];

    public function word()
    {
    	return $this->belongsTo(Word::class);
    }
}

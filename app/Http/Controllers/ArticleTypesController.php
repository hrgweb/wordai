<?php

namespace App\Http\Controllers;

use App\ArticleType;
use Illuminate\Http\Request;

class ArticleTypesController extends Controller
{
    public function listOfArticleType()
    {
    	$types = ArticleType::all(['id', 'article_type']);

    	return $types;
    }
}

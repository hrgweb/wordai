<?php

namespace App\Http\Controllers;

use App\ArticleType;
use Illuminate\Http\Request;

class ArticleTypesController extends Controller
{
    public function listOfArticleType()
    {
    	$types = ArticleType::all();

    	return $types;
    }
}

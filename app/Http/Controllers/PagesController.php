<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function index()
    {
    	// return auth()->user();

    	return view('welcome');
    }
}

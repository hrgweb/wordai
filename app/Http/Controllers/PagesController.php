<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
	function __construct() {
        $this->middleware('guest');
	}

    public function index()
    {
    	return view('welcome');
    }
}

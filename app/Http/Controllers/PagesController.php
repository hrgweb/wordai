<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
	function __construct() {
        $this->middleware('guest')->only('index');
        $this->middleware('auth');
	}

    public function index()
    {
    	return view('welcome');
    }

    public function domain()
    {
    	return view('admin.domain');
    }

    public function user()
    {
    	return view('users.index');
    }

    public function home2()
    {
    	return view('words.home');
    }
}

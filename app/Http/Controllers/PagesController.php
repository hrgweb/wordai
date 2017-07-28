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

    public function domain_details()
    {
    	return view('admin.domain-detail');
    }

    public function user()
    {
    	return view('users.index');
    }

    public function home2()
    {
    	return view('words.home');
    }

    public function home3()
    {
    	return view('words.curation-edit');
    }

    public function home4()
    {
    	return view('words.home4');
    }

    public function copyscape()
    {
    	return view('words.copyscape-api-result');
    }

    public function curl()
    {
    	return view('words.curl');
    }
}

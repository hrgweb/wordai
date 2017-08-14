<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
	function __construct() {
        $this->middleware('guest')->only('index');
        $this->middleware('auth')->except('verification');
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

    public function createArticle()
    {
    	return view('words.create-article');
    }

    public function copyscape()
    {
    	return view('words.copyscape-api-result');
    }

    public function curl()
    {
    	return view('words.curl');
    }

    public function verification()
    {
    	return view('auth.verification');
    }

    public function editor()
    {
    	return view('editor.index');
    }

    public function articles()
    {
    	return view('users.articles');
    }

    public function copyscapePage() {
    	return view('copyscape.index');
    }
}

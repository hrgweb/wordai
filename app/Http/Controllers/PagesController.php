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

    public function writer()
    {
    	return view('users.articles');
    }

    public function copyscapePage()
    {
    	return view('copyscape.index');
    }

    public function superEditor()
    {
    	return view('editor.super-editor');
    }

    public function admin() {
        return view('admin.index');
    }

    public function article() {
        return view('admin.article');
    }

    public function domain_group() {
        return view('admin.group');
    }

    public function group() {
        return view('admin.create-group');
    }

    public function editArticle() {
        return view('admin.edit-article');
    }

    public function searchArticle() {
        return view('admin.search-article');
    }
}

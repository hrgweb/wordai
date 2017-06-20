<?php

// Pages
Route::get('/', 'PagesController@index');
Route::get('/domain', 'PagesController@domain');

// WordAI
Route::get('words/generate', 'WordsController@article');
Route::get('words/rawArticles', 'WordsController@getRawArticles');
Route::post('words', 'WordsController@store');
Route::post('words/generateArticle', 'WordsController@generateArticle');
Route::post('words/postSpinTax', 'WordsController@postSpinTax');

// Admin
Route::get('admin/pendingUsers', 'AdminController@pendingUsers');
Route::get('admin/domainList', 'AdminController@domainList');
Route::post('admin/postDomain', 'AdminController@postDomain');
Route::patch('admin/updateDomain', 'AdminController@updateDomain');
Route::delete('admin/removeDomain', 'AdminController@removeDomain');



Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

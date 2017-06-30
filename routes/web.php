<?php

// tmp
Route::get('test', 'WordsController@test');

// Pages
Route::get('/', 'PagesController@index');
Route::get('domain', 'PagesController@domain');
Route::get('user', 'PagesController@user');

// WordAI
Route::get('words/generate', 'WordsController@article');
Route::get('words/rawArticles', 'WordsController@getRawArticles');
Route::post('words', 'WordsController@store');
Route::post('words/generateArticle', 'WordsController@generateArticle');
Route::post('words/generateParagraph', 'WordsController@generateParagraph');
Route::post('words/generateRespintax', 'WordsController@generateRespintax');
Route::post('words/postSpinTax', 'WordsController@postSpinTax');
Route::post('words/processToCopyscape', 'WordsController@processToCopyscape');

// Admin
// Domain
Route::get('admin/pendingUsers', 'AdminController@pendingUsers');
Route::get('admin/domainList', 'AdminController@domainList');
Route::post('admin/postDomain', 'AdminController@postDomain');
Route::patch('admin/updateDomain', 'AdminController@updateDomain');
Route::delete('admin/removeDomain', 'AdminController@removeDomain');
// Protected Terms
Route::post('admin/postProtectedTerms', 'AdminController@postProtectedTerms');

// User
Route::prefix('user')->group(function() {
	Route::get('userList', 'UserController@userList');
	Route::patch('verifySignup', 'UserController@verifySignup');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

<?php

// tmp
Route::get('test', 'WordsController@test');

// Pages
Route::get('/', 'PagesController@index');
Route::get('domain', 'PagesController@domain');
Route::get('user', 'PagesController@user');
Route::get('home2', 'PagesController@home2');
Route::get('home3', 'PagesController@home3');
Route::get('home4', 'PagesController@home4');
Route::get('copyscape', 'PagesController@copyscape');
Route::get('curl', 'PagesController@curl');

// WordAI
Route::prefix('words')->group(function() {
	Route::get('generate', 'WordsController@article');
	Route::get('rawArticles', 'WordsController@getRawArticles');
	Route::post('/', 'WordsController@store');
	Route::post('generateArticle', 'WordsController@generateArticle');
	Route::post('generateParagraph', 'WordsController@generateParagraph');
	Route::post('processCopyscapeApi', 'WordsController@processCopyscapeApi');
	Route::post('generateFullArticle', 'WordsController@generateFullArticle');
	Route::post('generateRespintax', 'WordsController@generateRespintax');
	Route::post('postSpinTax', 'WordsController@postSpinTax');
	Route::post('processToCopyscape', 'WordsController@processToCopyscape');

	Route::post('processTextGrammar', 'WordsController@processTextGrammar');
	// tmp
	Route::post('runCurl', 'WordsController@runCurl');
});

// ArticleType
Route::prefix('articleType')->group(function() {
	Route::get('listOfArticleType', 'ArticleTypesController@listOfArticleType');
});


// Admin
Route::prefix('admin')->group(function() {
	// Domain
	Route::get('pendingUsers', 'AdminController@pendingUsers');
	Route::get('domainList', 'AdminController@domainList');
	Route::post('postDomain', 'AdminController@postDomain');
	Route::patch('updateDomain', 'AdminController@updateDomain');
	Route::delete('removeDomain', 'AdminController@removeDomain');
	// Protected Terms
	Route::post('postProtectedTerms', 'AdminController@postProtectedTerms');
});

// User
Route::prefix('user')->group(function() {
	Route::get('userList', 'UserController@userList');
	Route::patch('verifySignup', 'UserController@verifySignup');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

<?php

// tmp
Route::get('test', 'WordsController@test');

// Pages
Route::get('/', 'PagesController@index');
Route::get('admin', 'PagesController@admin');
Route::get('article', 'PagesController@article');
Route::get('domain', 'PagesController@domain');
Route::get('domain-group', 'PagesController@domain_group');
Route::get('domain-details', 'PagesController@domain_details');
Route::get('user', 'PagesController@user');
Route::get('home2', 'PagesController@home2');
Route::get('home3', 'PagesController@home3');
Route::get('home4', 'PagesController@home4');
Route::get('create-article', 'PagesController@createArticle');
Route::get('copyscape', 'PagesController@copyscape');
Route::get('curl', 'PagesController@curl');
Route::get('verification', 'PagesController@verification');
Route::get('editor', 'PagesController@editor');
Route::get('writer', 'PagesController@writer');
Route::get('copyscape-page', 'PagesController@copyscapePage');
Route::get('super-editor', 'PagesController@superEditor');

// WordAI
Route::prefix('words')->group(function() {
	Route::get('generate', 'WordsController@article');
	Route::get('rawArticles', 'WordsController@getRawArticles');
	Route::get('domainChange', 'WordsController@domainChange');
	Route::get('unprocessArticles', 'WordsController@unprocessArticles');
	Route::post('/', 'WordsController@store');
	Route::post('generateArticle', 'WordsController@generateArticle');
	Route::post('generateParagraph', 'WordsController@generateParagraph');
	Route::post('processCopyscapeApi', 'WordsController@processCopyscapeApi');
	Route::post('generateFullArticle', 'WordsController@generateFullArticle');
	Route::post('generateRespintax', 'WordsController@generateRespintax');
	Route::post('postSpinTax', 'WordsController@postSpinTax');
	Route::post('processToCopyscape', 'WordsController@processToCopyscape');
	Route::post('processTextGrammar', 'WordsController@processTextGrammar');
    Route::post('respinArticle', 'WordsController@respinArticle');
	Route::post('saveAndProcessNow', 'WordsController@saveAndProcessNow');
	Route::patch('updateSpintaxArticle', 'WordsController@updateSpintaxArticle');
    Route::patch('updateCsCheckHitMax', 'WordsController@updateCsCheckHitMax');
    Route::patch('updateRespinCheckHitMax', 'WordsController@updateRespinCheckHitMax');
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
	Route::get('domainList', 'AdminController@domainList');
	Route::get('domainListNotSet', 'AdminController@domainListNotSet');
	Route::get('domainDetails', 'AdminController@domainDetails');
    Route::get('retrieveCopyscapeSetting', 'AdminController@retrieveCopyscapeSetting');
    Route::get('articlesThisWeek', 'AdminController@articlesThisWeek');
    Route::get('articlesCreator', 'AdminController@articlesCreator');
    Route::get('listOfArticlesCreatedByUser', 'AdminController@listOfArticlesCreatedByUser');
    Route::get('articlesDomain', 'AdminController@articlesDomain');
    Route::get('listOfDomainByArticles', 'AdminController@listOfDomainByArticles');
    Route::get('groupList', 'AdminController@groupList');
    Route::get('getUsersAssociatedByDomain', 'AdminController@getUsersAssociatedByDomain');
	Route::post('postDomain', 'AdminController@postDomain');
    Route::post('saveDetails', 'AdminController@saveDetails');
	Route::post('newGroup', 'AdminController@newGroup');
	Route::patch('updateDomain', 'AdminController@updateDomain');
	Route::patch('updateDetails', 'AdminController@updateDetails');
    Route::patch('updateCopyscapeSetting', 'AdminController@updateCopyscapeSetting');
	Route::patch('updateGroup', 'AdminController@updateGroup');
	Route::delete('removeDomain', 'AdminController@removeDomain');
	Route::delete('removeDetails', 'AdminController@removeDetails');
	// Protected Terms
	Route::post('postProtectedTerms', 'AdminController@postProtectedTerms');
});

// User
Route::prefix('user')->group(function() {
	Route::get('pendingUsers', 'UserController@pendingUsers');
	Route::get('userList', 'UserController@userList');
	Route::get('userLevelList', 'UserController@userLevelList');
	Route::get('userArticles', 'UserController@userArticles');
	Route::get('editArticle', 'UserController@editArticle');
    Route::get('userDomainSetup', 'UserController@userDomainSetup');
	Route::patch('verifySignup', 'UserController@verifySignup');
	Route::patch('updateRole', 'UserController@updateRole');
	Route::patch('suspendUser', 'UserController@suspendUser');
	Route::patch('activateUser', 'UserController@activateUser');
	Route::patch('updateArticle', 'UserController@updateArticle');
	Route::patch('updatePeditor', 'UserController@updatePeditor');
	Route::delete('dissmissUser', 'UserController@dissmissUser');
});

// Editor
Route::prefix('editor')->group(function() {
	Route::get('articleList', 'EditorsController@articleList');
	Route::post('publishArticle', 'EditorsController@publishArticle');
	Route::patch('updateArticle', 'EditorsController@updateArticle');
    Route::patch('editorSpentTimeOnEditingArticle', 'EditorsController@editorSpentTimeOnEditingArticle');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

<?php

// Pages
Route::get('/', 'PagesController@index');

// WordAI
Route::get('words/generate', 'WordsController@article');
Route::get('words/rawArticles', 'WordsController@getRawArticles');
Route::post('words', 'WordsController@store');
Route::post('words/generateArticle', 'WordsController@generateArticle');
Route::post('words/postSpinTax', 'WordsController@postSpinTax');



Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

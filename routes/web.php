<?php

// Pages
Route::get('/', 'PagesController@index');

// WordAI
Route::post('words', 'WordsController@store');
Route::post('words/postSpinTax', 'WordsController@postSpinTax');



Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

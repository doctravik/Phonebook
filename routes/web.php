<?php

/**
 * User routes
 */
Route::get('/api/users', 'UserController@index');
Route::post('/api/users', 'UserController@store');
Route::put('/api/users/{user}', 'UserController@update');
Route::delete('/api/users/{user}', 'UserController@destroy');

/**
 * Search route
 */
Route::get('/api/users/search/{name}', 'SearchController@index');

/**
 * Phone routes
 */
Route::post('/api/users/{user}/phones', 'PhoneController@store');
Route::patch('/api/phones/{phone}', 'PhoneController@update');
Route::delete('/api/phones/{phone}', 'PhoneController@destroy');
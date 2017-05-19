<?php

Route::get('/api/users', 'UserController@index');
Route::post('/api/users', 'UserController@store');
Route::put('/api/users/{user}', 'UserController@update');
Route::delete('/api/users/{user}', 'UserController@destroy');
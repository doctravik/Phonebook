<?php

Route::get('/api/users', 'UserController@index');
Route::post('/api/users', 'UserController@store');
<?php

/**
 * Contact routes
 */
Route::get('/api/contacts', 'ContactController@index');
Route::post('/api/contacts', 'ContactController@store');
Route::put('/api/contacts/{contact}', 'ContactController@update');
Route::delete('/api/contacts/{contact}', 'ContactController@destroy');

/**
 * Search route
 */
Route::get('/api/contacts/search/{name}', 'SearchController@index');

/**
 * Phone routes
 */
Route::post('/api/contacts/{contact}/phones', 'PhoneController@store');
Route::patch('/api/phones/{phone}', 'PhoneController@update');
Route::delete('/api/phones/{phone}', 'PhoneController@destroy');
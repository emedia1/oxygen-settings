<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file contains the routes required for Oxygen Settings extension.
|
*/

// web
Route::group(['middleware' => 'web'], function() {
	// Authenticated Users...
	Route::group(['middleware' => 'auth'], function() {
		// Settings
		Route::group(['namespace' => '\EMedia\Settings\Http\Controllers'], function () {
			Route::resource('settings', 'SettingsController')->only(['index', 'edit', 'update']);
	    });
	});
});

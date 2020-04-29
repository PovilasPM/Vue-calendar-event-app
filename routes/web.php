<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Routes for AJAX;
// Get events;
// Create event;
// Delete event;
// Update event;

Route::get('/calendar', function () {
    return view('calendar');
});

Route::get('/retrieve', 'EventsController@retrieve');
Route::post('/create', 'EventsController@create');
Route::post('/remove', 'EventsController@remove');
Route::post('/update', 'EventsController@update');

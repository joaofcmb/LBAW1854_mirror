<?php

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
    return redirect('index');
});

// Authentication
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login')->name('login-action');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register')->name('register-action');

// Home, Search and Static pages
Route::get('index', function () { return view('pages.index'); })->name('index');
Route::get('home', 'HomeController@show')->name('home');
Route::get('search', 'SearchController@show')->name('search');

// Forums
Route::get('companyforum', 'ForumController@showCompanyForum')->name('companyforum');
Route::get('companyforum/thread/{id_thread}', 'ThreadController@show')->name('companyforum-thread');

Route::get('/project/{id}/forum', 'ForumController@show')->name('project-forum');
Route::get('/project/{id_project}/forum/thread/{id_thread}', 'ThreadController@show')->name('forum-thread');

// Profile
Route::get('profile/{id}', 'ProfileController@show')->name('profile');
Route::get('profile/{id}/team', 'ProfileController@showTeam')->name('profile-team');
Route::get('profile/{id}/favorites', 'ProfileController@showFavorites')->name('profile-favorites');
Route::get('profile/{id}/followers', 'ProfileController@showFollowers')->name('profile-followers');
Route::get('profile/{id}/following', 'ProfileController@showFollowing')->name('profile-following');


// Project
Route::get('/project/{id_project}', 'ProjectController@show')->name('project-overview');

// Cards
//Route::get('cards', 'CardController@list');
//Route::get('cards/{id}', 'CardController@show');

// API
//Route::put('api/cards', 'CardController@create');
//Route::delete('api/cards/{card_id}', 'CardController@delete');
//Route::put('api/cards/{card_id}/', 'ItemController@create');
//Route::post('api/item/{id}', 'ItemController@update');
//Route::delete('api/item/{id}', 'ItemController@delete');

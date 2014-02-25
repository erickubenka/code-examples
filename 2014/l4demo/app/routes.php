<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

//Route::group(array('domain' => 'local.dev'), function () {
//    Route::get('/', function () {
//        return 'local.dev';
//    });
//});
//
//Route::group(array('domain' => 'wurst.dev'), function () {
//    Route::get('/', function () {
//        return 'wurst.dev';
//    });
//});
//
//Route::get('/demo', function () {
//    return View::make('demo.home');
//});
//
//Route::post('/demo', function () {
//    return View::make('demo.home')->with('name', Input::get('name'));
//});
//
//Route::get('/ctrlaction', 'HomeController@showWelcome');
//
//Route::controller('democtrl', 'DemoController');
//
//Route::resource('user', 'UserController');
//
//Route::get('login', function () {
//    return View::make('demo.auth.login');
//});
//
//Route::post('login', function () {
//
//    $creds = [
//        'email' => Input::get('email'),
//        'password' => Input::get('password')
//    ];
//
//    if(Auth::attempt($creds)){
//        return Redirect::to('login')->with('success', "You're logged in");
//    }
//
//        return Redirect::to('login')->with('error', "Wrong credentials");
//});
//
//Route::post('logout', function () {
//    Auth::logout();
//    return Redirect::to('login')->with('success', "You're logged out");
//});


Route::get('/', function () {
    return View::make('hello');
});
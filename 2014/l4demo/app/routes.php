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


Route::get('/', function () {
    return View::make('hello');
});

//Route::get('/demo', function () {
//    return View::make('demo.home');
//});
//
//Route::post('/demo', function () {
//    return View::make('demo.home')->with('name', Input::get('name'));
//});

//Route::get('/demo', 'DemoController@getIndex');

//Route::controller('demo', 'DemoController');

Route::resource('user', 'VmUserController');

//
//Route::get('login', function () {
//    return View::make('demo.auth.login');
//});
//
//Route::post('login', function () {
//    $credentials = [
//        'email' => Input::get('email'),
//        'password' => Input::get('password')
//    ];
//
//    if (Auth::attempt($credentials)) {
//        return Redirect::to('login')->with('success', 'Well done. Welcome. It works');
//    }
//
//    return Redirect::to('login')->with('error', 'Something went wrong.');
//});
//
//Route::post('logout', function(){
//    Auth::logout();
//    return Redirect::to('login')->with('success', "You're logged out successfully");
//});

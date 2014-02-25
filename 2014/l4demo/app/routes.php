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

Route::group(array('domain' => 'local.dev'), function ()
{
    Route::get('/', function ()
    {
        return 'local.dev';
    });
});

Route::group(array('domain' => 'wurst.dev'), function ()
{
    Route::get('/', function ()
    {
        return 'wurst.dev';
    });
});

/**
 * Closure Route
 */
Route::get('/demo', function ()
{
    return View::make('demo.home');
});

Route::post('/demo', function(){
   return View::make('demo.home')->with('name', Input::get('name'));
});

/**
 * Controller Action Routing
 */
Route::get('/ctrlaction', 'HomeController@showWelcome');

/**
 * RESTful Controller / Routing
 * Base Uri And Controller
 */
Route::controller('democtrl', 'DemoController');

/**
 * Resource Controller
 */
Route::resource('user', 'UserController');

/**
 * Default Hello Route
 */
Route::get('/', function ()
{
    return View::make('hello');
});



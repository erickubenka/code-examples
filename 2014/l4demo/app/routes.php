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


Route::get('/', function ()
{
    return View::make('hello');
});

Route::get('/secure', function ()
{

    $var = Session::get('key', function ()
    {
        return 'default';
    });
    $var = Session::get('key', 'default');

});

Route::resource('user', 'LvUserController');

//Route::get('/demo', function () {
//    return View::make('demo.home');
//});
//
//Route::post('/demo', function () {
//    return View::make('demo.home')->with('name', Input::get('name'));
//});

//Route::get('/demo', 'DemoController@getIndex');

//Route::controller('demo', 'DemoController');

//
//

Route::group(array('before' => 'auth'), function(){

    Route::get('memberarea', function(){
        return ' Member Area. ';
    });

});

//
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


Route::get('eloquent', function ()
{
//    $viewModel = [];
//
//    // Fetch all users
//    $viewModel['all'] = User::all();
//    $viewModel['all']->each(function ($user)
//    {
//        var_dump($user->name);
//    });
//
//    // Where clause, // Collection each

//    // select * from "users" where "email" like ?'
//    $viewModel['where'] = User::where('email', 'like', 'david%')->get();
//    $viewModel['where']->each(function ($user)
//    {
//        var_dump($user->email);
//    });
//
//
//    // Where And orWhere

//    // select * from "users" where "name" like ? or "name" = ?
//    $viewModel['orwhere'] = User::where('name', 'like', 'david%')->orWhere('name', '=', 'Eric')->get();

//    $viewModel['orwhere']->each(function ($user)
//    {
//        var_dump($user->name);
//    });

    // select * from "users" where "name" in(?, ?, ?)
    //$viewModel['wherein'] = User::whereIn('name', ['Eric', 'David', 'Jan'])->get();

//
//    $viewModel['wherein']->each(function ($user)
//    {
//        var_dump($user->name);
//    });
//

    // select * from "users" order by "id" desc limit 2 offset 3
    //$viewModel['skiptake'] = User::take(2)->skip(3)->orderBy('id', 'desc')->get();

//    $viewModel['skiptake']->each(function($user){
//        var_dump($user->name, $user->id);
//    });
//
//    $nestedWhere =  Album::whereNested(function ($query)
//    {
//        $query->where('year', '>', 2000);
//        $query->where('year', '<', 2005);
//    })->get();
//});

});

Route::get('facade', function(){

    // Identisch zueinander und über Facaden aufgelöst
    Session::get('key');

    $session = new \Symfony\Component\HttpFoundation\Session\Session();
    $session->get('key');

    $rules = array('name' => 'required|min:2');
    $validator = Validator::make(Input::all(), $rules);


    User::where('id', '>', '0')
        ->where('email', 'like', '%.de%')
        ->take(3)
        ->skip(1)
        ->orderBy('name', 'desc')
        ->get();



    // return View::make('hello')->withErrors($validator)->with('data', 'This is a message');
});


Route::group(array('domain' => 'local.dev'), function ()
{
    Route::get('/', function ()
    {
        return 'Index of local.dev ';
    });
});

Route::group(array('domain' => 'example.com'), function ()
{
    Route::get('/', function ()
    {
        return 'Index example.com';
    });
});


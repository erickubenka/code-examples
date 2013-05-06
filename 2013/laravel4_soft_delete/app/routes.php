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

Route::get('/', function()
{
	return View::make('hello');
});

/**
* Create new user
*/
Route::get('/user/add', function(){
	$usr = new User;
	$usr->username = 'Test';
	$usr->deleted_at = null;
	$usr->save();

	echo "user added";
});

/**
* Delete specified user softly
*/
Route::get('/user/delete/{id}', function($id){

	$usr = User::find($id);
	$usr->delete();

	"echo user deleted , softly";
});

/**
* Delete specified user hard
*/
Route::get('/user/forcedelete/{id}', function($id){
	$usr = User::find($id);
	$usr->forceDelete();

	echo "user deleted";
});

/**
* Restore specified user 
*/
Route::get('/user/restore/{id}', function($id){

	User::trashed()->where('id', '=', $id)->first()->restore();
	echo $usr->username.' with id '.$usr->id.' restored';
});


/**
* Display all non-trashed users
*/
Route::get('/user', function(){
	foreach (User::all() as $usr) {
		echo $usr->username.' with id: '.$usr->id.' - deleted_at: '.$usr->deleted_at.'<br>';
	}
});

/**
* Display all users incl. trashed
*/
Route::get('/user/all', function(){
	foreach (User::withTrashed()->where('id', '>', '0')->get() as $usr) {
		echo $usr->username.' with id: '.$usr->id.' - deleted_at: '.$usr->deleted_at.'<br>';
	}
});

/**
* Display trashed users only
*/
Route::get('/user/trashed', function(){

	$users = User::trashed()->where('account_id', 1)->get();
	

});
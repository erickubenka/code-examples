<?php

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

	return "user added";
});

/**
* Delete specified user softly
*/
Route::get('/user/delete/{id}', function($id){

	$usr = User::find($id);
	if(isset($usr))
	{
		$usr->delete();
		return 'User '.$usr->username.' with id '.$usr->id.' deleted, softly';
	}
	return "nothing to delete";
});

/**
* Delete specified user hard
*/
Route::get('/user/forcedelete/{id}', function($id){

	$usr = User::find($id);
	if(isset($usr))
	{
		$usr->forceDelete();
		return 'User '.$usr->username.' with id '.$usr->id.' deleted';
	}
	return "nothing do delete";
});

/**
* Restore specified user 
*/
Route::get('/user/restore/{id}', function($id){


	$usr = User::trashed()->where('id', '=', $id)->first();

	if(isset($usr))
	{
		$usr->restore();
		return $usr->username.' with id '.$usr->id.' restored';
	}
});


/**
* Display all non-trashed users
*/
Route::get('/user', function(){

	$user = User::all();

	if(isset($user))
	{
		foreach ($user as $usr) {
			echo $usr->username.' with id: '.$usr->id.' - deleted_at: '.$usr->deleted_at.'<br>';
		}
		return;
	}
});

/**
* Display all users incl. trashed
*/
Route::get('/user/all', function(){

	$user = User::withTrashed()->get();

	if(isset($user))
	{
		foreach ($user as $usr) {
			echo $usr->username.' with id: '.$usr->id.' - deleted_at: '.$usr->deleted_at.'<br>';
		}
		return;
	}
});

/**
* Display trashed users only
*/
Route::get('/user/trashed', function(){

	$user = User::trashed()->get();

	if(isset($user))
	{
		foreach ($user as $usr) {
			echo $usr->username.' with id: '.$usr->id.' - deleted_at: '.$usr->deleted_at.'<br>';
		}
		return;
	}
});
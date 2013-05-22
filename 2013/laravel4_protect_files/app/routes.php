<?php

/**
 * protect the following routes
 */
Route::group(array('before' => 'auth'), function()
				{
						/**
						 * Logout the current user and redirect
						 */
						Route::get('/logout', function()
										{
												Auth::logout();
												return Redirect::route('home')->with('message', 'See you later');
										});

						/**
						 * notice: enable php_fileinfo extension in php.ini
						 * response with download or redirect and error
						 */
						Route::get('download/{name}', function($name)
										{
												$filePath = storage_path() . '/files/' . $name;

												if (File::exists($filePath))
												{
														return Response::download($filePath);
												}
												else
												{
														return Redirect::route('home')->with('message', "The file you're looking fore doesn't exists");
												}
										});
				});


/**
 * route: home
 */
Route::get('/', array('as' => 'home', function()
		{
				return View::make('hello');
		}));

/**
 * route: validate user data and reddirect
 */
Route::post('/login', function()
				{

						$userData = array(
								'username' => Input::get('username'),
								'password' => Input::get('password')
						);

						if (Auth::attempt($userData))
						{
								return Redirect::route('home')->with('message', 'Login successfull');
						}
						else
						{
								return Redirect::route('home')->with('message', 'Login failed');
						}
				});

/**
 * create a default testuser
 */
Route::get('adduser', function()
				{
						$user = new User();
						$user->username = "test";
						$user->password = Hash::make('test');
						$user->save();

						return View::make('hello');
				});
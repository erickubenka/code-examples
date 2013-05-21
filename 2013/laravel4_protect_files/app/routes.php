<?php

Route::get('/', function()
				{
						return View::make('hello');
				});

Route::get('download/{name}', array(function($name)
		{
				$filePath = storage_path().'/files/'.$name;
				
				if(File::exists($filePath))
				{
						return Response::download($filePath);
				}
				else
				{
						return "The file you're looking for doesn't exist";
				}	
		}));
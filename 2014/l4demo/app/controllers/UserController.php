<?php

class UserController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        return Response::view('demo.user.list', ['users' => User::all()]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        return Response::view('demo.user.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
        $user = new User;
        $input = Input::all();

        $user->name = $input['name'];
        $user->email = $input['email'];
        $user->password = Hash::make('password');

        if($user->save()){
            return Redirect::route('user.edit', ['id' => $user->id]);
        }
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
        $user = User::find($id);
        return Response::view('demo.user.profile', ['user' => $user]);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
        $user = User::find($id);
        return Response::view('demo.user.edit', ['user' => $user]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
        $user = User::find($id);
        $input = Input::all();

        $user->email = $input['email'];
        $user->name = $input['name'];

        if($user->save()){
            return Redirect::route('user.edit', ['id' => $user->id])->with('success', 'Success');
        };
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$user = User::find($id);

        $user->delete();
        return Redirect::route('user.index')->with('success', 'User deleted');
	}

}
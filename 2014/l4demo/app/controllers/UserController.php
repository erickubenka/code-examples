<?php

class UserController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $viewModel = new UserListViewModel();
        $users = $viewModel->load();
        return Response::view('demo.user.list', ['users' => $users]);
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
        $user = new UserModel;

        $user->name = Input::get('name');;
        $user->email = Input::get('email');;
        $user->password = Hash::make('password');

        if($user->save()){
            return Redirect::route('user.edit', ['id' => $user->id])->with('success', 'User successfully created. You can now edit it.');
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
        $viewModel = new UserModel();
        $user = $viewModel->load($id);

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
        $viewModel = new UserModel();
        $user = $viewModel->load($id);
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
        $input = Input::all();

        $user = new UserModel();
        $user->load($id);

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
		$user = new UserModel();
        $user->load($id);
        $user->delete();

        return Redirect::route('user.index')->with('success', 'User deleted');
	}

}
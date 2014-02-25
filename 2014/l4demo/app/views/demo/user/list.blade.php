@extends('demo.master')

@section('content')
<div class="page-header">
    <h2>User List</h2>
    <a href="{{ route('user.create') }}" class="btn btn-success">Create User</a>
</div>

@if (Session::has('success'))
<div class="alert alert-success">
    {{ Session::get('success') }}
</div>
@endif

<table class="table table-bordered table-striped">
    <thead>
    <tr>
        <th>id</th>
        <th>name</th>
        <th>mail</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    @foreach($users as $user)
    <tr>
        <td>{{ $user->id }}</td>
        <td>{{ $user->name }}</td>
        <td>{{ $user->email }}</td>
        <td>
            <form action="{{ route('user.destroy', ['id' => $user->id]) }}" method="post">
                <a href="{{ route('user.show', ['id' => $user->id]) }}" class="btn btn-info">Details</a>
                <a href="{{ route('user.edit', ['id' => $user->id]) }}" class="btn btn-success">Edit</a>
                <input type="hidden" name="_method" value="DELETE"/>
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
        </td>
    </tr>
    @endforeach
    </tbody>
</table>
@stop
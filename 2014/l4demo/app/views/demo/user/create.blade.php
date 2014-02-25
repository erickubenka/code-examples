@extends('demo.master')

@section('content')
<div class="page-header">
    @if(isset($user))
    <h2>Edit User {{{ $user->name }}}</h2>
    @else
    <h2>Create User</h2>
    @endif
</div>

<div class="row">
    <form class="form col-lg-6 col-md-6 col-xs-6 col-sm-6" action="{{ route('user.store') }}" method="post">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" required="" class="form-control" id="name" name="name" value="" />
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" required="" class="form-control" id="email" name="email" value=""/>
        </div>

        <input type="submit" class="btn btn-primary" />
    </form>
</div>

@stop
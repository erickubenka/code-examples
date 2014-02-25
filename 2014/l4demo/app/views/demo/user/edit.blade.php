@extends('demo.master')

@section('content')
<div class="page-header">
    @if(isset($user))
    <h2>Edit User {{{ $user->name }}}</h2>
    @endif
</div>

@if (Session::has('success'))
<div class="alert alert-success">
    {{ Session::get('success') }}
</div>
@endif

<div class="row">
    <form class="form col-lg-6 col-md-6 col-xs-6 col-sm-6" action="{{ route('user.update', ['id' => $user->id]) }}"
          method="post">

        <input type="hidden" name="_method" value="PUT"/>

        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}"/>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="text" class="form-control" id="email" name="email" value="{{ $user->email }}"/>
        </div>

        <input type="submit" class="btn btn-primary"/>
    </form>
</div>

@stop
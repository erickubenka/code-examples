@extends('demo.master')

@section('content')

@if(Session::has('success'))
<div class="alert alert-success">
    {{ Session::get('success') }}
</div>
@endif

@if(Session::has('error'))
<div class="alert alert-danger">
    {{ Session::get('error') }}
</div>
@endif

@unless(Auth::check())
<div class="page-header">
    <h2>Please login</h2>
</div>

<form class="form col-lg-6 col-md-6 col-xs-6 col-sm-6" action="login" method="post">
    <div class="form-group">
        <label for="name">E-mail</label>
        <input type="email" class="form-control" id="email" name="email" />
    </div>

    <div class="form-group">
        <label for="email">Password</label>
        <input type="password" class="form-control" id="password" name="password" />
    </div>

    <input type="submit" class="btn btn-primary" value="Login"/>
</form>
@endunless
@unless(!Auth::check())
<div class="page-header">
    <h2>You're logged in as {{ Auth::user()->name }}</h2>
</div>

<form class="form col-lg-6 col-md-6 col-xs-6 col-sm-6" action="logout" method="post">
    <input type="submit" class="btn btn-primary" value="Logout"/>
</form>
@endunless
@stop
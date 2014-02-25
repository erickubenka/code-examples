@extends('demo.master')

@section('content')

<div class="page-header">
    <h2>Please login</h2>
</div>


<form class="form col-lg-6 col-md-6 col-xs-6 col-sm-6" action="" method="post">
    <div class="form-group">
        <label for="name">E-mail</label>
        <input type="email" class="form-control" id="email" name="email" />
    </div>

    <div class="form-group">
        <label for="email">Password</label>
        <input type="password" class="form-control" id="password" name="password" />
    </div>

    <input type="submit" class="btn btn-primary"/>
</form>
@stop
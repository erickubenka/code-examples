@extends('demo.master')

@section('content')

<div class="page-header">
    <h2>You've arrived.</h2>
</div>
<p>
    Start coding now, but please type your name.
</p>

@if (isset($name))
<div class="alert alert-info" >
    Welcome, {{{ $name }}}
</div>
@endif

<div class="row">
<form class="form col-lg-6 col-md-6 col-xs-6 col-sm-6" action="demo" method="post">

    <div class="form-group">
        <label for="name">Name</label>
        <input type="text" class="form-control" id="name" name="name"/>
    </div>

    <input type="submit" class="btn btn-primary" />
</form>
</div>

@stop
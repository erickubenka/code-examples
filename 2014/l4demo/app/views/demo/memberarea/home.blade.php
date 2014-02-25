@extends('demo.master')

@section('content')

<div class="page-header">
    <h2>You've arrived in your member Area.</h2>
</div>

@if (isset($name))
<div class="alert alert-info" >
    <strong>Welcome, {{{ $name }}}</strong>
</div>
@endif
@stop
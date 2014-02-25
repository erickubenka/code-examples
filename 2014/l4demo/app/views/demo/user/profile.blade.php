@extends('demo.master')

@section('content')
<div class="page-header">

    @if(isset($user))
    <h2>Profile of {{{ $user->name }}}</h2>
    @endif

</div>

<div class="row">
    <form class="form col-lg-6 col-md-6 col-xs-6 col-sm-6">
        <div class="form-group">
            <label for="name">Name</label>
            <p type="text" class="form-control-static" id="name" name="name" >{{ $user->name }}</p>
        </div>

        <div class="form-group">
            <label for="name">Email</label>
            <p type="text" class="form-control-static" id="email" name="email" >{{ $user->email }}</p>
        </div>
    </form>
</div>

@stop
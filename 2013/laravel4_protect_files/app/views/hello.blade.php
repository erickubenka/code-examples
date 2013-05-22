<!DOCTYPE html>
<html>
		<head>
				<title>Safe File Download Example</title>
				<link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
				<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
				<script src="assets/bootstrap/js/bootstrap.min.js"></script>
				<link href="assets/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" media="screen">
		</head>
		<body>
				<p></p>
				<div class="content container-fluid">
						@if(Session::has('message'))
						<div class="alert alert-info ">
								{{ Session::get('message') }}
						</div>
						@endif
						<div class="row-fluid">
								<div class="well span3">
										<h3>User Area</h3>
										@if(!Auth::check())
										{{ Form::open(array('url' => 'login', 'method' => 'post', 'class' => 'form-horizontal')) }}
										{{ Form::label('username', 'username') }}
										{{ Form::text('username', '', array('class' => 'input')) }}
										{{ Form::label('password', 'password') }}
										{{ Form::password('password', array('class' => 'input')) }}
										{{ Form::submit('Sign in', array('name' => 'submitLogin', 'class' => 'btn btn-primary ')) }}
										{{ Form::close() }}
										@else
										<p>Welcome {{ Auth::user()->username }}</p>
										<a href="{{ URL::to('logout') }}" class="btn btn-warning">Logout</a>
										@endif
								</div>
								<div class="well span3">
										<h3>File List</h3>
										<a href="{{ URL::to('download/protect.txt') }}">Download protect.txt</a>
								</div>
						</div>
				</div>
		</body>
</html>
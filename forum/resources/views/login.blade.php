@extends('main_layout')

@section('title')Вход@endsection

@section('content')
<h1>Auth page</h1>
<form method='post' action='/auth/check'>
	@csrf
	<input type='login' name='login' id='login' placeholder="Login"><br>
	<input type='password' name='password' id='password' placeholder="Password"><br>
	<button type='submit'>Login</button>
</form>
@endsection
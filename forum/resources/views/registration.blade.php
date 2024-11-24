@extends('main_layout')

@section('title')Регистрация@endsection

@section('content')
<h1>Registration page</h1>
<form method='post' action='/registration/check'>
	@csrf
	<input type='text' name='name' id='name' placeholder="Name"><br>
	<input type='login' name='login' id='login' placeholder="Login"><br>
	<input type='password' name='password' id='password' placeholder="Password"><br>
	<button type='submit'>Sign up</button>
</form>
@endsection
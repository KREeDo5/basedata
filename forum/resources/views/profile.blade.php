@extends('main_layout')

@section('title')Профиль@endsection

@section('content')
<h1>ME</h1>
<form method='post' action='/change/profile'>
	@csrf
	<input type='text' name='name' id='name' placeholder="name"><br>
	<input type='text' name='description' id='description' placeholder="description"><br>
	<button type='submit'>Change</button>
</form>
<br>
<br>
<br>
<form method='post' action='/change/profile/image'>
	@csrf
	<input type='file' name='image' id='image' placeholder="image" alt="Change image"><br>
	<button type='submit'>Change</button>
</form>
@endsection
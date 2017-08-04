@extends('layouts.app')

@section ('header')
	<style>
		.EditorPage { margin: 3em 0; }
	</style>
@endsection

@section ('content')
	<div class="EditorPage">
		@include('partials._content')

		<editor></editor>
	</div>
@endsection

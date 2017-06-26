@extends('layouts.admin')

@section ('content')
	<user token="{{ csrf_token() }}"></user>
@endsection

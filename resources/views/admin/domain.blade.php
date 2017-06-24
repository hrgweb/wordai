@extends('layouts.admin')

@section ('content')
	<domain token="{{ csrf_token() }}"></domain>
	<protected-term token="{{ csrf_token() }}"></protected-term>
@endsection

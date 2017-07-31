@extends('layouts.admin')

@section ('content')
	<domain token="{{ csrf_token() }}" user="{{ auth()->user() }}"></domain>
@endsection

@extends('layouts.admin')

@section ('content')
	<domain token="{{ csrf_token() }}"></domain>
@endsection

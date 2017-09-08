@extends('layouts.admin')

@section ('content')
	{{-- Domain Detail --}}
	<domain-detail user="{{ $user }}"></domain-detail>

	{{-- Protected term --}}
	{{-- <protected-term token="{{ csrf_token() }}"></protected-term> --}}
@endsection

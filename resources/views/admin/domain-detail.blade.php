@extends('layouts.admin')

@section ('content')
	{{-- Domain Detail --}}
	<domain-detail></domain-detail>

	{{-- Protected term --}}
	{{-- <protected-term token="{{ csrf_token() }}"></protected-term> --}}
@endsection

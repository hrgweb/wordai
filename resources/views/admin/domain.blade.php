@extends('layouts.admin')

@section ('content')
	<domain token="{{ csrf_token() }}"></domain>
	<domain-edit></domain-edit>
@endsection

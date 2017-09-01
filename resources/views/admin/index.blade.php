@extends('layouts.admin')

@section ('content')
    <dashboard token="{{ csrf_token() }}"></dashboard>
@endsection

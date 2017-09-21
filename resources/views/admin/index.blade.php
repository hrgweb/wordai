@extends('layouts.admin')

@section ('content')
    <dashboard user="{{ $user }}" token="{{ csrf_token() }}"></dashboard>
@endsection

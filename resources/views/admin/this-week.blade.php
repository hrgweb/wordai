@extends('layouts.admin')

@section ('content')
    <article-report user="{{ $user }}" token="{{ csrf_token() }}"></article-report>
@endsection

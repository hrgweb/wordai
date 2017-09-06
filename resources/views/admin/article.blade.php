@extends('layouts.admin')

@section ('content')
    <article-main user="{{ $user }}" token="{{ csrf_token() }}"></article-main>
@endsection

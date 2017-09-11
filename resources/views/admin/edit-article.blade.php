@extends('layouts.admin')

@section ('content')
    <edit-article user="{{ $user }}" token="{{ csrf_token() }}"></edit-article>
@endsection

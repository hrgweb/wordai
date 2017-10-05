@extends('layouts.editor')

@section ('content')
    <create-article user="{{ $user }}" token="{{ csrf_token() }}"></create-article>
@endsection

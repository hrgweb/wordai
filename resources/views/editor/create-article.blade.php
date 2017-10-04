@extends('layouts.editor')

@section ('header')
    <style>
        .EditorPage { margin: 3em 0; }
        .Word { padding: 0 7em; }
    </style>
@endsection

@section ('content')
    <div class="EditorPage">
        @include('partials._content')

        <create-article user="{{ $user }}" token="{{ csrf_token() }}"></create-article>
    </div>
@endsection

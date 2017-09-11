@extends('layouts.admin')

@section ('header')
    <style>
        .timer-overlay {
            bottom: .1em !important;
            right: 0;
            left: inherit !important;
        }
    </style>
@endsection

@section ('content')
    <edit-article user="{{ $user }}"></edit-article>
@endsection

@extends('layouts.admin')

@section ('content')
    <div class="ThisWeek">


        <!-- Article Report -->
        <article-report user="{{ $user }}"></article-report>
    </div>
@endsection

@extends('layouts.admin')

@section ('content')
	<div class="row">
		{{ dd($user->user_level()) }}

		@if ($user->isAdmin)
			<pending-user token="{{ csrf_token() }}"></pending-user>
		@else
			<word-api user="{{ $user }}" token="{{ csrf_token() }}"></word-api>
		@endif
	</div>
@endsection
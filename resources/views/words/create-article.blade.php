@extends('layouts.admin')

@section ('content')
	<div class="row">
		@if ($user->check_if_admin_or_manager())
			<pending-user token="{{ csrf_token() }}"></pending-user>
		@else
			<create-article user="{{ $user }}" token="{{ csrf_token() }}"></create-article>
		@endif
	</div>
@endsection

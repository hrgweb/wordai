@extends('layouts.admin')

@section ('content')
	<div class="row">
		@if ($user->check_if_admin_or_manager())
            <dashboard token="{{ csrf_token() }}"></dashboard>
		@else
			<user-article user="{{ $user }}"></user-article>
		@endif
	</div>
@endsection

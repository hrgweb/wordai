@extends('layouts.admin')

@section ('content')
	<div class="row">
		@if ($user->check_if_admin_or_manager())
			<pending-user token="{{ csrf_token() }}"></pending-user>
		@else
			<curation-edit user="{{ $user }}" token="{{ csrf_token() }}"></curation-edit>
		@endif
	</div>
@endsection

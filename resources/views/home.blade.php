@extends('layouts.admin')

@section ('content')
	<div class="row">
		@if ($user->isAdmin)
			<pending-user></pending-user>
		@else
			<word-api user="{{ $user }}" token="{{ csrf_token() }}"></word-api>
		@endif
	</div>
@endsection
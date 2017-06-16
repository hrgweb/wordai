@extends('layouts.admin')

@section ('content')
	<div class="row">
		@unless($user->isAdmin)
			<word-api user="{{ $user }}"></word-api>
		@endunless
	</div>
@endsection
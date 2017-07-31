@extends('layouts.admin')

@section ('content')
	<h2>Domain</h2>

	<div class="row">
		<!-- LSI Terms -->
		<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
			<form method="POST" @submit.prevent="spinTax">
				<label for="lsi_terms">LSI Terms</label>
				<textarea class="form-control" rows="8"></textarea><br>

				<button type="submit" class="btn btn-primary">Spin Now</button>
				&nbsp;&nbsp;&nbsp;
				<span v-if="isLoading">LOADING....</span><br>
			</form>
		</div>

		<!-- Synonyms -->
		<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
			<form method="POST" @submit.prevent="spinTax">
				<label for="synonyms">Synonyms</label>
				<textarea class="form-control" rows="8"></textarea><br>

				<button type="submit" class="btn btn-primary">Spin Now</button>
				&nbsp;&nbsp;&nbsp;
				<span v-if="isLoading">LOADING....</span><br>
			</form>
		</div>
	</div><br>

	{{-- Protected term --}}
	<protected-term token="{{ csrf_token() }}"></protected-term>
@endsection

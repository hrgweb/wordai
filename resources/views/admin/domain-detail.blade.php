@extends('layouts.admin')

@section ('content')
	<h2>Domain</h2>

	<form method="POST" @submit.prevent="spinTax">
		<div class="row">
			<!-- LSI Terms -->
			<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
				<label for="lsi_terms">LSI Terms</label>
				<textarea class="form-control" rows="8"></textarea>
			</div>

			<!-- Synonyms -->
			<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
				<label for="synonyms">Synonyms</label>
				<textarea class="form-control" rows="8"></textarea>
			</div>
		</div><br>

		<button type="submit" class="btn btn-primary">Spin Now</button>
		&nbsp;&nbsp;&nbsp;
		<span v-if="isLoading">LOADING....</span><br>
	</form>
@endsection

@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row row-table page-wrapper">
		<div class="col-lg-3 col-md-6 col-sm-8 col-xs-12 align-middle">
			<!-- START panel-->
			<div data-toggle="play-animation" class="panel panel-dark panel-flat" style="width: 315px;">
				<div class="panel-heading text-center mb-lg">
					<p class="text-center mt-lg">
						<strong>SIGNUP TO GET INSTANT ACCESS.</strong>
					</p>
				</div>
				<div class="panel-body">
              
                <form role="form" method="POST" action="{{ route('register') }}">
                	{{ csrf_field() }}

                	<div class="form-group has-feedback {{ $errors->has('firstname') ? ' has-error' : '' }}">
                		<label for="firstname" class="text-muted">Firstname</label>
            			<input id="firstname" type="text" class="form-control" name="firstname" value="{{ old('firstname') }}" autofocus>

            			@if ($errors->has('firstname'))
	            			<span class="help-block">
	            				<strong>{{ $errors->first('firstname') }}</strong>
	            			</span>
            			@endif
                	</div>

                	<div class="form-group has-feedback {{ $errors->has('lastname') ? ' has-error' : '' }}">
                		<label for="lastname" class="text-muted">lastname</label>
            			<input id="lastname" type="text" class="form-control" name="lastname" value="{{ old('lastname') }}" autofocus>

            			@if ($errors->has('lastname'))
	            			<span class="help-block">
	            				<strong>{{ $errors->first('lastname') }}</strong>
	            			</span>
            			@endif
                	</div>

                	<div class="form-group has-feedback{{ $errors->has('email') ? ' has-error' : '' }}">
                		<label for="email" class="text-muted">E-Mail Address</label>
            			<input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}">

            			@if ($errors->has('email'))
	            			<span class="help-block">
	            				<strong>{{ $errors->first('email') }}</strong>
	            			</span>
            			@endif
                	</div>

                	<div class="form-group has-feedback{{ $errors->has('password') ? ' has-error' : '' }}">
                		<label for="password" class="text-muted">Password</label>
            			<input id="password" type="password" class="form-control" name="password">

            			@if ($errors->has('password'))
	            			<span class="help-block">
	            				<strong>{{ $errors->first('password') }}</strong>
	            			</span>
            			@endif
                	</div>

                	<div class="form-group has-feedback">
                		<label for="password-confirm" class="text-muted">Confirm Password</label>
                		<input id="password-confirm" type="password" class="form-control" name="password_confirmation">
                	</div>

                	
        			<button type="submit" class="btn btn-block btn-primary">Register</button>
                </form>
            </div>
        </div>
        <!-- END panel-->
    </div>
</div>

</div>
@endsection

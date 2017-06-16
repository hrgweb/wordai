@extends('layouts.app')

@section ('content')
	<div class="row row-table page-wrapper">
	    <div class="col-lg-3 col-md-6 col-sm-8 col-xs-12 align-middle">
	        <!-- START panel-->
	        <div class="panel panel-dark panel-flat">
	            <div class="panel-heading text-center">
	                <p class="text-center mt-lg">
	                    <strong>SIGN IN TO CONTINUE.</strong>
	                </p>
	            </div>
	            <div class="panel-body">
	                <form action="{{ route('login') }}" method="POST" role="form" class="mb-lg">
	                	{{ csrf_field() }}

	                    <div class="text-right mb-sm">
	                    	<a href="{{ url('register') }}" class="text-muted">Need to Signup?</a>
	                    </div>

	                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
	                        <input id="email" type="email" placeholder="Enter email" class="form-control" name="email" value="{{ old('email') }}" autofocus>

	                        @if ($errors->has('email'))
	                            <span class="help-block">
	                                <strong>{{ $errors->first('email') }}</strong>
	                            </span>
	                        @endif
	                    </div>

	                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
	                        <input id="password" type="password" placeholder="Password" class="form-control" name="password">

	                        @if ($errors->has('password'))
	                            <span class="help-block">
	                                <strong>{{ $errors->first('password') }}</strong>
	                            </span>
	                        @endif
	                    </div>

	                    <div class="clearfix">
	                        <div class="checkbox c-checkbox pull-left mt0">
	                            <label>
	                                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
	                                <span class="fa fa-check"></span>Remember Me
	                            </label>
	                        </div>
	                        <div class="pull-right">
	                        	{{-- <a href="{{ route('password.request') }}" class="text-muted">Forgot your password?</a> --}}
	                        </div>
	                    </div>

	                    <button type="submit" class="btn btn-block btn-primary" style="margin-top: 1em;">Login</button>
	                </form>
	            </div>
	        </div>
	        <!-- END panel-->
	    </div>
	</div>
@endsection



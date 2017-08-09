<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Connexions Content Suite</title>

    <!-- Styles -->
    <link rel="stylesheet" href="/vendor/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/template.css') }}">
    <link rel="stylesheet" href="{{ asset('css/noty.css') }}">

    <!-- include libraries(jQuery, bootstrap) -->
	{{-- <link href="http://netdna.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.css" rel="stylesheet"> --}}
	<script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.js"></script> 
	<script src="http://netdna.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.js"></script> 

	<!-- include summernote css/js-->
	<link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.4/summernote.css" rel="stylesheet">
	<script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.4/summernote.js"></script>

    @yield('header')
	
    <!-- Scripts -->
    <script>
        window.WordAI = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
</head>
<body>
    <div id="app" class="wrapper">
		@include('partials._nav')

		@if(Auth::check() && $user->check_if_admin_or_manager())
			<!-- START aside-->
			<aside class="aside">
				<!-- START Sidebar (left)-->
				<nav class="sidebar">
					<ul class="nav">
						<li class="nav-heading">Main navigation</li>
						<li>
							<a href="{{ url('home') }}">
								<em class="fa fa-dot-circle-o" aria-hidden="true"></em> Dashboard
							</a>
						</li>
						<li>
							<a href="{{ url('domain') }}">
								<em class="fa fa-link" aria-hidden="true"></em> Domain
							</a>
						</li>
						<li>
							<a href="{{ url('domain-details') }}">
								<em class="fa fa-link" aria-hidden="true"></em> Domain Details
							</a>
						</li>
						<li>
							<a href="{{ url('user') }}">
								<em class="fa fa-user" aria-hidden="true"></em> User
							</a>
						</li>
						{{-- <li><a href="#">Link004</a></li> --}}
						{{-- <li><a href="#">Link005</a></li> --}}
					</ul>
				</nav>

				<!-- END Sidebar (left)-->
			</aside>
			<!-- End aside-->

			@include('partials._content')
		@else
			@include('partials._content')
		@endif
	</div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    @yield('footer')
</body>
</html>
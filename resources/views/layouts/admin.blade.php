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
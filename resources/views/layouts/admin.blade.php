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
		@include('partials._admin-nav')

		@if(Auth::check() && $user->check_if_admin_or_manager())
			@include('partials._admin-sidebar')
			@include('partials._content')
		@else
			@include('partials._content')
		@endif
	</div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        function ulToHide(ul) {
            ul.hide();
        }

        function whenClickMenu(a, ul) {
            a.on('click', function(e) {
                e.preventDefault();

                ul.slideToggle('fast');
            });
        }

        (function() {
        'use strict';
            var ul = $('ul#sub-report, ul#sub-article');

            // hide ul
            ulToHide(ul);

            // click sidebar Report menu
            whenClickMenu($('a#report'), $('ul#sub-report'));
            // whenClickMenu($('a#article'), $('ul#sub-article'));
        })();
    </script>

    @yield('footer')
</body>
</html>
<nav role="navigation" class="navbar navbar-default navbar-top navbar-fixed-top">
    <div class="navbar-header">
        <a class="brand-logo" href="{{ url('/') }}">
        	Connexions Content Suite
        </a>
    </div>

    <div class="nav-wrapper">
        <ul class="nav navbar-nav navbar-right">
            @if (Auth::guest())
                <li><a href="{{ url('login') }}">Login</a></li>
                <li><a href="{{ url('register') }}">Register</a></li>
            @else
                <li><a href="#">{{ $user->username }}</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                        {{ Auth::user()->full_name() }}
                    </a>
                </li>

                {{-- show if logged in as user --}}
                @if (! $user->isAdmin)
                    <li><a href="{{ url('create-article') }}">Create Article</a></li>
                    <li><a href="{{ url('articles') }}">Article List</a></li>
                    {{-- <li><a href="{{ url('words/generate') }}">Generate Article</a></li> --}}
                @endif

                <li>
                    {{-- <a href="{{ url('logout') }}" --}}
                    <a href="#"
                        onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">
                        Logout
                    </a>

                    <form id="logout-form" action="logout" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </li>
            @endif
        </ul>
    </div>
</nav>

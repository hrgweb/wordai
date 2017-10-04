<nav role="navigation" class="navbar navbar-default navbar-top navbar-fixed-top">
    <div class="navbar-header">
        <a class="brand-logo" href="{{ url('/editor') }}">
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
                    <a href="#" role="button" aria-expanded="false">
                        {{ Auth::user()->full_name() }}
                    </a>
                </li>
                <li>
                    <a href="editor/create-article" role="button" aria-expanded="false">
                        Create Article
                    </a>
                </li>
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

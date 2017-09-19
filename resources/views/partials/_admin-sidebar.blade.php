<aside class="aside">
    <nav class="sidebar">
        <ul class="nav">
            <li class="nav-heading">Main navigation</li>
            <li>
                <a href="{{ url('admin') }}">
                    <em class="fa fa-dot-circle-o" aria-hidden="true"></em> Dashboard
                </a>

                {{-- sub for Dashboard --}}
                <ul class="nav sub-nav">
                    <li>
                        <a href="admin#articles_this_week">
                            <em class="fa fa-newspaper-o" aria-hidden="true"></em> This Week
                        </a>
                    </li>
                    <li>
                        <a href="admin#articles_edited_this_week">
                            <em class="fa fa-newspaper-o" aria-hidden="true"></em> Edited This Week
                        </a>
                    </li>
                    <li>
                        <a href="admin#articles_to_edit_this_week">
                            <em class="fa fa-newspaper-o" aria-hidden="true"></em> To Edit This Week
                        </a>
                    </li>
                    <li>
                        <a href="admin#articles_spun_this_week">
                            <em class="fa fa-newspaper-o" aria-hidden="true"></em> Spun This Week
                        </a>
                    </li>
                    <li>
                        <a href="admin#pending_users">
                            <em class="fa fa-user" aria-hidden="true"></em> Pending Users
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="{{ url('article') }}">
                    <em class="fa fa-newspaper-o" aria-hidden="true"></em> Article
                </a>

                {{-- sub for Article --}}
                <ul class="nav sub-nav">
                    <li>
                        <a href="{{ url('article') }}">
                            <em class="fa fa-plus" aria-hidden="true"></em> Create Article
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('edit-article') }}">
                            <em class="fa fa-pencil" aria-hidden="true"></em> Edit Article
                        </a>
                    </li>
                    {{-- <li>
                        <a href="{{ url('search-article') }}">
                            <em class="fa fa-search" aria-hidden="true"></em> Search Article
                        </a>
                    </li> --}}
                </ul>
            </li>
            <li>
                <a href="{{ url('group') }}">
                    <em class="fa fa-group" aria-hidden="true"></em> Group
                </a>
            </li>
            {{-- <li>
                <a href="{{ url('domain-group') }}">
                    <em class="fa fa-group" aria-hidden="true"></em> Domain Group
                </a>
            </li> --}}
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
            <li>
                <a href="{{ url('copyscape-page') }}">
                    <em class="fa fa-cc" aria-hidden="true"></em> Copyscape
                </a>
            </li>
        </ul>
    </nav>
</aside>

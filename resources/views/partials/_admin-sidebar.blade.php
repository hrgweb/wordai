<aside class="aside">
    <nav class="sidebar">
        <ul class="nav">
            <li class="nav-heading">Main navigation</li>
            <li>
                <a href="{{ url('admin') }}">
                    <em class="fa fa-dot-circle-o" aria-hidden="true"></em> Dashboard
                </a>
            </li>
            <li>
                <a href="#" id="report">
                    <em class="fa fa-table" aria-hidden="true"></em> Reports
                </a>

                {{-- sub for Dashboard --}}
                <ul class="nav sub-nav" id="sub-report">
                    <li>
                        <a href="report-search-by">
                            <em class="fa fa-newspaper-o" aria-hidden="true"></em> Search By
                        </a>
                    </li>
                    <li>
                        <a href="report-this-week">
                            <em class="fa fa-newspaper-o" aria-hidden="true"></em> Last 7 Days
                        </a>
                    </li>
                    <li>
                        <a href="report-edited-this-week">
                            <em class="fa fa-newspaper-o" aria-hidden="true"></em> Edited Last 7 Days
                        </a>
                    </li>
                    <li>
                        <a href="report-toedit-this-week">
                            <em class="fa fa-newspaper-o" aria-hidden="true"></em> To Edit Last 7 Days
                        </a>
                    </li>
                    <li>
                        <a href="report-spun-this-week">
                            <em class="fa fa-newspaper-o" aria-hidden="true"></em> Spun Last 7 Days
                        </a>
                    </li>
                    {{-- <li>
                        <a href="admin#pending_users">
                            <em class="fa fa-user" aria-hidden="true"></em> Pending Users
                        </a>
                    </li> --}}
                </ul>
            </li>
            <li>
                <a href="{{ url('article') }}">
                    <em class="fa fa-plus" aria-hidden="true"></em> Create Article
                </a>

                {{-- <a href="#" id="article">
                    <em class="fa fa-newspaper-o" aria-hidden="true"></em> Article
                </a> --}}

                {{-- sub for Article --}}
                {{-- <ul class="nav sub-nav" id="sub-article">
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
                    <li>
                        <a href="{{ url('search-article') }}">
                            <em class="fa fa-search" aria-hidden="true"></em> Search Article
                        </a>
                    </li>
                </ul> --}}
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
            <li>
                <a href="{{ url('new-copyscape') }}">
                    <em class="fa fa-cc" aria-hidden="true"></em> New Copyscape
                </a>
            </li>
        </ul>
    </nav>
</aside>

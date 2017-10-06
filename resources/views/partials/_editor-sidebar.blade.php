<aside class="aside">
    <nav class="sidebar">
        <ul class="nav">
            <li class="nav-heading">Main navigation</li>
            <li>
                <a href="{{ url('editor') }}">
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
                        <a href="/editor/report-search-by">
                            <em class="fa fa-newspaper-o" aria-hidden="true"></em> Search By
                        </a>
                    </li>
                    <li>
                        <a href="/editor/report-this-week">
                            <em class="fa fa-newspaper-o" aria-hidden="true"></em> This Week
                        </a>
                    </li>
                    <li>
                        <a href="/editor/report-edited-this-week">
                            <em class="fa fa-newspaper-o" aria-hidden="true"></em> Edited This Week
                        </a>
                    </li>
                    <li>
                        <a href="/editor/report-toedit-this-week">
                            <em class="fa fa-newspaper-o" aria-hidden="true"></em> To Edit This Week
                        </a>
                    </li>
                    <li>
                        <a href="/editor/report-spun-this-week">
                            <em class="fa fa-newspaper-o" aria-hidden="true"></em> Spun This Week
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="{{ url('editor/create-article') }}">
                    <em class="fa fa-plus" aria-hidden="true"></em> Create Article
                </a>
            </li>
            <li>
                <a href="{{ url('editor/domain-details') }}">
                    <em class="fa fa-link" aria-hidden="true"></em> Domain Details
                </a>
            </li>
            {{-- <li>
                <a href="{{ url('group') }}">
                    <em class="fa fa-group" aria-hidden="true"></em> Group
                </a>
            </li>
            <li>
                <a href="{{ url('domain-group') }}">
                    <em class="fa fa-group" aria-hidden="true"></em> Domain Group
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
            <li>
                <a href="{{ url('copyscape-page') }}">
                    <em class="fa fa-cc" aria-hidden="true"></em> Copyscape
                </a>
            </li> --}}
        </ul>
    </nav>
</aside>

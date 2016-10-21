<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Styles -->
        <link href="/css/all.css" rel="stylesheet">
        <link href="/css/app.css" rel="stylesheet">

        <!-- Scripts -->
        <script>
            window.Laravel = <?php echo json_encode([
                'csrfToken' => csrf_token(),
            ]); ?>
        </script>
    </head>
    <body>
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    @if (Auth::guest())
                        <a class="navbar-brand" href="{{ url('/') }}">
                            {{ config('app.name', 'Laravel') }}
                        </a>
                    @else
                        <a class="navbar-brand" href="{{ url('/home') }}">
                            {{ config('app.name', 'Laravel') }}
                        </a>
                    @endif
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        &nbsp;
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @if (Auth::guest())
                            <li><a href="{{ url('/login') }}">Login</a></li>
                            <li><a href="{{ url('/register') }}">Register</a></li>
                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ Auth::user()->name }} ({{ sprintf('%09d', Auth::user()->ibo_id) }}) <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{ url('/logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>
                                        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                    <li>
                                        <a href="{{ url('/genealogy?sponsor_id=' . Auth::user()->ibo_id . '&placement_id=' . Auth::user()->placement_id) }}">Genealogy</a>
                                        <a href="{{ url('/user/' . Auth::user()->id . '/edit') }}">Profile</a>
                                        <a href="{{ url('/commissionsummaryreport/' . Auth::user()->ibo_id . '?type=weekly') }}">Weekly Summary Commission Report</a>
                                        <a href="{{ url('/commissionsummaryreport/' . Auth::user()->ibo_id . '?type=monthly') }}">Monthly Summary Commission Report</a>
                                    </li>
                                    @if (Auth::user()->role == 'admin')
                                        <li><a href="{{ url('/ibo') }}">IBOs</a></li>
                                        <li><a href="{{ url('/commission') }}">Commissions</a></li>
                                        <li><a href="{{ url('/package') }}">Packages</a></li>
                                        <li><a href="{{ url('/packagetype') }}">Package Types</a></li>
                                        <li><a href="{{ url('/productamount') }}">Product Amounts</a></li>
                                        <li><a href="{{ url('/product') }}">Products</a></li>
                                        <li><a href="{{ url('/productpurchase') }}">Product Purchases</a></li>
                                        <li><a href="{{ url('/rankinglion') }}">Ranking Lions</a></li>
                                        <li><a href="{{ url('/rebate') }}">Rebates</a></li>
                                        <li><a href="{{ url('/activationtype') }}">Activation Types</a></li>
                                        <li><a href="{{ url('/activationcode') }}" target="_blank">Activation Codes</a></li>
                                        <li><a href="{{ url('/commissionsummaryreport/all') }}">Weekly Summary Commission Report (All)</a></li>
                                    @endif
                                </ul>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>

        @yield('content')

        <!-- Scripts -->
        <script src="/js/vendor.js"></script>
        <script src="/js/app.js"></script>
    </body>
</html>

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
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{ url('/logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                    <li>
                                        <a href="{{ url('/ibo') }}">IBO</a>
                                        <a class="genealogy_link" id="{{ Auth::user()->ibo_id }}}" href="{{ url('/genealogy') }}">Genealogy</a>
                                        <a href="{{ url('/user/' . Auth::user()->id . '/edit') }}">Profile</a>
                                    </li>
                                    @if (Auth::user()->role == 'admin')
                                        <li><a href="{{ url('/commission') }}">Commission</a></li>
                                        <li><a href="{{ url('/package') }}">Package</a></li>
                                        <li><a href="{{ url('/packagetype') }}">Package Type</a></li>
                                        <li><a href="{{ url('/productamount') }}">Product Amount</a></li>
                                        <li><a href="{{ url('/product') }}">Product</a></li>
                                        <li><a href="{{ url('/productpurchase') }}">Product Purchase</a></li>
                                        <li><a href="{{ url('/rankinglion') }}">Ranking Lion</a></li>
                                        <li><a href="{{ url('/rebate') }}">Rebate</a></li>
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

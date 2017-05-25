<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ config('app.name', 'Laravel') }}</title>
        <link href="/css/all.css" rel="stylesheet">
        <link href="/css/app.css" rel="stylesheet">
    </head>
    <body>
        <nav class="navbar navbar-default">
            <div class="container">
                <div class="navbar-header">
                    <a class="navbar-brand navbar-link" href="/"> <img src="images/sfi.jpg" width="50"></a>
                    <button class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navcol-1">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
                <div class="collapse navbar-collapse" id="navcol-1">
                    <ul class="nav navbar-nav navbar-right">
                        <li class="active" role="presentation"><a href="{{ url('/aboutus') }}">About Us</a></li>
                        <li role="presentation"><a href="{{ url('/contactus') }}">Contact Us</a></li>
                        <li role="presentation"><a href="{{ url('/login') }}">Login</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container">
            <div id="logo"><img class="img-responsive" src="images/sfi.jpg"></div>
        </div>
        <script src="/js/vendor.js"></script>
        <script src="/js/app.js"></script>
    </body>
</html>
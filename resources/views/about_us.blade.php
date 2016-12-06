<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                /*color: #636b6f;*/
                font-family: 'Raleway';
                /*font-weight: 100;*/
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 70vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                width: 900px;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    <a href="{{ url('/about') }}">About Us</a>
                    <a href="{{ url('/login') }}">Login</a>
                    <a href="{{ url('/register') }}">Register</a>
                </div>
            @endif
            <div class="content">
                <div>
                    <h3>COMPANY FROFILE</h3>
                    Success Formula Marketing (SFM) is a company established through the expertise and passion of the CEO/President Mr. Michael Siega Javier for over a decades.
                    SFM company provides high Quality Filipino Products that individually can market it in A very easy and profitable way. This company also provides powerful marketing plan that every (IBO) Independent Business Owner can enjoy all the monetary commissions using the company system.
                    <br>
                    <br>
                    <br>
                    <h3>OUR VISION</h3>
                    To be One of the Successful Network Marketing in the Philippines, also in Global.
                    <br>
                    <br>
                    <br>
                    <h3>OUR MISSION</h3>
                    To provide high Qualify Products and services to organize profitable and stable business to our leaders. And the bottom line, to produce more Successful Entrepreneurs.
                </div>
            </div>
        </div>
    </body>
</html>

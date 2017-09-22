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
                    <a class="navbar-brand navbar-link" href="/"> <img src="/images/sfi.jpg" width="50"></a>
                    <button class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navcol-1">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
                <div class="collapse navbar-collapse" id="navcol-1">
                    <ul class="nav navbar-nav navbar-right">
                        <li role="presentation"><a href="{{ url('/products') }}">Products</a></li>
                        <li role="presentation"><a href="{{ url('/aboutus') }}">About Us</a></li>
                        <li role="presentation"><a href="{{ url('/contactus') }}">Contact Us</a></li>
                        @if (Auth::guest())
                            <li role="presentation"><a href="{{ url('/login') }}">Login</a></li>
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
                                        @if (Auth::user()->role == 'staff-1')
                                            <a href="{{ url('/genealogy?sponsor_id=' . Auth::user()->ibo_id . '&placement_id=' . Auth::user()->placement_id) }}">Genealogy</a>
                                            <a href="{{ url('/user/' . Auth::user()->ibo_id . '/edit') }}">Profile</a>
                                            <a href="{{ url('/productcode') }}" target="_blank">Product Codes</a>
                                            <a href="{{ url('/activationcode') }}" target="_blank">Activation Codes</a>
                                            <a href="{{ url('/ibosearch') }}" target="_blank">IBO Search</a>
                                            <a href="{{ url('/productpurchase') }}">Product Purchases</a>
                                        @else
                                            <a href="{{ url('/genealogy?sponsor_id=' . Auth::user()->ibo_id . '&placement_id=' . Auth::user()->placement_id) }}">Genealogy</a>
                                            <a href="{{ url('/user/' . Auth::user()->ibo_id . '/edit') }}">Profile</a>
                                            <a href="{{ url('/ibo/create') }}">Register New IBO</a>
                                            <a href="{{ url('/productpurchase/create') }}">Repeat Purchase</a>
                                            <a href="{{ url('/ibocommission/' . Auth::user()->ibo_id) }}">My Weekly Commission Summary Report</a>
                                            <a href="{{ url('/iboindirect/' . Auth::user()->ibo_id) }}">My Indirect Commission</a>
                                            <a href="{{ url('/commissionsummaryreport/' . Auth::user()->ibo_id . '?type=myrebate&month=' . date("n") . '&year=' . date("Y")) }}">My Rebates</a>
                                            <a href="{{ url('/productpurchase/' . Auth::user()->ibo_id) }}">My Purchases</a>
                                        @endif
                                    </li>
                                    @if (Auth::user()->role == 'admin')
                                        <li role="separator" class="divider"></li>
                                        <li class="dropdown-header">Admin</li>
                                        <li>
                                            <a href="{{ url('/commissionsummaryreport/' . Auth::user()->ibo_id . '?type=weekly') }}">Weekly Commission Summary Report (Admin view)</a>
                                            <a href="{{ url('/commissionsummaryreport/all') }}">Weekly Commission Summary Report (All)</a>
                                            <a href="{{ url('/commissionsummaryreport/0?type=monthly') }}">Monthly Commission Summary Report (all Rebates)</a>
                                            <a href="{{ url('/indirectcommission500up') }}">Indirect Commissions (500 up)</a>
                                            <a href="{{ url('/allindirectcommission') }}">Indirect Commissions (All)</a>
                                            <a href="{{ url('/ibo') }}">IBOs</a>
                                            <a href="{{ url('/ibosearch') }}" target="_blank">IBO Search</a>
                                            <a href="{{ url('/commission') }}">Commissions</a>
                                            <a href="{{ url('/package') }}">Packages</a>
                                            <a href="{{ url('/packagetype') }}">Package Types</a>
                                            <a href="{{ url('/productamount') }}">Product Amounts</a>
                                            <a href="{{ url('/product') }}">Products</a>
                                            <a href="{{ url('/productpurchase') }}">Product Purchases</a>
                                            <a href="{{ url('/rankinglion') }}">Ranking Lions</a>
                                            <a href="{{ url('/rebate') }}">Rebates</a>
                                            <a href="{{ url('/activationtype') }}">Activation Types</a>
                                            <a href="{{ url('/activationcode') }}" target="_blank">Activation Codes</a>
                                            <a href="{{ url('/productcode') }}" target="_blank">Product Codes</a>
                                            <a href="{{ url('/bank') }}">Banks</a>
                                            <a href="{{ url('/maritalstatus') }}">Marital Status</a>
                                            <a href="{{ url('/gender') }}">Genders</a>
                                            <a href="{{ url('/country') }}">Countries</a>
                                            <a href="{{ url('/city') }}">Cities</a>
                                            <a href="{{ url('/pickupcenter') }}">Pickup Centers</a>
                                            <a href="{{ url('/flushout') }}">Flushouts</a>
                                            <a href="{{ url('/particular') }}">Particulars</a>
                                            <a href="{{ url('/appagp') }}">APP / AGP</a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container">
            <div id="products-carousel" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators">
                    @foreach ($data['products'] as $key => $product)
                        @if ($key == 0)
                            <li data-target="#products-carousel" data-slide-to="{{ $key }}" class="active"></li>
                        @else
                            <li data-target="#products-carousel" data-slide-to="{{ $key }}"></li>
                        @endif
                    @endforeach
                </ol>
                <div class="carousel-inner">
                    @foreach ($data['products'] as $key => $product)
                        @if ($key == 0)
                            <div class="row carousel item active">
                                <div class="col-md-12" style="max-height: 900px; overflow: hidden;">
                                    <img class="d-block w-100 img-responsive" src="/images/products/{{ $product }}" alt="{{ $product }}" style="max-height: 900px; margin: 0 auto;">
                                </div>
                            </div>
                        @else
                            <div class="row carousel item">
                                <div class="col-md-12" style="max-height: 900px; overflow: hidden;">
                                    <img class="d-block w-100 img-responsive" src="/images/products/{{ $product }}" alt="{{ $product }}" style="max-height: 900px; margin: 0 auto;">
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
                <a class="carousel-control-prev" href="#products-carousel" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#products-carousel" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </div>
        <script src="/js/vendor.js"></script>
        <script src="/js/app.js"></script>
    </body>
</html>
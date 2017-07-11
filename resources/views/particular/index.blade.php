@extends('layouts.app')

@section('content')
<div class="container spark-screen">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading text-capitalize">
                    <div class="row">
                        <div class="col-md-3">Particular</div>
                        <div class="col-md-9">
                            <div class="col-xs-1"></div>
                            <div class="col-xs-4">
                                <input type="text" class="form-control particular-ibo-id" placeholder="IBO ID" value="">
                            </div>
                            <div class="col-xs-3">
                                <input type="text" class="form-control particular-from-date" placeholder="From" value="">
                            </div>
                            <div class="col-xs-3">
                                <input type="text" class="form-control particular-to-date" placeholder="To" value="">
                            </div>
                            <div class="col-xs-1">
                                <div class="row">
                                    <button class="btn btn-primary particular-btn" type="button"><strong>Fetch</strong></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <table class="table table-striped table-hover table-condensed table-bordered particular-table" style="margin-top:50px;">
                        <thead>
                            <tr>
                                <th class="no-border-tl"></th>
                                <th colspan="3">Left</th>
                                <th colspan="3">Right</th>
                                <th colspan="3" class="no-border-tr"></th>
                            </tr>
                            <tr>
                                <th style="text-align:left;">Date</th>
                                <th>OW</th>
                                <th>NW</th>
                                <th>NE</th>
                                <th>NE</th>
                                <th>OW</th>
                                <th>NW</th>
                                <th>Match</th>
                                <th>Fifth</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="text-align:left;">2017-07-31</td>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                                <td style="text-align:right;">0.00</td>
                                <td style="text-align:right;">0.00</td>
                                <td style="text-align:right;">0.00</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

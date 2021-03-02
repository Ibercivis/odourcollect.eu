@extends('layouts.app')

@section('content')

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.4.0/dist/leaflet.css" integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA==" crossorigin=""/>
    <link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v1.0.0/MarkerCluster.Default.css' rel='stylesheet' />

    <style>
        .menu {
            position: absolute;
            z-index: 2;
            right: 25px;
            top: -8px;
        }

        .marker-cluster div {background-color: rgb(0, 177, 135)!important;}

        .marker-cluster {background-color: rgba(0, 177, 135, 0.48)!important;}

        .leaflet-control-attribution{display: none!important;}

        .leaflet-popup{
            bottom: -36px!important;
            left: -43px!important;
        }

    </style>

    <div class="dashboard-wrapper">
        <div class="container-fluid dashboard-content">
            <div class="row" style="padding-top:20px">
            @if (session('status'))
            <div class="card-body border-top">
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>Done!</strong>CSV upload successful
                                <a href="#" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </a>
                            </div>
                        </div>
            @endif
           

                <div class="col-md-12">
                    <div style="padding: 0px 30px 30px 30px">
                    @if(Auth::guard('web')->check())
                    <a href="{{ route('odour.format') }}" class="btn btn-primary">Download Model CSV</a>
                    @else
                    <a href="{{ route('admin.odour.format') }}" class="btn btn-primary">Download Model CSV</a>
                   
                    @endif
                    </div>
                </div>
            </div>
        
            <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="card">
                                <h5 class="card-header">New csv</h5>
                                <div class="card-body">
                                     @if(Auth::guard('web')->check())
                                    {!! Form::open(['route' => 'odour.importcsv', 'method' => 'POST', 'id' => 'createUserForm', 'class' => '', 'enctype' => 'multipart/form-data']) !!}
                                    @else
                                    {!! Form::open(['route' => 'admin.odour.importcsv', 'method' => 'POST', 'id' => 'createUserForm', 'class' => '', 'enctype' => 'multipart/form-data']) !!}
                                    @endif
                                        <div class="form-group">
                                            <label for="new_name" class="col-form-label">Archive</label>
                                            <input id="csv" name="csv" type="file" class="form-control" required>
                                        </div>
                                        
                                        <button type="submit" class="btn btn-primary btn-large">Save</a>
                                    {!! Form::close() !!}
                                </div>
                            </div>
                        </div>
                        </div>
        </div>    
</div>
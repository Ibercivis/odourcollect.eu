@extends('layouts.app')

@section('content')

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.4.0/dist/leaflet.css" integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA==" crossorigin=""/>
    <link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v1.0.0/MarkerCluster.Default.css' rel='stylesheet' />

    <style>
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
            <div class="row">
                @if($errors->any())
                    @if($errors->first() == 'success')
                        <div class="card-body border-top">
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>Done!</strong> It has been verified correctly
                                <a href="#" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </a>
                            </div>
                        </div>
                    @endif
                @endif

                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div id='map' style='width: 100%; height: 500px;'></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="card">
                        <h5 class="card-header">Unverified observations list</h5>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="table" data-page-length='10' class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Type</th>
                                            <th scope="col">Address</th>
                                            <th scope="col">User</th>
                                            <th scope="col">Verified</th>
                                            <th scope="col">Published at</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($odours as $odour)
                                            <tr>
                                                <th scope="row">{{$odour->id}}</th>
                                                <td>{{$odour->odour_type}}</td>
                                                <td>{{$odour->address}}</td>
                                                @if (isset($odour->username))
                                                <td>{{$odour->username}}</td>
                                                @else
                                                <td></td>
                                                @endif
                                                @if ($odour->verified == 1)
                                                    <td>Yes</td>
                                                @else
                                                    <td><a class="btn-primary" href="{{ route('odour.verify', ['id' => $odour->id])}}">Verify</a></td>
                                                @endif
                                                <td>{{$odour->published_at}}</td>
                                                @if(Auth::guard('web')->check())
                                                <td><a class="btn-primary" href="{{ route('odour.show', ['id' => $odour->id])}}">VIEW</a></td>
                                                 @else
                                                 <td><a class="btn-primary" href="{{ route('admin.odour.show', ['id' => $odour->id])}}">VIEW</a></td>
                                                 @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="card">
                        <h5 class="card-header text-center">Observations Types (%)</h5>
                        <div class="card-body">
                            <canvas id="types-chart" width="200" height="200"></canvas>
                        </div>
                        <div class="card-footer bg-white text-center">
                            <h6 id="types-total"></h6>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="card">
                        <h5 class="card-header text-center">Observations in case study (%)</h5>
                        <div class="card-body">
                            <canvas id="zones-chart" width="200" height="200"></canvas>
                        </div>
                        <div class="card-footer bg-white text-center">
                            <h6 id="zones-total"></h6>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-inline-block">
                                <h5 class="text-muted">Registered users</h5>
                                <h5 class="mb-0">{{$count_users}}</h5>
                            </div>
                            <div class="float-right icon-circle-medium  icon-box-lg  bg-info-light mt-1">
                                <i class="fas fa-users fa-fw fa-sm text-info"></i>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="d-inline-block">
                                <h5 class="text-muted">Total logins</h5>
                                <h5 class="mb-0">{{$count_user}}</h5>
                            </div>
                            <div class="float-right icon-circle-medium  icon-box-lg  bg-info-light mt-1">
                                <i class="fa fa-sign-in-alt fa-fw fa-sm text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="card">
                        <h5 class="card-header">Top5 observations</h5>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table no-wrap p-table">
                                    <thead class="bg-light">
                                    <tr class="border-0">
                                        <th class="border-0">#</th>
                                        <th class="border-0">Nº visits</th>
                                        <th class="border-0">Type</th>
                                        <th class="border-0">Origin</th>
                                        <th class="border-0">Intensity</th>
                                        <th class="border-0">Annoy</th>
                                        <th class="border-0">Duration</th>
                                        <th class="border-0"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($top as $t)
                                            <tr>
                                                <td>{{$t->id_target}}</td>
                                                <td>{{$t->count}}</td>
                                                <td><span style="font-style: italic; font-size: 12px;">{{$t->parent_type}}</span> / {{$t->type}}</td>
                                                <td>{{$t->origin}}</td>
                                                <td>{{$t->intensity}}</td>
                                                <td>{{$t->annoy}}</td>
                                                <td>{{$t->duration}}</td>
                                                <td><a class="btn-primary" href="{{ route('odour.show', ['id' => $t->id_target])}}">VIEW</a></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.4.0/dist/leaflet.js" integrity="sha512-QVftwZFqvtRNi0ZyCtsznlKSWOStnDORoefr1enyq5mVL4tmKB3S/EnC3rRJcxCPavG10IcrVGSmPh6Qw5lwrg==" crossorigin=""></script>
    <script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v1.0.0/leaflet.markercluster.js'></script>
    <!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>!-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.js"></script>

    <script>
        var markers = [];
        var markers_layout = '';
        var bounds = [];

        $(document).on('ready', function() {
            $('#table').DataTable({
                ordering: false,
                bInfo : false
            });
        });

        var map = L.map( 'map', {
            center: [41.385063, 2.173404],
            minZoom: 2,
            maxZoom: 18,
            zoom: 10,
            zoomControl:false
        });

        L.tileLayer('https://api.mapbox.com/styles/v1/mapbox/streets-v10/tiles/{z}/{x}/{y}?access_token=pk.eyJ1Ijoic2xpbmdzaG90MDgiLCJhIjoiY2puMnZzZm02MXN0dzNrcGw1em9yZmZjdCJ9.En40T_Cg1OtKJVpulVLxNg').addTo(map)

        $.ajaxSetup({
            headers: { 'X-XSRF-Token': $('meta[name="_token"]').attr('content') }
        });

        $.ajax({
            url: '{{ route('odour.unverified.markers') }}',
            type: "GET",
            beforeSend : function() {},
            success: function(response) {

                markers_layout = L.markerClusterGroup({
                    removeOutsideVisibleBounds: true,
                    disableClusteringAtZoom: 19,
                    maxClusterRadius: 30,
                    showCoverageOnHover: false,
                });

                OdourIcon = L.Icon.extend({
                    options: {
                        iconSize:     [33, 39],
                        iconAnchor:   [16, 40],
                        popupAnchor:  [-3, -76]
                    }
                });
                spot0 = new OdourIcon({iconUrl: '{{ asset('assets/images/point/spot0.png') }}'});
                spot1 = new OdourIcon({iconUrl: '{{ asset('assets/images/point/spot1.png') }}'});
                spot2 = new OdourIcon({iconUrl: '{{ asset('assets/images/point/spot2.png') }}'});
                spot3 = new OdourIcon({iconUrl: '{{ asset('assets/images/point/spot3.png') }}'});
                spot4 = new OdourIcon({iconUrl: '{{ asset('assets/images/point/spot4.png') }}'});
                spot5 = new OdourIcon({iconUrl: '{{ asset('assets/images/point/spot5.png') }}'});
                spot6 = new OdourIcon({iconUrl: '{{ asset('assets/images/point/spot6.png') }}'});
                spot7 = new OdourIcon({iconUrl: '{{ asset('assets/images/point/spot7.png') }}'});
                spot8 = new OdourIcon({iconUrl: '{{ asset('assets/images/point/spot7.png') }}'});
                spot9 = new OdourIcon({iconUrl: '{{ asset('assets/images/point/spot0.png') }}'});

                for($i=0; $i < response.length; $i++){

                    bounds.push([response[$i].lat, response[$i].lng]);

                    if (response[$i].verified === 1) {
                        if(response[$i].id_odor_type === 9){
                            marker = L.marker([response[$i].lat, response[$i].lng], {icon: spot9}).addTo(markers_layout).bindPopup('<a class="btn-primary" href="{{ route('home')}}' + '/odour/' + response[$i].id + '">View</a>');
                                    marker.id = response[$i].id;
                        } else {
                        switch (response[$i].color) {
                            case 1:
                                marker = L.marker([response[$i].lat, response[$i].lng], {icon: spot1}).addTo(markers_layout).bindPopup('<a class="btn-primary" href="{{ route('home')}}' + '/odour/' + response[$i].id + '">View</a>');
                                marker.id = response[$i].id;
                                break;
                            case 2:
                                marker = L.marker([response[$i].lat, response[$i].lng], {icon: spot2}).addTo(markers_layout).bindPopup('<a class="btn-primary" href="{{ route('home')}}' + '/odour/' + response[$i].id + '">View</a>');
                                marker.id = response[$i].id;
                                break;
                            case 3:
                                marker = L.marker([response[$i].lat, response[$i].lng], {icon: spot3}).addTo(markers_layout).bindPopup('<a class="btn-primary" href="{{ route('home')}}' + '/odour/' + response[$i].id + '">View</a>');
                                marker.id = response[$i].id;
                                break;
                            case 4:
                                marker = L.marker([response[$i].lat, response[$i].lng], {icon: spot4}).addTo(markers_layout).bindPopup('<a class="btn-primary" href="{{ route('home')}}' + '/odour/' + response[$i].id + '">View</a>');
                                marker.id = response[$i].id;
                                break;
                            case 5:
                                marker = L.marker([response[$i].lat, response[$i].lng], {icon: spot5}).addTo(markers_layout).bindPopup('<a class="btn-primary" href="{{ route('home')}}' + '/odour/' + response[$i].id + '">View</a>');
                                marker.id = response[$i].id;
                                break;
                            case 6:
                                marker = L.marker([response[$i].lat, response[$i].lng], {icon: spot6}).addTo(markers_layout).bindPopup('<a class="btn-primary" href="{{ route('home')}}' + '/odour/' + response[$i].id + '">View</a>');
                                marker.id = response[$i].id;
                                break;
                            case 7:
                                marker = L.marker([response[$i].lat, response[$i].lng], {icon: spot7}).addTo(markers_layout).bindPopup('<a class="btn-primary" href="{{ route('home')}}' + '/odour/' + response[$i].id + '">View</a>');
                                marker.id = response[$i].id;
                                break;
                            case 8:
                                marker = L.marker([response[$i].lat, response[$i].lng], {icon: spot8}).addTo(markers_layout).bindPopup('<a class="btn-primary" href="{{ route('home')}}' + '/odour/' + response[$i].id + '">View</a>');
                                marker.id = response[$i].id;
                                break;
                            case 9:
                                marker = L.marker([response[$i].lat, response[$i].lng], {icon: spot9}).addTo(markers_layout).bindPopup('<a class="btn-primary" href="{{ route('home')}}' + '/odour/' + response[$i].id + '">View</a>');
                                marker.id = response[$i].id;
                                break;
                        }
                    }
                    } else {
                        marker = L.marker([response[$i].lat, response[$i].lng], {icon: spot0}).addTo(markers_layout).bindPopup('<a style="margin-left:3px" class="btn-primary" href="{{ route('home')}}' + '/odour/' + response[$i].id + '">View</a><p style="margin-top: 24px;"><a class="btn-primary" href="{{ route('home')}}' + '/odour/' + response[$i].id +'/verify">Verify</a></p>');
                        marker.id = response[$i].id;
                    }
                }
                var b = new L.LatLngBounds(bounds);
                map.fitBounds(b);
                map.addLayer(markers_layout);
            },
            error: function (data, textStatus, errorThrown) {
                console.log(data);
            },
        });

        $.ajax({
            url: '{{ route('odour.zone.stats') }}',
            type: "GET",
            beforeSend : function() {},
            success: function(response) {
                var types_labels = [];
                var types_values = [];

                console.log(response);

                for ($i = 0; $i < response.zones.length; $i++){
                    if (response.zones[$i].count !== undefined){
                        types_labels.push(response.zones[$i].name +  ' (' + response.zones[$i].count + ')');
                        response.zones[$i].count =  response.zones[$i].count*100/response.total_odors_z;
                    } else {
                        response.zones[$i].count = 0;
                        types_labels.push(response.zones[$i].name +  ' (' + response.zones[$i].count + ')');
                    }
                    types_values.push(response.zones[$i].count);
                }

                var ctxZones = document.getElementById("zones-chart").getContext('2d');

                zones = {
                    datasets: [{
                        data: types_values,
                        backgroundColor: [
                            'rgba(88,181,199, 1)',
                            'rgba(40,123,139, 1)',
                            'rgba(135,220,236, 1)',
                            'rgba(88,128,136, 1)',
                            'rgba(3,206,245, 1)',
                            'rgba(9,48,56, 1)',
                        ]
                    }],
                    labels: types_labels
                };
                var zonesChart = new Chart(ctxZones,{
                    type: 'doughnut',
                    data: zones,
                    options:{
                        responsive: true,
                        tooltips: {
                            mode: 'index',
                            callbacks: {
                                // Use the footer callback to display the sum of the items showing in the tooltip
                                footer: function(tooltipItems, data) {
                                    var sum = 0;

                                    tooltipItems.forEach(function(tooltipItem) {
                                        sum += data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                                    });
                                    return Math.round(sum * 100) / 100 + '%';
                                },
                                label: function(tooltipItems, data) {
                                    return '';
                                }
                            },
                            footerFontStyle: 'normal'
                        },
                        hover: {
                            mode: 'index',
                            intersect: true
                        },
                    }
                });

                $('#zones-total').text('TOTAL: ' + response.total_odors_z);

                types_labels = [];
                types_values = [];

                for ($i = 0; $i < response.types.length; $i++){
                    if (response.types[$i].count !== undefined){
                        types_labels.push(response.types[$i].name +  ' (' + response.types[$i].count + ')');
                        response.types[$i].count =  response.types[$i].count*100/response.total_odors;
                    } else {
                        response.types[$i].count = 0;
                        types_labels.push(response.types[$i].name +  ' (' + response.types[$i].count + ')');
                    }
                    types_values.push(response.types[$i].count);
                }

                var ctxTypes = document.getElementById("types-chart").getContext('2d');

                types1 = {
                    datasets: [{
                        data: types_values,
                        backgroundColor: [
                            'rgba(88,181,199, 1)',
                            'rgba(40,123,139, 1)',
                            'rgba(135,220,236, 1)',
                            'rgba(88,128,136, 1)',
                            'rgba(3,206,245, 1)',
                            'rgba(9,48,56, 1)',
                        ]
                    }],
                    labels: types_labels
                };
                var typesChart = new Chart(ctxTypes,{
                    type: 'doughnut',
                    data: types1,
                    options:{
                        responsive: true,
                        tooltips: {
                            mode: 'index',
                            callbacks: {
                                // Use the footer callback to display the sum of the items showing in the tooltip
                                footer: function(tooltipItems, data) {
                                    var sum = 0;

                                    tooltipItems.forEach(function(tooltipItem) {
                                        sum += data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                                    });
                                    return Math.round(sum * 100) / 100 + '%';
                                },
                                label: function(tooltipItems, data) {
                                    return '';
                                }
                            },
                            footerFontStyle: 'normal'
                        },
                        hover: {
                            mode: 'index',
                            intersect: true
                        },
                    }
                });

                $('#types-total').text('TOTAL: ' + response.total_odors);

            },
            error: function (data, textStatus, errorThrown) {
                console.log(data);
            },
        });

    </script>

@endsection

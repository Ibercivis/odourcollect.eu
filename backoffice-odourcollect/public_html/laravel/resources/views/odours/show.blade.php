@extends('layouts.app')

@section('content')

    <script src='https://api.mapbox.com/mapbox-gl-js/v0.49.0/mapbox-gl.js'></script>
    <link href='https://api.mapbox.com/mapbox-gl-js/v0.49.0/mapbox-gl.css' rel='stylesheet' />

    <style>
        .menu{
            position: absolute;
            z-index: 2;
            right: 56px;
            top: -8px;
        }

        .marker {
            background-size: cover;
            width: 30px;
            height: 35px;
            border-radius: 50%;
            cursor: pointer;
        }

        .track {
            background-color: rgba(0, 0, 0, 0.5);
            width: 7px;
            height: 7px;
            border-radius: 50%;
            cursor: pointer;
        }

        .chart-index{
            text-align: center;
            font-size: 52px;
            position: absolute;
            top: calc(50% - 42px);
            left: calc(50% - 21px);
            font-weight: 800;
        }

        .leaflet-control-attribution{display: none!important;}

        .marker0 {background-image: url({{ asset('assets/images/point/spot0.png') }});}

        .marker1 {background-image: url({{ asset('assets/images/point/spot1.png') }});}

        .marker2 {background-image: url({{ asset('assets/images/point/spot2.png') }});}

        .marker3 {background-image: url({{ asset('assets/images/point/spot3.png') }});}

        .marker4 {background-image: url({{ asset('assets/images/point/spot4.png') }});}

        .marker5 {background-image: url({{ asset('assets/images/point/spot5.png') }});}

        .marker6 {background-image: url({{ asset('assets/images/point/spot6.png') }});}

        .marker7 {background-image: url({{ asset('assets/images/point/spot7.png') }});}

        .marker8 {background-image: url({{ asset('assets/images/point/spot7.png') }});}

        .marker9 {background-image: url({{ asset('assets/images/point/spot0.png') }});}
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

                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="page-header">
                                <div class="page-breadcrumb">
                                    <nav aria-label="breadcrumb">
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item"><a href="{{route('odour.list')}}" class="breadcrumb-link">Observation list</a></li>
                                            <li class="breadcrumb-item active" aria-current="page">Observation: #{{$odour->id}}</li>
                                        </ol>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="card influencer-profile-data">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="user-avatar-info">
                                        <div class="user-avatar-address">
                                            <p><span class="d-xl-inline-block d-block"><i class="fa fa-user mr-2 text-primary"></i>

                                                @if(Auth::guard('admin')->check())
                                 
                                                <a href="{{ url('admin/user', ['id' => $odour->user->id])}}">{{$odour->user->username}}</a>
                                                @else
                                                 {{$odour->user->username}}
                                                @endif
                                            </span></p>
                                            <p><span class="d-xl-inline-block d-block"><i class="fa fa-map-marker-alt mr-2 text-primary"></i>{{$odour->location->address}}</span></p>
                                            <p><span class="d-xl-inline-block d-block"><i class="fa fa-clock mr-2 text-primary"></i>{{$odour->published_at}}</span></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="user-avatar-info">
                                        <div class="user-avatar-address">
                                            <p><span class="d-xl-inline-block d-block"><i class="fas fa-bookmark mr-2 text-primary"></i>{{$odour->type}} / {{$odour->subtype}}</span></p>

                                            @if ($odour->origin)
                                                <p><span class="d-xl-inline-block d-block"><i class="fa fa-dot-circle mr-2 text-primary"></i>{{$odour->origin}}</span></p>
                                            @endif

                                            @if ($odour->status == 'deleted')
                                                <p><span class="d-xl-inline-block d-block"><i class="fa fa-trash-alt mr-2 text-primary"></i>Deleted by the user</span></p>
                                            @endif

                                            <div class="d-xl-inline-block d-block">
                                                <i class="fa fa-eye mr-2 text-primary"></i>Visible:
                                                <div class="switch-button switch-button-success">
                                                    {!! Form::open(['route' => 'odour.update.status', 'method' => 'POST', 'id' => 'updateOdourStatus', 'class' => '']) !!}
                                                        <input type="hidden" id="odour_id" name="odour_id" value="{{$odour->id}}">
                                                        <input type="checkbox"  @if ($odour->status == 'published'){{'checked'}}@endif name="status" id="switch"><span><label for="switch"></label></span>
                                                    {!! Form::close() !!}
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="menu">
                                        <form id="maps_radio">
                                            <div class="dropdown">
                                                <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Track</button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <a class="dropdown-item">
                                                        <label class="custom-control custom-radio custom-control">
                                                            <input type="radio" name="radio-map" class="custom-control-input" value="1"><span class="custom-control-label">On</span>
                                                        </label>
                                                    </a>
                                                    <a class="dropdown-item">
                                                        <label class="custom-control custom-radio custom-control">
                                                            <input type="radio" name="radio-map" checked class="custom-control-input" value="0"><span class="custom-control-label">Off</span>
                                                        </label>
                                                    </a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div id='map' style='width: 100%; height: 500px; z-index: 1'></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <input type="hidden" id="odour_id" name="odour_id" value="{{$odour->id}}">

                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-inline-block">
                                <h5 class="text-muted">Confirmations</h5>
                                <h2 class="mb-0"> {{sizeof($odour->confirmations)}}</h2>
                            </div>
                            <div class="float-right icon-circle-medium  icon-box-lg  bg-info-light mt-1">
                                <i class="fa fa-check-circle fa-fw fa-sm text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-inline-block">
                                <h5 class="text-muted">Views</h5>
                                <h2 class="mb-0"> {{$odour->stats}}</h2>
                            </div>
                            <div class="float-right icon-circle-medium  icon-box-lg  bg-info-light mt-1">
                                <i class="fa fa-eye fa-fw fa-sm text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-body">
                            <a href="{{route('odour.comments', ['id' => $odour->id])}}">
                                <div class="d-inline-block">
                                    <h5 class="text-muted">Comments</h5>
                                    <h2 class="mb-0"> {{$odour->count_comments}}</h2>
                                </div>
                                <div class="float-right icon-circle-medium  icon-box-lg  bg-info-light mt-1">
                                    <i class="fas fa-comment-alt fa-fw fa-sm text-info"></i>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-inline-block">
                                <h5 class="text-muted">Duration</h5>
                                <h5 class="mb-0"> {{$odour->duration}}</h5>
                            </div>
                            <div class="float-right icon-circle-medium  icon-box-lg  bg-info-light mt-1">
                                <i class="fa fa-clock fa-fw fa-sm text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="card">
                        <h5 class="card-header text-center">Observation Intensity</h5>
                        <div class="card-body">
                            <canvas id="intensity-chart" width="200" height="200"></canvas>
                            <p class="chart-index">{{$odour->index_intensity}}</p>
                        </div>
                        <div class="card-footer bg-white text-center">
                            <h6>{{$odour->intensity}}</h6>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="card">
                        <h5 class="card-header text-center">Observation Nice / foul</h5>
                        <div class="card-body">
                            <canvas id="annoy-chart" width="200" height="200"></canvas>
                            <p class="chart-index">{{$odour->index_annoy}}</p>
                        </div>
                        <div class="card-footer bg-white text-center">
                            <h6>{{$odour->annoy}}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.bundle.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.min.js"></script>

    <script>
        var markers = [];
        var track = [];

        $(document).on('ready', function() {
            $('#switch').change(function () {
                $("#updateOdourStatus").submit();
            });

            $('#maps_radio').change(function(){

                if ($("input[name='radio-map']:checked").val() == 1){
                    var elements = document.getElementsByClassName('track hide');

                    while(elements.length > 0){
                        elements[0].classList.remove('hide');
                    }
                } else {
                    var elements = document.getElementsByClassName('track');
                    for (i = 0; i < elements.length; i++){
                        elements[i].classList.add('hide');
                    }
                }
            });
        });

        mapboxgl.accessToken = 'pk.eyJ1Ijoic2xpbmdzaG90MDgiLCJhIjoiY2puMnZzZm02MXN0dzNrcGw1em9yZmZjdCJ9.En40T_Cg1OtKJVpulVLxNg';
        /* eslint-disable */
        var map = new mapboxgl.Map({
            container: 'map', // container id
            style: 'mapbox://styles/mapbox/streets-v10', //hosted style id
            attributionControl: false,
            center: [2.173404, 41.385063], // starting position
            zoom: 10 // starting zoom
        });

        $.ajaxSetup({
            headers: { 'X-XSRF-Token': $('meta[name="_token"]').attr('content')}
        });

        $.ajax({
            url: '{{ route('odour.track', ['id' => $odour->id]) }}',
            type: "GET",
            beforeSend : function() {},
            success: function(response) {

                response.forEach(function(t){
                    var el = document.createElement('div');
                    var id = t.id;

                    el.className = 'track hide';
                    track.push(new mapboxgl.Marker(el)
                        .setLngLat([t.longitude, t.latitude])
                        .addTo(map));
                });

            },
            error: function (data, textStatus, errorThrown) {
                console.log(data);
            },
        });

        $.ajax({
            url: '{{ route('odour.markers', ['id' => $odour->id]) }}',
            type: "GET",
            beforeSend : function() {},
            success: function(response) {

                var el = document.createElement('div');
                var id = response[0].id;

                if (response[0].verified === 1) {
                   console.log(response[0]);
                   if(response[0].id_odor_type == 9){
                    
                    el.className = 'marker9';
                    el.className += ' marker';
                   } else {
                    el.className = 'marker' + response[0].color;
                    el.className += ' marker';
                   }
                    
                } else {
                    el.className = 'marker0';
                    el.className += ' marker';
                    var popup = new mapboxgl.Popup({ offset: 25 })
                        .setHTML('<a class="btn-primary" href="{{ route('home')}}' + '/odour/' + response[0].id +'/verify">Verify</a>');
                }

                markers.push(new mapboxgl.Marker(el)
                    .setLngLat([response[0].lng ,response[0].lat])
                    .setPopup(popup)
                    .addTo(map));

                map.flyTo({ center: [response[0].lng ,response[0].lat],zoom: 8 });
            },
            error: function (data, textStatus, errorThrown) {
                console.log(data);
            },
        });

        $.ajaxSetup({
            headers: { 'X-XSRF-Token': $('meta[name="_token"]').attr('content') }
        });

        $.ajax({
            url: '{{ route('odour.spec', ['id' => $odour->id]) }}',
            type: "GET",
            beforeSend : function() {},
            success: function(response) {

                //Instensity chart
                var value_int = (response.id_odor_intensity - 1)*100/6;
                var value_annoy = (response.id_odor_annoy - 1)*100/8;

                var ctxIntensity = document.getElementById("intensity-chart").getContext('2d');

                intensity = {
                    datasets: [{
                        data: [value_int, 100 - value_int],
                        backgroundColor: [
                            'rgba(88,181,199, 1)',
                            'rgba(181,183,187, 1)',
                        ]
                    }],
                    labels: ['', '',]
                };
                var intensityChart = new Chart(ctxIntensity,{
                    type: 'doughnut',
                    data: intensity,
                    options:{
                        responsive: true,
                        tooltips: {
                            enabled: false,
                            callbacks: {
                                label: function(tooltipItems, data) {
                                    return '';
                                }
                            },
                        },
                        legend: {
                            display: false,
                        }
                    }
                });

                //Annoy chart
                var ctxAnnoy = document.getElementById("annoy-chart").getContext('2d');
                annoy = {
                    datasets: [{
                        data: [value_annoy, 100 - value_annoy],
                        backgroundColor: [
                            'rgba(140,134,211, 1)',
                            'rgba(181,183,187, 1)',
                        ]
                    }],
                    labels: ['', '',]
                };
                var annoyChart = new Chart(ctxAnnoy,{
                    type: 'doughnut',
                    data: annoy,
                    options:{
                        responsive: true,
                        tooltips: {
                            enabled: false,
                            callbacks: {
                                label: function(tooltipItems, data) {
                                    return '';
                                }
                            },
                        },
                        legend: {
                            display: false,
                        }
                    }
                });

            },
            error: function (data, textStatus, errorThrown) {
                console.log(data);
            },
        });

    </script>

@endsection

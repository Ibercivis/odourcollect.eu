@extends('layouts.app')

@section('content')

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.4.0/dist/leaflet.css" integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA==" crossorigin=""/>

    <style>
        .leaflet-control-attribution{display: none!important;}
    </style>

    <div class="dashboard-wrapper">
        <div class="container-fluid dashboard-content">
            <div class="row">
                @if($errors->any())
                    @if($errors->first() == 'success')
                        <div class="card-body border-top">
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>Done!</strong> It has been removed correctly
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
                        <h5 class="card-header">Case study list (Active)</h5>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="table" data-page-length='10' class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Place</th>
                                            <th scope="col">Region</th>
                                            <th scope="col">Country</th>
                                            <th scope="col"> </th>
                                            <th scope="col"> </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($zones as $zone)
                                            <tr>
                                                <th scope="row">{{$zone->id}}</th>
                                                <td>{{$zone->name}}</td>
                                                <td>{{$zone->place}}</td>
                                                <td>{{$zone->region}}</td>
                                                <td>{{$zone->country}}</td>
                                                @if(Auth::guard('web')->check())
                                                <td><a class="btn-primary" href="{{ route('zone.show', ['id' => $zone->id])}}">MANAGE</a></td>
                                                @else
                                                <td><a class="btn-primary" href="{{ url('admin/zone', ['id' => $zone->id])}}">MANAGE</a></td>
                          
                                                @endif
                                                
                                                <td><a id="myBtn-{{$zone->id}}" data-url="{{ route('zone.delete', ['id' => $zone->id])}}" class="modal__trigger btn-danger">DELETE</a></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="card">
                        <h5 class="card-header">Case study list (No Active)</h5>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="table" data-page-length='10' class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Place</th>
                                            <th scope="col">Region</th>
                                            <th scope="col">Country</th>
                                            @if(Auth::guard('admin')->check())
                                            <th scope="col"> </th>
                                            @endif
                                            <th scope="col"> </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($zones_no_active as $zone)
                                            <tr>
                                                <th scope="row">{{$zone->id}}</th>
                                                <td>{{$zone->name}}</td>
                                                <td>{{$zone->place}}</td>
                                                <td>{{$zone->region}}</td>
                                                <td>{{$zone->country}}</td>
                                                 @if(Auth::guard('admin')->check())
                                                <td><a class="btn-primary" href="{{ route('admin.zone.active', ['id' => $zone->id])}}">ACTIVE</a></td>
                                                @endif
                                                @if(Auth::guard('web')->check())
                                                <td><a id="myBtn-{{$zone->id}}" data-url="{{ route('zone.delete', ['id' => $zone->id])}}" class="modal__trigger btn-danger">DELETE</a></td>
                                                @else
                                                <td><a id="myBtn-{{$zone->id}}" data-url="{{ route('admin.zone.delete', ['id' => $zone->id])}}" class="modal__trigger btn-danger">DELETE</a></td>
                                                @endif
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

    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close close_action">&times;</span>
            <p class="modal_title">¿Are you sure to delete it?</p>
            <div style="display: flex">
                <a id="accept" href="" class="btn-danger modal_button">YES</a>
                <a class="btn-cancel close_action modal_button">CANCEL</a>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.4.0/dist/leaflet.js" integrity="sha512-QVftwZFqvtRNi0ZyCtsznlKSWOStnDORoefr1enyq5mVL4tmKB3S/EnC3rRJcxCPavG10IcrVGSmPh6Qw5lwrg==" crossorigin=""></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

    <script>

        $(document).on('ready', function() {
            $('#table').DataTable({
                ordering: false,
                bInfo : false
            });

            var modal = document.getElementById('myModal');
            $('[id^="myBtn"]').on('click', function() {
                modal.style.display = "block";
                url = $(this).data('url');

                $('#accept').attr("href",url);
            });

            $('.close_action').on('click', function() {
                modal.style.display = "none";
            });

            window.onclick = function(event) {
                if (event.target == modal) {modal.style.display = "none";}
            }
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
            url: '{{ route('zone.points', ['id' => 0]) }}',
            type: "GET",
            beforeSend : function() {},
            success: function(response) {
                var bounds = [];

                for (i = 0; i < response.length; i++){
                    var geojson = [];

                    for (e = 0; e < response[i].points.length; e++){
                        geojson.push([ parseFloat(response[i].points[e].latitude), parseFloat(response[i].points[e].longitude) ]);
                    }

                    bounds.push([ parseFloat(response[i].latitude), parseFloat(response[i].longitude) ]);

                    var polygon = L.polygon(geojson).addTo(map);
                    map.fitBounds(bounds, {padding: [50,50]});

                    if (response[i].id !== 0){
                        polygon.bindPopup('<a class="btn-primary" href="{{ route('home')}}' + '/zone/' + response[i].id + '">Manage</a>');
                    }
                }
            }
        });
    </script>

@endsection

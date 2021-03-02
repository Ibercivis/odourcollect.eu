@extends('layouts.app')

@section('content')

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.4.0/dist/leaflet.css" integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA==" crossorigin=""/>
    <link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v1.0.0/MarkerCluster.Default.css' rel='stylesheet' />

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
                        <h5 class="card-header">Activities of interest list</h5>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="table" data-page-length='10' class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Type</th>
                                            <th scope="col">Address</th>
                                            <th scope="col">Place</th>
                                            <th scope="col">Country</th>
                                            <th scope="col"></th>
                                            <th scope="col"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($points as $point)
                                            <tr>
                                                <th scope="row">{{$point->id}}</th>
                                                <td>{{$point->name}}</td>
                                                <td>{{$point->odour_type}}</td>
                                                <td>{{$point->address}}</td>
                                                <td>{{$point->place}}</td>
                                                <td>{{$point->country}}</td>
                                                @if(Auth::guard('web')->check())
                                                <td><a class="btn-primary" href="{{ route('point.show', ['id' => $point->id])}}">MANAGE</a></td>
                                                @else
                                                <td><a class="btn-primary" href="{{ url('admin/point', ['id' => $point->id])}}">MANAGE</a></td>
                                                @endif
                                                
                                                <td><a id="myBtn-{{$point->id}}" data-url="{{ route('point.delete', ['id' => $point->id])}}" class="modal__trigger btn-danger">DELETE</a></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
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
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.4.0/dist/leaflet.js" integrity="sha512-QVftwZFqvtRNi0ZyCtsznlKSWOStnDORoefr1enyq5mVL4tmKB3S/EnC3rRJcxCPavG10IcrVGSmPh6Qw5lwrg==" crossorigin=""></script>
    <script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v1.0.0/leaflet.markercluster.js'></script>

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
            url: '{{ route('point.points', ['id' => 0] ) }}',
            type: "GET",
            beforeSend : function() {},
            success: function(response) {
                markers_layout = L.markerClusterGroup({
                    removeOutsideVisibleBounds: true,
                    disableClusteringAtZoom: 19,
                    maxClusterRadius: 30,
                    showCoverageOnHover: false,
                });

                InterestIcon = L.Icon.extend({
                    options: {
                        iconSize:     [33, 39],
                        iconAnchor:   [16, 40],
                        popupAnchor:  [0, -46]
                    }
                });

                spot2 = new InterestIcon({iconUrl: '{{ asset('assets/images/interest/agriculture-spot.png') }}'});
                spot3 = new InterestIcon({iconUrl: '{{ asset('assets/images/interest/food-spot.png') }}'});
                spot4 = new InterestIcon({iconUrl: '{{ asset('assets/images/interest/industry-spot.png') }}'});
                spot5 = new InterestIcon({iconUrl: '{{ asset('assets/images/interest/unknown-spot.png') }}'});
                spot6 = new InterestIcon({iconUrl: '{{ asset('assets/images/interest/urban-spot.png') }}'});
                spot7 = new InterestIcon({iconUrl: '{{ asset('assets/images/interest/waste-spot.png') }}'});
                spot8 = new InterestIcon({iconUrl: '{{ asset('assets/images/interest/water-spot.png') }}'});

                for($i=0; $i < response.length; $i++){

                    switch (response[$i].id_point_of_interest_type) {
                        case 2:
                            marker = L.marker([response[$i].latitude, response[$i].longitude], {icon: spot2}).addTo(markers_layout).bindPopup('<a class="btn-primary" href="{{ route('home')}}' + '/point/' + response[$i].id + '">Manage</a>');
                            marker.id = response[$i].id;
                            break;
                        case 3:
                            marker = L.marker([response[$i].latitude, response[$i].longitude], {icon: spot3}).addTo(markers_layout).bindPopup('<a class="btn-primary" href="{{ route('home')}}' + '/point/' + response[$i].id + '">Manage</a>');
                            marker.id = response[$i].id;
                            break;
                        case 4:
                            marker = L.marker([response[$i].latitude, response[$i].longitude], {icon: spot4}).addTo(markers_layout).bindPopup('<a class="btn-primary" href="{{ route('home')}}' + '/point/' + response[$i].id + '">Manage</a>');
                            marker.id = response[$i].id;
                            break;
                        case 5:
                            marker = L.marker([response[$i].latitude, response[$i].longitude], {icon: spot5}).addTo(markers_layout).bindPopup('<a class="btn-primary" href="{{ route('home')}}' + '/point/' + response[$i].id + '">Manage</a>');
                            marker.id = response[$i].id;
                            break;
                        case 6:
                            marker = L.marker([response[$i].latitude, response[$i].longitude], {icon: spot6}).addTo(markers_layout).bindPopup('<a class="btn-primary" href="{{ route('home')}}' + '/point/' + response[$i].id + '">Manage</a>');
                            marker.id = response[$i].id;
                            break;
                        case 7:
                            marker = L.marker([response[$i].latitude, response[$i].longitude], {icon: spot7}).addTo(markers_layout).bindPopup('<a class="btn-primary" href="{{ route('home')}}' + '/point/' + response[$i].id + '">Manage</a>');
                            marker.id = response[$i].id;
                            break;
                        case 8:
                            marker = L.marker([response[$i].latitude, response[$i].longitude], {icon: spot8}).addTo(markers_layout).bindPopup('<a class="btn-primary" href="{{ route('home')}}' + '/point/' + response[$i].id + '">Manage</a>');
                            marker.id = response[$i].id;
                            break;
                        }

                }
                map.addLayer(markers_layout);
            }
        });
    </script>

@endsection

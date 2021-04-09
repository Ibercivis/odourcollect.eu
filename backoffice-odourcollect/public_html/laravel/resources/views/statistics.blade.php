@extends('layouts.app')

@section('content')

    <div class="dashboard-wrapper">
        <div class="container-fluid dashboard-content">
            <div class="row">
                <div class="row no_margin selects">
                    <form id="statistics_form" class="'navbar-form navbar-left" method="get">
                        <div class="form-group">
                            {!! Form::select('map', [null => 'Case study'] + $maps->pluck('name', 'id')->all(), null, ['class' => 'form-control', 'id' => 'map']) !!}
                        </div>

                        <div class="form-group">
                            <label for="program_type_select">Published dates (dd/mm/aaaa)</label>
                            <span class="between-dates">From  </span>{!! Form::input('date', 'publish_date_src', "", ['class' => 'form-control', 'id' => 'publish_date_src_select', 'data-date-format' => "yyyy-mm-dd"]) !!}
                            <span class="between-dates">To  </span>{!! Form::input('date', 'publish_date_dst', "", ['class' => 'form-control', 'id' => 'publish_date_dst_select', 'data-date-format' => "yyyy-mm-dd"]) !!}
                        </div>
                    </form>
                </div>

                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="card">
                        <h5 class="card-header text-center">Observations Types(%)</h5>
                        <div class="card-body" id="types-chart-content">
                            <canvas id="types-chart" width="200" height="200"></canvas>
                        </div>
                        <div class="card-footer bg-white text-center">
                            <h6 id="types-total"></h6>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="card">
                        <h5 class="card-header text-center">Observations Subtypes(%)</h5>
                        <div class="card-body" id="subtypes-chart-content">
                            <canvas id="subtypes-chart" width="200" height="200"></canvas>
                        </div>
                        <div class="card-footer bg-white text-center">
                            <h6 id="subtypes-total"></h6>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="card">
                        <h5 class="card-header text-center">Observations Intensities(%)</h5>
                        <div class="card-body" id="intensity-chart-content">
                            <canvas id="intensity-chart" width="200" height="200"></canvas>
                        </div>
                        <div class="card-footer bg-white text-center">
                            <h6 id="intensity-total"></h6>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="card">
                        <h5 class="card-header text-center">Observations Nice/foul (%)</h5>
                        <div class="card-body" id="annoy-chart-content">
                            <canvas id="annoy-chart" width="200" height="200"></canvas>
                        </div>
                        <div class="card-footer bg-white text-center">
                            <h6 id="annoy-total"></h6>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="card">
                        <h5 class="card-header text-center">Commented observations</h5>
                        <div class="card-body" id="comment-chart-content">
                            <canvas id="comment-chart" width="200" height="200"></canvas>
                        </div>
                        <div class="card-footer bg-white text-center">
                            <h6 id="comment-total"></h6>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="card">
                        <h5 class="card-header text-center">Validated observations</h5>
                        <div class="card-body" id="valid-chart-content">
                            <canvas id="valid-chart" width="200" height="200"></canvas>
                        </div>
                        <div class="card-footer bg-white text-center">
                            <h6 id="valid-total"></h6>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.js"></script>

    <script>
    var total;
        $(document).on('ready', function() {
            $('#map').on('change', function(){
                map = $('#map').val();
                src = $('#publish_date_src_select').val();
                dst = $('#publish_date_dst_select').val();
                update(map, src, dst);
            });

            $('#publish_date_src_select').on('change', function(){
                map = $('#map').val();
                src = $('#publish_date_src_select').val();
                dst = $('#publish_date_dst_select').val();
                update(map, src, dst);
            });

            $('#publish_date_dst_select').on('change', function(){
                map = $('#map').val();
                src = $('#publish_date_src_select').val();
                dst = $('#publish_date_dst_select').val();
                update(map, src, dst);
            });
        });

        total = $.ajax({
            url: '{{ route('home') }}' + '/odourStatistics/?map=&publish_date_src=&publish_date_dst=',
            type: "GET",
            beforeSend : function() {},
            success: function(response) {
                console.log("llamada general");
                console.log(response.subtypes);
                //Observations Types Chart
                var types_labels = [];
                var types_values = [];
                var subtypes_labels = [];
                var subtypes_values = [];
                var ctxTypes = document.getElementById("subtypes-chart").getContext('2d');
                
                for ($i = 0; $i < response.subtypes.length; $i++){
                    if (response.subtypes[$i].count !== undefined){
                        subtypes_labels.push(response.subtypes[$i].name +  ' (' + response.subtypes[$i].count + ')');
                        response.subtypes[$i].count =  response.subtypes[$i].count*100/response.total_odors;
                    } else {
                        response.subtypes[$i].count = 0;
                        subtypes_labels.push(response.subtypes[$i].name +  ' (' + response.subtypes[$i].count + ')');
                    }
                    subtypes_values.push(response.subtypes[$i].count);
                }
                var subtypes_labels = subtypes_labels.slice(0,10);
                console.log(subtypes_labels);
                t = {
                    datasets: [{
                        data: subtypes_values,
                        backgroundColor: [
                            'rgba(227,161,66)',
                            'rgba(164,225,224)',
                            'rgba(180,227,124)',
                            'rgba(224,116,107)',
                            'rgba(249,169,169)',
                            'rgba(95,85,87)',
                            'rgba(9,5,61)',
                            'rgba(28,23,90)',
                            'rgba(22,12,148)',
                            'rgba(66,52,236)',
                            'rgba(82,73,196)',
                            'rgba(105,100,170)',
                            'rgba(103,100,131)',
                            'rgba(172,170,206)',
                            'rgba(198,194,255)',
                            'rgba(227,161,66)',
                            'rgba(164,225,224)',
                            'rgba(180,227,124)',
                            'rgba(224,116,107)',
                            'rgba(249,169,169)',
                            'rgba(95,85,87)',
                            'rgba(132,224,241)',
                            'rgba(81,220,247)',
                            'rgba(88,181,199)',
                            'rgba(43,147,167)',
                            'rgba(52,96,104)',
                            'rgba(23,91,104)',
                            'rgba(5,54,63)',
                            'rgba(98,167,148)',
                            'rgba(209,241,233)',
                        ]
                    }],
                    labels: subtypes_labels
                };
                var typesChart = new Chart(ctxTypes,{
                    type: 'doughnut',
                    data: t,
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
                
                $('#subtypes-total').text('TOTAL: ' + response.total_odors);

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
                
                t = {
                    datasets: [{
                        data: types_values,
                        backgroundColor: [
                            'rgba(227,161,66)',
                            'rgba(164,225,224)',
                            'rgba(180,227,124)',
                            'rgba(224,116,107)',
                            'rgba(249,169,169)',
                            'rgba(95,85,87)',
                        ]
                    }],
                    labels: types_labels
                };
                var typesChart = new Chart(ctxTypes,{
                    type: 'doughnut',
                    data: t,
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

                

                //Observations Intensities Chart
                var intensity_labels = [];
                var intensity_values = [];

                for ($i = 0; $i < response.intensity.length; $i++){
                    if (response.intensity[$i].count !== undefined){
                        intensity_labels.push(response.intensity[$i].name +  ' (' + response.intensity[$i].count + ')');
                        response.intensity[$i].count =  response.intensity[$i].count*100/response.total_odors;
                    } else {
                        response.intensity[$i].count = 0;
                        intensity_labels.push(response.intensity[$i].name +  ' (' + response.intensity[$i].count + ')');
                    }
                    intensity_values.push(response.intensity[$i].count);
                }

                var ctxTypes = document.getElementById("intensity-chart").getContext('2d');

                intensities = {
                    datasets: [{
                        data: intensity_values,
                        backgroundColor: [
                            'rgba(132,224,241)',
                            'rgba(81,220,247)',
                            'rgba(88,181,199)',
                            'rgba(43,147,167)',
                            'rgba(52,96,104)',
                            'rgba(23,91,104)',
                            'rgba(5,54,63)',
                        ]
                    }],
                    labels: intensity_labels
                };
                var intensityChart = new Chart(ctxTypes,{
                    type: 'doughnut',
                    data: intensities,
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

                $('#intensity-total').text('TOTAL: ' + response.total_odors);


                //Observations nice/foul Chart
                var annoy_labels = [];
                var annoy_values = [];

                for ($i = 0; $i < response.annoy.length; $i++){
                    if (response.annoy[$i].count !== undefined){
                        annoy_labels.push(response.annoy[$i].name +  ' (' + response.annoy[$i].count + ')');
                        response.annoy[$i].count =  response.annoy[$i].count*100/response.total_odors;
                    } else {
                        response.annoy[$i].count = 0;
                        annoy_labels.push(response.annoy[$i].name +  ' (' + response.annoy[$i].count + ')');
                    }
                    annoy_values.push(response.annoy[$i].count);
                }

                var ctxTypes = document.getElementById("annoy-chart").getContext('2d');

                annoy = {
                    datasets: [{
                        data: annoy_values,
                        backgroundColor: [
                            'rgba(9,5,61)',
                            'rgba(28,23,90)',
                            'rgba(22,12,148)',
                            'rgba(66,52,236)',
                            'rgba(82,73,196)',
                            'rgba(105,100,170)',
                            'rgba(103,100,131)',
                            'rgba(172,170,206)',
                            'rgba(198,194,255)',
                        ]
                    }],
                    labels: annoy_labels
                };
                var annoyChart = new Chart(ctxTypes,{
                    type: 'doughnut',
                    data: annoy,
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

                $('#annoy-total').text('TOTAL: ' + response.total_odors);


                //Commented obervations Chart
                var comment_labels = ['Commented', 'Not commented' ];
                var comment_values = [];

                comment_values.push(response.commented);

                var ctxTypes = document.getElementById("comment-chart").getContext('2d');

                comment = {
                    datasets: [{
                        data: [comment_values, response.total_odors - comment_values],
                        backgroundColor: [
                            'rgba(98,167,148)',
                            'rgba(209,241,233)',
                        ]
                    }],
                    labels: comment_labels
                };
                var commentChart = new Chart(ctxTypes,{
                    type: 'doughnut',
                    data: comment,
                });

                $('#comment-total').text('TOTAL: ' + response.total_odors);


                //Validated obervations Chart
                var valid_labels = ['Validated', 'Not validated' ];
                var valid_values = [];

                valid_values.push(response.verified);

                var ctxTypes = document.getElementById("valid-chart").getContext('2d');

                valid = {
                    datasets: [{
                        data: [valid_values, response.total_odors - valid_values],
                        backgroundColor: [
                            'rgba(98,167,148)',
                            'rgba(209,241,233)',
                        ]
                    }],
                    labels: valid_labels
                };
                var validtChart = new Chart(ctxTypes,{
                    type: 'doughnut',
                    data: valid,
                });

                $('#valid-total').text('TOTAL: ' + response.total_odors);

            },
            error: function (data, textStatus, errorThrown) {
                console.log(data);
            },
        });
    
        function update(map, src, dst){
            console.log("update.....");
            if(total != null){total.abort(); total = null;}
            $.ajax({
                url: '{{ url('admin') }}' + '/odourStatistics/?map=' + map + '&publish_date_src='  + src + '&publish_date_dst=' + dst,
                type: "GET",
                beforeSend: function () {},
                success: function (response) {

                    //Observations Types Chart
                    var types_labels = [];
                    var types_values = [];

                    var subtypes_labels = [];
                     var subtypes_values = [];
               
                document.getElementById("subtypes-chart-content").innerHTML = '&nbsp;';
                document.getElementById("subtypes-chart-content").innerHTML = '<canvas id="subtypes-chart" width="200" height="200"></canvas>';
                var ctxTypes = document.getElementById("subtypes-chart").getContext("2d");

                for ($i = 0; $i < 10; $i++){
                    if (response.subtypes[$i].count !== undefined){
                        
                        subtypes_labels.push(response.subtypes[$i].name +  ' (' + response.subtypes[$i].count + ')');
                        response.subtypes[$i].count =  response.subtypes[$i].count*100/response.total_odors;
                    } else {
                        response.subtypes[$i].count = 0;
                        subtypes_labels.push(response.subtypes[$i].name +  ' (' + response.subtypes[$i].count + ')');
                    }
                    subtypes_values.push(response.subtypes[$i].count);
                }
               
                t = {
                    datasets: [{
                        data: subtypes_values,
                        backgroundColor: [
                            'rgba(227,161,66)',
                            'rgba(164,225,224)',
                            'rgba(180,227,124)',
                            'rgba(224,116,107)',
                            'rgba(249,169,169)',
                            'rgba(95,85,87)',
                        ]
                    }],
                    labels: subtypes_labels
                };
                
                var typesChart = new Chart(ctxTypes,{
                    type: 'doughnut',
                    data: t,
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

                $('#subtypes-total').text('TOTAL: ' + response.total_odors);
                    
                    for ($i = 0; $i < response.types.length; $i++) {
                        if (response.types[$i].count !== undefined) {
                            types_labels.push(response.types[$i].name + ' (' + response.types[$i].count + ')');
                            response.types[$i].count = response.types[$i].count * 100 / response.total_odors;
                        } else {
                            response.types[$i].count = 0;
                            types_labels.push(response.types[$i].name + ' (' + response.types[$i].count + ')');
                        }
                        types_values.push(response.types[$i].count);
                    }

  
                document.getElementById("types-chart-content").innerHTML = '&nbsp;';
                document.getElementById("types-chart-content").innerHTML = '<canvas id="types-chart" width="200" height="200"></canvas>';
                var ctxTypes = document.getElementById("types-chart").getContext("2d");
                    t = {
                        datasets: [{
                            data: types_values,
                            backgroundColor: [
                                'rgba(227,161,66)',
                                'rgba(164,225,224)',
                                'rgba(180,227,124)',
                                'rgba(224,116,107)',
                                'rgba(249,169,169)',
                                'rgba(95,85,87)',
                            ]
                        }],
                        labels: types_labels
                    };
                    var typesChart = new Chart(ctxTypes, {
                        type: 'doughnut',
                        data: t,
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


                    //Observations Intensities Chart
                    var intensity_labels = [];
                    var intensity_values = [];

                    for ($i = 0; $i < response.intensity.length; $i++) {
                        if (response.intensity[$i].count !== undefined) {
                            intensity_labels.push(response.intensity[$i].name + ' (' + response.intensity[$i].count + ')');
                            response.intensity[$i].count = response.intensity[$i].count * 100 / response.total_odors;
                        } else {
                            response.intensity[$i].count = 0;
                            intensity_labels.push(response.intensity[$i].name + ' (' + response.intensity[$i].count + ')');
                        }
                        intensity_values.push(response.intensity[$i].count);
                    }

                    //var ctxTypes = document.getElementById("intensity-chart").getContext('2d');
                    document.getElementById("intensity-chart-content").innerHTML = '&nbsp;';
                    document.getElementById("intensity-chart-content").innerHTML = '<canvas id="intensity-chart" width="200" height="200"></canvas>';
                    var ctxTypes = document.getElementById("intensity-chart").getContext("2d");
                    intensities = {
                        datasets: [{
                            data: intensity_values,
                            backgroundColor: [
                                'rgba(132,224,241)',
                                'rgba(81,220,247)',
                                'rgba(88,181,199)',
                                'rgba(43,147,167)',
                                'rgba(52,96,104)',
                                'rgba(23,91,104)',
                                'rgba(5,54,63)',
                            ]
                        }],
                        labels: intensity_labels
                    };
                    var intensityChart = new Chart(ctxTypes, {
                        type: 'doughnut',
                        data: intensities,
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

                    $('#intensity-total').text('TOTAL: ' + response.total_odors);


                    //Observations nice/foul Chart
                    var annoy_labels = [];
                    var annoy_values = [];

                    for ($i = 0; $i < response.annoy.length; $i++) {
                        if (response.annoy[$i].count !== undefined) {
                            annoy_labels.push(response.annoy[$i].name + ' (' + response.annoy[$i].count + ')');
                            response.annoy[$i].count = response.annoy[$i].count * 100 / response.total_odors;
                        } else {
                            response.annoy[$i].count = 0;
                            annoy_labels.push(response.annoy[$i].name + ' (' + response.annoy[$i].count + ')');
                        }
                        annoy_values.push(response.annoy[$i].count);
                    }

                    //var ctxTypes = document.getElementById("annoy-chart").getContext('2d');
                    document.getElementById("annoy-chart-content").innerHTML = '&nbsp;';
                    document.getElementById("annoy-chart-content").innerHTML = '<canvas id="annoy-chart" width="200" height="200"></canvas>';
                    var ctxTypes = document.getElementById("annoy-chart").getContext("2d");
                    annoy = {
                        datasets: [{
                            data: annoy_values,
                            backgroundColor: [
                                'rgba(9,5,61)',
                                'rgba(28,23,90)',
                                'rgba(22,12,148)',
                                'rgba(66,52,236)',
                                'rgba(82,73,196)',
                                'rgba(105,100,170)',
                                'rgba(103,100,131)',
                                'rgba(172,170,206)',
                                'rgba(198,194,255)',
                            ]
                        }],
                        labels: annoy_labels
                    };
                    var annoyChart = new Chart(ctxTypes, {
                        type: 'doughnut',
                        data: annoy,
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

                    $('#annoy-total').text('TOTAL: ' + response.total_odors);


                    //Commented obervations Chart
                    var comment_labels = ['Commented', 'Not commented'];
                    var comment_values = [];

                    comment_values.push(response.commented);

                    //var ctxTypes = document.getElementById("comment-chart").getContext('2d');
                    document.getElementById("comment-chart-content").innerHTML = '&nbsp;';
                    document.getElementById("comment-chart-content").innerHTML = '<canvas id="comment-chart" width="200" height="200"></canvas>';
                    var ctxTypes = document.getElementById("comment-chart").getContext("2d");
                    comment = {
                        datasets: [{
                            data: [comment_values, response.total_odors - comment_values],
                            backgroundColor: [
                                'rgba(98,167,148)',
                                'rgba(209,241,233)',
                            ]
                        }],
                        labels: comment_labels
                    };
                    var commentChart = new Chart(ctxTypes, {
                        type: 'doughnut',
                        data: comment,
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

                    $('#comment-total').text('TOTAL: ' + response.total_odors);


                    //Validated obervations Chart
                    var valid_labels = ['Validated', 'Not validated'];
                    var valid_values = [];

                    valid_values.push(response.verified);

                    //var ctxTypes = document.getElementById("valid-chart").getContext('2d');
                    document.getElementById("valid-chart-content").innerHTML = '&nbsp;';
                    document.getElementById("valid-chart-content").innerHTML = '<canvas id="valid-chart" width="200" height="200"></canvas>';
                    var ctxTypes = document.getElementById("valid-chart").getContext("2d");
                    valid = {
                        datasets: [{
                            data: [valid_values, response.total_odors - valid_values],
                            backgroundColor: [
                                'rgba(98,167,148)',
                                'rgba(209,241,233)',
                            ]
                        }],
                        labels: valid_labels
                    };
                    var validtChart = new Chart(ctxTypes, {
                        type: 'doughnut',
                        data: valid,
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

                    $('#valid-total').text('TOTAL: ' + response.total_odors);
                },
                error: function (data, textStatus, errorThrown) {
                    console.log(data);
                },
            })
        }

        
    </script>

@endsection

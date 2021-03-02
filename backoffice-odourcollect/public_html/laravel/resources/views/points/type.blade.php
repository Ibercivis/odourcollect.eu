@extends('layouts.app')

@section('content')

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
                    @elseif  ($errors->first() == 'error')
                        <div class="card-body border-top">
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Error!</strong> The file can't be loaded. Be sure it is an PNG file (84x84)
                                <a href="#" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </a>
                            </div>
                        </div>
                    @elseif ( count($errors) > 0 )
                        <div class="col-md-12">
                            <div class="col">
                                <div class="alert alert-dismissible alert-danger">
                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                    <strong>Ops!</strong> There are some errors in your information, please check the information and try again.
                                </div>
                            </div>
                        </div>
                    @endif
                @endif

                @if (session('success'))
                    <div class="col-md-12">
                        <div class="col">
                            <div class="alert alert-dismissible alert-success">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <strong>Yes!</strong> {!!session('success')!!}.
                            </div>
                        </div>
                    </div>
                @endif

                <div id="error" class="col-md-12 hide">
                    <div class="col">
                        <div class="alert alert-dismissible alert-danger">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <strong>Ops!</strong> There are some errors in your information, please check the information and try again.
                        </div>
                    </div>
                </div>

                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="card">
                        <h5 class="card-header">Types of activity of interest</h5>
                        <div class="card-body">
                            {!! Form::open(['route' => 'point.type.save', 'method' => 'POST', 'enctype' =>'multipart/form-data', 'id' => 'form__create-type', 'class' => '']) !!}
                                <div class="table-responsive">
                                    <table id="table" data-page-length='10' class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th style="width: 30%" scope="col"></th>
                                                <th scope="col">Name</th>
                                                <th scope="col"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th></th>
                                                <td><input class="form-control" type="file" name="file" id="file" accept="image/*"></td>
                                                <td><input id="name" type="text" name="name" class="form-control"></td>
                                                <td><button onclick="checkForm()" style="margin-top: -2px; padding: 10px 19px!important;" class="btn btn-primary">SAVE</button></td>
                                            </tr>
                                            @foreach ($types as $type)
                                                <tr>
                                                    <th scope="row">{{$type->id}}</th>
                                                    <td><img style="width: 31px;" src="{{ asset('assets/images/interest/' .$type->slug . '-spot.png') }}"></td>
                                                    <td>{{$type->name}}</td>
                                                    <td><a id="myBtn-{{$type->id}}" data-url="{{ route('point.type.delete', ['id' => $type->id])}}" class="modal__trigger btn-danger">DELETE</a></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            {!! Form::close() !!}
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

    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

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

        function checkForm(){
            event.preventDefault();

            var name = $('#name');
            var file = $('#file');

            name.removeClass('error');
            file.removeClass('error');

            var error = document.getElementById("error");

            var valid = true;

            if (!validateTextInput(name)) { valid = false; name.addClass('error'); }
            if (!(file.val())) { valid = false; file.addClass('error'); }

            if (valid){
                error.classList.add("hide");
                $( "#form__create-type" ).submit();
            } else {
                error.classList.remove("hide");
            }
        }

        function validateTextInput(input) {
            var value = input.val();
            if (value) {
                input.removeClass("invalid").addClass("valid");
                return true;
            } else {
                input.removeClass("valid").addClass("invalid");
                return false;
            }
        }
    </script>

@endsection

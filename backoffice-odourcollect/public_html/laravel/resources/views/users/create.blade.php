@extends('layouts.app')

@section('content')

    <div class="dashboard-wrapper">
        <div class="container-fluid dashboard-content">
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

                    <div id="error" class="hide">
                        <div class="col">
                            <div class="alert alert-dismissible alert-danger">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <strong>Ops!</strong> Check all the fields.
                            </div>
                        </div>
                    </div>

                    @if (session('success'))
                        <div class="col">
                            <div class="alert alert-dismissible alert-success">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <strong>Yes!</strong> {!!session('success')!!}.
                            </div>
                        </div>
                    @endif

                    @if($errors->any())
                        @if($errors->first() == 'error')
                            <div class="card-body border-top">
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Error!</strong> Try again later
                                    <a href="#" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </a>
                                </div>
                            </div>
                        @endif
                    @endif

                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="card">
                                <h5 class="card-header">New user</h5>
                                <div class="card-body">
                                     @if(Auth::guard('web')->check())
                                    {!! Form::open(['route' => 'user.save', 'method' => 'POST', 'id' => 'createUserForm', 'class' => '']) !!}
                                    @else
                                    {!! Form::open(['route' => 'admin.user.save', 'method' => 'POST', 'id' => 'createUserForm', 'class' => '']) !!}
                                    @endif
                                        <div class="form-group">
                                            <label for="new_name" class="col-form-label">Name</label>
                                            <input id="new_name" name="name" type="text" class="form-control" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="new_surname" class="col-form-label">Lastname</label>
                                            <input id="new_surname" name="surname" type="text" class="form-control" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="new_email">Email address</label>
                                            <input id="new_email" name="email" type="email" placeholder="name@example.com" class="form-control" required>
                                        </div>
                                        @if(Auth::guard('web')->check())
                                         <div class="form-group">
                                            <label for="new_email">Zone</label>
                                            <select name="zone" id="" class="form-control">
                                                @foreach ($zones as $zone)
                                                <option value="{{$zone->id}}">{{$zone->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @endif
                                        <label for="new_password">Password</label>
                                        <div class="input-group mb-3">
                                            <input id="new_password" name="password" type="password" placeholder="Password" class="form-control" required>
                                            <div class="input-group-append">
                                                <a onclick="generatePassword()" id="generate_password" type="button" class="btn btn-aux">Generate</a>
                                            </div>
                                        </div>
                                        <a onclick="checkForm()" class="btn btn-primary btn-large">Save</a>
                                    {!! Form::close() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

    <script>
        function checkForm(){
            event.preventDefault();

            var name = $('#new_name');
            var surname = $('#new_surname');
            var email = $('#new_email');
            var password = $('#new_password');

            name.removeClass('error');
            password.removeClass('error');
            surname.removeClass('error');
            email.removeClass('error');

            var error = document.getElementById("error");

            var valid = true;

            if (!validateTextInput(name)) { valid = false; name.addClass('error'); }
            if (!validateTextInput(password)) { valid = false; password.addClass('error'); }
            if (!validateTextInput(surname)) { valid = false; surname.addClass('error');}
            if (!validateEmailInput(email)) { valid = false; email.addClass('error');}

            if (valid){
                error.classList.add("hide");
                $( "#createUserForm" ).submit();

            } else {
                error.classList.remove("hide");
            }
        }

        function generatePassword(){
           $('#new_password').val(randString(12));
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

        function validateEmailInput(input) {
            var re = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
            var value = input.val();

            var is_email = re.test(input.val());

            if (value) {
                if (is_email) {
                    input.removeClass("invalid").addClass("valid");
                    return true;
                } else {
                    input.removeClass("valid").addClass("invalid");
                    return false;
                }
            } else {
                input.removeClass("valid").addClass("invalid");
                return false;
            }
        }

        function randString(length){
            var chars = "abcdefghijklmnopqrstuvwxyz!@#$%^&*()-+<>ABCDEFGHIJKLMNOP1234567890";
            var pass = "";
            for (var x = 0; x < length; x++) {
                var i = Math.floor(Math.random() * chars.length);
                pass += chars.charAt(i);
            }
            return pass;
        }
    </script>

@endsection

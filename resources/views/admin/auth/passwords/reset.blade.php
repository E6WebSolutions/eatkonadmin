

@extends('layouts.app')
@section('content')


    <!-- Main content -->
    <div class="main-content">
        <!-- Header -->
        <div class="header bg-gradient-danger py-7 py-lg-8 pt-lg-9">

            <div class="separator separator-bottom separator-skew zindex-100">
                <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1"
                     xmlns="http://www.w3.org/2000/svg">
                    <polygon class="fill-default" points="2560 0 2560 100 0 100"></polygon>
                </svg>
            </div>
        </div>
        <!-- Page content -->
        <div class="container mt--8 pb-5">
            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-7">
                    <div class="card bg-secondary border-0 mb-0">

                        <div class="card-body px-lg-5 py-lg-5">
                            <h3 style="text-align: center">Admin {{ __('Reset Password') }}</h3><br>
                            <form method="POST" action="{{ route('password.update') }}">
                                @csrf
                                @if($errors->any()) @include('admin.admin_layout.form_error') @endif
                                @if (session('status'))
                                    <div class="alert alert-success" role="alert">
                                        {{ session('status') }}
                                    </div>
                                @endif
                                <div class="form-group mb-3">
                                    <div class="input-group input-group-merge input-group-alternative">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                                        </div>
                                        <input type="hidden" name="token" value="{{ $token }}">
                                        <input id="email" type="email" placeholder="{{ __('chef.email') }}"
                                               value="{{ $email ?? old('email') }}"
                                               class="form-control @error('email') is-invalid @enderror" name="email"
                                               required autocomplete="email" autofocus>


                                        @error('email')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>

                                </div>
                                <div class="form-group mb-3">
                                    <div class="input-group input-group-merge input-group-alternative">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                                        </div>
                                        <input type="hidden" name="token" value="{{ $token }}">
                                        <input id="password" placeholder="{{ __('Password') }}" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">


                                        @error('password')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>

                                </div>
                                <div class="form-group mb-3">
                                    <div class="input-group input-group-merge input-group-alternative">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                                        </div>
                                        <input type="hidden" name="token" value="{{ $token }}">
                                        <input id="password-confirm" placeholder="{{ __('Confirm Password') }}" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">



                                    </div>

                                </div>

                                <div class="text-center">
                                    @csrf
                                    <button type="submit"
                                            class="btn btn-primary my-4">    {{ __('Reset Password') }}</button>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection






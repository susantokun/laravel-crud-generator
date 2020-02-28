@extends('auth.layouts.app')

@section('content')

                            <div class="row">
                                <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
                                <div class="col-lg-7">
                                    <div class="p-5">
                                        <div class="text-center">
                                            <h1 class="h4 text-gray-900 mb-4">{{ __('label.create_an_account') }}</h1>
                                        </div>
                                        <form class="user" method="POST" action="{{ route('register') }}">
                                            @csrf

                                            <div class="form-group">
                                                <input
                                                    type="text"
                                                    class="form-control form-control-user @error('name') is-invalid @enderror"
                                                    name="name"
                                                    value="{{ old('name') }}"
                                                    required
                                                    autocomplete="name"
                                                    autofocus
                                                    placeholder="{{ __('label.name') }}">
                                                @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror

                                            </div>
                                            <div class="form-group">
                                                <input
                                                    type="email"
                                                    class="form-control form-control-user @error('email') is-invalid @enderror"
                                                    name="email"
                                                    value="{{ old('email') }}"
                                                    required
                                                    autocomplete="email"
                                                    placeholder="{{ __('label.email') }}">
                                                @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror

                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-6 mb-3 mb-sm-0">
                                                    <input
                                                        type="password"
                                                        class="form-control form-control-user @error('password') is-invalid @enderror"
                                                        name="password"
                                                        required
                                                        autocomplete="new-password"
                                                        placeholder="{{ __('label.password') }}">
                                                    @error('password')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror

                                                </div>
                                                <div class="col-sm-6">
                                                    <input
                                                        type="password"
                                                        class="form-control form-control-user"
                                                        name="password_confirmation"
                                                        required
                                                        autocomplete="new-password"
                                                        placeholder="{{ __('label.confirm_password') }}">
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-primary btn-user btn-block">
                                                {{ __('label.register') }}
                                            </button>
                                            {{-- <hr>
                                            <a href="index.html" class="btn btn-google btn-user btn-block">
                                                <i class="fab fa-google fa-fw"></i> Register with Google
                                            </a>
                                            <a href="index.html" class="btn btn-facebook btn-user btn-block">
                                                <i class="fab fa-facebook-f fa-fw"></i> Register with Facebook
                                            </a> --}}
                                        </form>
                                        <hr>
                                        <div class="text-center">
                                            @if (Route::has('password.request'))

                                            <a class="small" href="{{ route('password.request') }}">
                                                {{ __('label.forgot_your_password') }}
                                            </a>
                                            @endif

                                        </div>
                                        <div class="text-center">
                                            <a class="small" href="{{route('login')}}">{{ __('label.already_have_an_account') }}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

@endsection

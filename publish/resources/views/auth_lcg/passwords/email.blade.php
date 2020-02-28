@extends('auth.layouts.app')

@section('content')

                            <div class="row">
                                <div class="col-lg-6 d-none d-lg-block bg-password-image"></div>
                                <div class="col-lg-6">
                                    <div class="p-5">
                                        <div class="text-center">
                                            @if (session('status'))
                                            <div class="alert alert-success" role="alert">
                                                {{ session('status') }}
                                            </div>
                                            @endif

                                            <h1 class="h4 text-gray-900 mb-2">{{ __('label.forgot_your_password') }}</h1>
                                            <p class="mb-4">{{ __('label.forgot_description') }}</p>
                                        </div>
                                        <form class="user" method="POST" action="{{ route('password.email') }}">
                                            @csrf

                                            <div class="form-group">
                                                <input
                                                    type="email"
                                                    class="form-control form-control-user @error('email') is-invalid @enderror"
                                                    id="exampleInputEmail"
                                                    aria-describedby="emailHelp"
                                                    placeholder="{{ __('label.email') }}"
                                                    name="email"
                                                    value="{{ old('email') }}"
                                                    required
                                                    autocomplete="email"
                                                    autofocus>
                                                @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror

                                            </div>
                                            <button type="submit" class="btn btn-primary btn-user btn-block">
                                                {{ __('label.send_password_reset') }}
                                            </button>
                                        </form>
                                        <hr>
                                        <div class="text-center">
                                            <a class="small" href="{{route('register')}}">{{ __('label.create_an_account') }}</a>
                                        </div>
                                        <div class="text-center">
                                            <a class="small" href="{{route('login')}}">{{ __('label.already_have_an_account') }}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

@endsection

@extends('master')
@section('register_css')
    <!--begin::Page Custom Styles(used by this page)-->
    <link href="{{ asset('assets/css/pages/login/classic/login-4.min.css') }}" rel="preload" onload="this.onload=null;this.rel='stylesheet'" as="style" type="text/css"/>
    <!--end::Page Custom Styles-->
    <!--begin::Page Custom Styles(used by this page)-->
    <link href="{{ asset('assets/css/pages/wizard/wizard-3.min.css') }}" rel="preload" onload="this.onload=null;this.rel='stylesheet'" as="style" type="text/css"/>
    <!--end::Page Custom Styles-->
@endsection
@section('content')
    <body id="kt_body"
          class="header-fixed header-mobile-fixed subheader-enabled subheader-fixed aside-enabled aside-fixed aside-minimize-hoverable page-loading">
    <!--begin::Main-->
    <div class="d-flex flex-column flex-root">
        <!--begin::Login-->
        <div class="login login-4 login-signup-on d-flex flex-row-fluid" id="kt_login">
            <div class="d-flex flex-center flex-row-fluid bgi-size-cover bgi-position-top bgi-no-repeat"
                 style="background-image: url(' {{ asset('assets/media/bg/bg-3.jpg') }}');">
                <div class="login-form text-center p-7 position-relative overflow-hidden">
                    <!--begin::Login Header-->
                    <div class="d-flex flex-center mb-15">
                        <a href="{{route('login')}}">
                            <img src="{{ asset('assets/media/logos/logo.png') }}" class="max-h-75px" alt="Siplah Klikmro"/>
                        </a>
                    </div>
                    <!--end::Login Header-->
                    <!--begin::Login Sign in form-->
                    <div class="login-signin">
                        <div class="mb-20">
                            <h3>{{ __('Masuk Sebagai Penyedia') }}</h3>
                            <div class="text-muted font-weight-bold">
                                {{ __('Silahkan masukan akun yang terdaftar sebagai penyedia') }}
                            </div>
                        </div>

                        @include('auth.validation-errors')

                        @if (session('status'))
                            <div class="mb-4 font-medium text-sm text-green-600">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="form-group mb-5">
                                <x-jet-input id="email"
                                             class="block mt-1 w-full form-control h-auto form-control-solid py-4 px-8"
                                             type="email" name="email" :value="old('email')" required autofocus
                                             placeholder="Email"/>
                            </div>

                            <div class="form-group mb-5">
                                <x-jet-input id="password"
                                             class="block mt-1 w-full form-control h-auto form-control-solid py-4 px-8"
                                             type="password" name="password" required autocomplete="current-password"
                                             placeholder="Password"/>
                            </div>

                            <div class="form-group d-flex flex-wrap justify-content-between align-items-center">
                                <div class="checkbox-inline">
                                    <label class="checkbox m-0 text-muted">
                                        <x-jet-checkbox id="remember_me" name="remember"
                                                        class="checkbox m-0 text-muted"/>
                                        <span></span>{{ __('Ingat saya') }}
                                    </label>
                                </div>
                                @if (Route::has('password.request'))
                                    <a class="underline text-sm text-gray-600 hover:text-gray-900 text-muted text-hover-primary"
                                       href="{{ route('password.request') }}">
                                        {{ __('Lupa password?') }}
                                    </a>
                                @endif
                            </div>

                            <div class="flex items-center justify-end mt-4">


                                <button type="submit" class="ml-4 btn btn-primary font-weight-bold px-9 py-4 my-3 mx-4">
                                    {{ __('Masuk') }}
                                </button>
                            </div>
                        </form>
                        <div class="mt-10">
                            <span class="opacity-70 mr-4">{{ __('Belum memiliki account?') }}</span>
                            <a href="{{ route('register') }}"
                               class="text-muted text-hover-primary font-weight-bold">{{ __('Daftar disini!') }}</a>
                        </div>
                    </div>
                    <!--end::Login Sign in form-->
                </div>
            </div>
        </div>
        <!--end::Login-->
    </div>
    <!--end::Main-->
    </body>
@endsection
@section('register_js')
    <script async defer src="{{asset('assets/js/pages/custom/login/login-general.min.js') }}"></script>
    <script async defer src="{{asset('assets/js/pages/custom/wizard/wizard-3.min.js') }}"></script>
    <script async defer src="{{asset('assets/js/pages/crud/forms/widgets/input-mask.min.js') }}"></script>
@endsection

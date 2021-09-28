@extends('master')
@section('register_css')
    <!--begin::Page Custom Styles(used by this page)-->
    <link href="{{ asset('assets/css/pages/login/classic/login-4.css') }}" rel="stylesheet" type="text/css"/>
    <!--end::Page Custom Styles-->
    <!--begin::Page Custom Styles(used by this page)-->
    <link href="{{ asset('assets/css/pages/wizard/wizard-3.css') }}" rel="stylesheet" type="text/css"/>
    <!--end::Page Custom Styles-->
@endsection
@section('content')

    <body id="kt_body"
          class="header-fixed header-mobile-fixed subheader-enabled subheader-fixed aside-enabled aside-fixed aside-minimize-hoverable page-loading">
    <!--begin::Main-->
    <div class="d-flex flex-column flex-root">
        <!--begin::Login-->
        <div class="login login-4 login-signin-on d-flex flex-row-fluid" id="kt_login">
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
                    <!--begin::Login Sign up form-->
                    <div class="login-signup">
                        <div class="mb-20">
                            <h3>{{__('Daftar Sebagai Penyedia')}}</h3>
                            <div class="text-muted font-weight-bold">{{__('Silahkan masukan informasi akun anda sebagai penyedia')}}</div>
                        </div>


                        @include('auth.validation-errors')

                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="mt-4 form-group mb-5 text-left">
                                <x-jet-input id="name" class="block mt-1 w-full form-control h-auto form-control-solid py-4 px-8" type="text" name="name"
                                             :value="old('name')" required autofocus autocomplete="name" placeholder="{{ __('Name') }}"/>
                            </div>

                            <div class="mt-4 form-group mb-5 text-left">
                                <x-jet-input id="email" class="block mt-1 w-full form-control h-auto form-control-solid py-4 px-8" type="email" name="email"
                                             :value="old('email')" required placeholder="{{ __('Email') }}"/>
                            </div>

                            <div class="mt-4 form-group mb-5 text-left">
                                <x-jet-input id="password" class="block mt-1 w-full form-control h-auto form-control-solid py-4 px-8" type="password" name="password"
                                             required autocomplete="new-password" placeholder="{{ __('Password') }}"/>
                            </div>

                            <div class="mt-4 form-group mb-5 text-left">
                                <x-jet-input id="password_confirmation" class="block mt-1 w-full form-control h-auto form-control-solid py-4 px-8" type="password"
                                             name="password_confirmation" required autocomplete="new-password" placeholder="{{ __('Confirm Password') }}"/>
                            </div>

                            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                                <div class="mt-4 form-group mb-5 text-left">
                                    <div class="checkbox-inline">
                                        <label class="checkbox m-0">
                                            <x-jet-checkbox name="terms" id="terms"/>
                                            <span></span>
                                            {!! __('Saya setuju dengan :terms_of_service dan :privacy_policy', [
                                                        'terms_of_service' => '<a target="_blank" href="https://dev-siplah.klikmro.com/ketentuan-penyedia" class="font-weight-bold ml-1 mr-1 underline text-sm text-gray-600 hover:text-gray-900">'.__('Persyaratan Layanan').'</a>',
                                                        'privacy_policy' => '<a target="_blank" href="https://dev-siplah.klikmro.com/kebijakan-privasi" class="font-weight-bold ml-1 underline text-sm text-gray-600 hover:text-gray-900">'.__('Kebijakan Privasi').'</a>',
                                                ]) !!}
                                        </label>
                                    </div>
                                    <div class="form-text text-muted text-center"></div>
                                </div>
                            @endif
                            <button type="submit" class="ml-4 btn btn-primary font-weight-bold px-9 py-4 my-3 mx-4">
                                {{ __('Daftar') }}
                            </button>
                        </form>
                        <div class="mt-10">
                            <span class="opacity-70 mr-4">{{ __('Sudah memiliki akun?') }}</span>
                            <a href="{{ route('login') }}"
                               class="text-muted text-hover-primary font-weight-bold">{{ __('Masuk disini') }}</a>
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
    <script src="{{asset('assets/js/pages/custom/login/login-general.js') }}"></script>
    <script src="{{asset('assets/js/pages/custom/wizard/wizard-3.js') }}"></script>
    <script src="{{asset('assets/js/pages/crud/forms/widgets/input-mask.js') }}"></script>
@endsection

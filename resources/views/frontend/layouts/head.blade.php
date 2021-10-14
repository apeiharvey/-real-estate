<!-- Meta Tag -->
@yield('meta')
<!-- Title Tag  -->
<title>@yield('title')</title>
<!-- Favicon -->
<!-- Place favicon.png in the root directory -->
<link rel="shortcut icon" href="{{asset('frontend/img/HIVE_LOGO.png')}}" type="image/x-icon" />
<!-- Font Icons css -->
<link rel="stylesheet" href="{{asset('frontend/css/font-icons.css')}}">
<!-- plugins css -->
<link rel="stylesheet" href="{{asset('frontend/css/plugins.css')}}">
<!-- Main Stylesheet -->
<link rel="stylesheet" href="{{asset('frontend/css/style.css')}}">
<!-- Responsive css -->
<link rel="stylesheet" href="{{asset('frontend/css/responsive.css')}}">

<style>
    .pointer {
        cursor: pointer;
    }
    input:disabled {
        background: #dddddd;
    }
    .hidden {
        display: none;
    }
    .flx {
        display: flex;
    }
</style>

@stack('styles')

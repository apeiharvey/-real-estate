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
    .video-container {
    position: relative;
    padding-bottom: 50%; /* 16:9 */
    height: 0;
    }
    .video-container iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }
    .slick-slide-arrow-1 .slick-arrow{
        background:transparent;
    }
</style>

@stack('styles')

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
    
    @media (min-width: 576px){
        .link-area{
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            left: 0;
            width: 40px;
        }
    }

    @media (max-width: 576px){
        .link-area {
            width: 100%;
            bottom: 0;
            left: 0;
            height: 36px;
            display: flex;
        }
    }
    
    .link-area a {
        display: block;
        width: 100%;
    }
    .link-area a img {
        display: block;
        width: 100%;
        transition: all .15s ease-in-out;
    }
</style>

@stack('styles')

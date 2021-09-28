<meta charset="utf-8" />
<title>@yield('custom_title', 'Siplah Klikmro')</title>
<meta name="description" content="Page with empty content" />
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="canonical" href="https://siplah.klikmro.com" />
<!--begin::Fonts-->
<link rel="preload" @if(request()->path()==="login") rel="preload" onload="this.onload=null;this.rel='stylesheet'" @else rel="stylesheet" @endif as="style" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
<!--end::Fonts-->
<!--begin::Global Theme Styles(used by all pages)-->
<link href="{{ asset('assets/plugins/global/plugins.bundle.min.css') }}" @if(request()->path()==="login") rel="preload" onload="this.onload=null;this.rel='stylesheet'" @else rel="stylesheet" @endif as="style" type="text/css" />
<link href="{{ asset('assets/plugins/custom/prismjs/prismjs.bundle.min.css') }}" @if(request()->path()==="login") rel="preload" onload="this.onload=null;this.rel='stylesheet'" @else rel="stylesheet" @endif as="style" type="text/css" />
<link href="{{ asset('assets/css/style.bundle.min.css') }}" @if(request()->path()==="login") rel="preload" onload="this.onload=null;this.rel='stylesheet'" @else rel="stylesheet" @endif as="style" type="text/css" />
<!--end::Global Theme Styles-->
<!--begin::Layout Themes(used by all pages)-->
<link href="{{ asset('assets/css/themes/layout/header/base/light.min.css') }}" @if(request()->path()==="login") rel="preload" onload="this.onload=null;this.rel='stylesheet'" @else rel="stylesheet" @endif as="style" type="text/css" />
<link href="{{ asset('assets/css/themes/layout/header/menu/light.min.css') }}" @if(request()->path()==="login") rel="preload" onload="this.onload=null;this.rel='stylesheet'" @else rel="stylesheet" @endif as="style" type="text/css" />
<link href="{{ asset('assets/css/themes/layout/brand/light.min.css') }}" @if(request()->path()==="login") rel="preload" onload="this.onload=null;this.rel='stylesheet'" @else rel="stylesheet" @endif as="style" type="text/css" />
<link href="{{ asset('assets/css/themes/layout/aside/light.min.css') }}" @if(request()->path()==="login") rel="preload" onload="this.onload=null;this.rel='stylesheet'" @else rel="stylesheet" @endif as="style" type="text/css" />
<link href="{{ asset('css/universal.css') }}" @if(request()->path()==="login") rel="preload" onload="this.onload=null;this.rel='stylesheet'" @else rel="stylesheet" @endif as="style" type="text/css" />
<!--end::Layout Themes-->
@yield('admin_css')
<link rel="shortcut icon" href="{{ asset('assets/media/logos/favicon.png') }}" />

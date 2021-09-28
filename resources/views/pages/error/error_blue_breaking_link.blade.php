@extends('includes.layout_min')

@section('custom_title','Oops')

@section('admin_css')
	<link href="{{ asset('assets/css/pages/error/error-6.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('pages')
<!--begin::Error-->
<div class="error error-6 d-flex flex-row-fluid bgi-size-cover bgi-position-center" style="background-image: url({{asset('assets/media/error/bg6.jpg')}});">
    <!--begin::Content-->
    <div class="d-flex flex-column flex-row-fluid text-center">
        <h1 class="error-title font-weight-boldest text-white mb-12" style="margin-top: 12rem;">{{@$error[title]}}</h1>
        <p class="display-4 font-weight-bold text-white">{{@$error[message]}}</p>
    </div>
    <!--end::Content-->
</div>
<!--end::Error-->
@endsection

@section('admin_js')
@endsection


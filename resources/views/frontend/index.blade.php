@extends('frontend.layouts.master')
@section('title','Marq - Nest - Cendana Homes')
@section('main-content')
    @include('frontend.pages.homepage.banner-slider')

    @include('frontend.pages.homepage.property-slider')

    @include('frontend.pages.homepage.house-list')

    @include('frontend.pages.homepage.testimonial')
@endsection
@extends('frontend.layouts.master')
@section('title','Uptown Hive Commercial')
@section('main-content')
    @include('frontend.pages.homepage.banner-slider')

    @include('frontend.pages.homepage.gallery-slider')

    @include('frontend.pages.homepage.house-list')

    @include('frontend.pages.homepage.promotion')
    @include('frontend._includes.modal_slider')
@endsection
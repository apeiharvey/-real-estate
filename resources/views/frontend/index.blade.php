@extends('frontend.layouts.master')
@section('title','Hive Commercial')
@section('main-content')
    @include('frontend.pages.homepage.banner-slider')

    @include('frontend.pages.homepage.property-slider')

    @include('frontend.pages.homepage.house-list')

    @include('frontend.pages.homepage.promotion')
@endsection
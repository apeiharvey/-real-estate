@extends('frontend.layouts.master')
@section('title','Nama Website || Simulate Mortages')
@section('main-content')
    @include('frontend.pages.homepage.banner-slider')
@endsection
@push('styles')
    <style>
        .pointer {
            cursor: pointer;
        }
    </style>
@endpush
@extends('includes.layout_admin')

@section('custom_title','Nego')
@section('admin_css')
<link href="{{asset('assets/plugins/custom/datatables/datatables.bundle.css')}}" rel="stylesheet" type="text/css"/>
@endsection
@section('pages')
@include('component/loading_modal')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Subheader-->
    <div class="subheader py-2 py-lg-6 subheader-solid" id="kt_subheader">
        <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <!--begin::Info-->
            <div class="d-flex align-items-center flex-wrap mr-1">
                <!--begin::Page Heading-->
                <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <!--begin::Breadcrumb-->
                    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
                        <li class="breadcrumb-item text-muted">
                            <a href="" class="text-muted">Home</a>
                        </li>
                        <li class="breadcrumb-item text-muted">
                            <a href="" class="text-muted">Nego</a>
                        </li>
                    </ul>
                    <!--end::Breadcrumb-->
                </div>
                <!--end::Page Heading-->
            </div>
            <!--end::Info-->
        </div>
    </div>
    <!--end::Subheader-->
    <!--begin::Entry-->
    <div class="d-flex flex-column flex-row-fluid" id="kt_wrapper">
        <!--begin::Content-->
        <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
            <!--begin::Entry-->
            <div class="d-flex flex-column-fluid">
                <!--begin::Container-->
                <div class="container">
                    <!--begin::Page Layout-->
                    <div class="d-flex flex-row">
                        <!--begin::Layout-->
                        <div class="flex-row-fluid">
                            <!--begin::Section-->
                            <!--begin::Advance Table Widget 10-->
                            <div class="card card-custom">
                                <div class="card-header card-header-tabs-line">
                                    <div class="card-toolbar">
                                        <ul class="nav nav-tabs nav-bold nav-tabs-line">
                                            <li class="nav-item">
                                                <a @if(strpos(url()->full(),'stid=1')!==false || strpos(url()->full(),'stid') === false ) class="nav-link active"
                                                   @else class="nav-link" @endif
                                                   href="{{url('nego?stid=1')}}">
                                                    <span class="nav-icon"><i class="flaticon-chat"></i></span>
                                                    <span class="nav-text">Dinego Pembeli</span>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a @if(strpos(url()->full(),'stid=2')!==false) class="nav-link active"
                                                   @else class="nav-link" @endif
                                                   href="{{url('nego?stid=2')}}">
                                                    <span class="nav-icon"><i class="flaticon-reply"></i></span>
                                                    <span class="nav-text">Dinego Penyedia</span>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a @if(strpos(url()->full(),'stid=3')!==false) class="nav-link active"
                                                   @else class="nav-link" @endif
                                                   href="{{url('nego?stid=3')}}">
                                                    <span class="nav-icon"><i class="flaticon2-check-mark"></i></span>
                                                    <span class="nav-text">Diterima</span>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a @if(strpos(url()->full(),'stid=4')!==false) class="nav-link active"
                                                   @else class="nav-link" @endif
                                                   href="{{url('nego?stid=4')}}">
                                                    <span class="nav-icon"><i class="far fa-thumbs-down"></i></span>
                                                    <span class="nav-text">Ditolak</span>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a @if(strpos(url()->full(),'stid=5')!==false) class="nav-link active"
                                                   @else class="nav-link" @endif
                                                   href="{{url('nego?stid=5')}}">
                                                    <span class="nav-icon"><i class="flaticon2-cross"></i></span>
                                                    <span class="nav-text">Dibatalkan</span>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a @if(strpos(url()->full(),'stid=6')!==false) class="nav-link active"
                                                   @else class="nav-link" @endif
                                                   href="{{url('nego?stid=6')}}">
                                                    <span class="nav-icon"><i class="flaticon2-time"></i></span>
                                                    <span class="nav-text">Kadaluarsa</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="tab-content">
                                        <div class="tab-pane fade show active" id="kt_tab_pane_1_3" role="tabpanel" aria-labelledby="kt_tab_pane_1_3">
                                            <div class="card-body">
                                                <!--begin: Search Form-->
                                                <form class="mb-15">
                                                    <div class="row mb-6">
                                                        <div class="col-lg-4 mb-lg-0 mb-6">
                                                            <label>Produk</label>
                                                            <input id="product_name" type="text" class="form-control datatable-input" data-col-index="0" />
                                                            <div id="invalid_product_name" class="hide invalid-feedback">Pencarian product name harus lebih dari 3 karakter</div>
                                                        </div>
                                                        <div class="col-lg-4 mb-lg-0 mb-6">
                                                            <label>Jumlah</label>
                                                            <div class="input-group">
                                                                <select class="select-operation form-control datatable-input" data-col-index="1">
                                                                    <option>=</option>
                                                                    <option>></option>
                                                                    <option><</option>
                                                                    <option>>=</option>
                                                                    <option><=</option>
                                                                </select>
                                                                <input type="text" class="form-control datatable-input" data-col-index="1" />
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 mb-lg-0 mb-6">
                                                            <label>Harga Nego</label>
                                                            <div class="input-group">
                                                                <input id="nego_price_from" min="0" type="number" class="form-control datatable-input" name="start" placeholder="From" data-col-index="2" />
                                                                <div class="input-group-append">
															<span class="input-group-text">
																<i class="la la-ellipsis-h"></i>
															</span>
                                                                </div>
                                                                <input id="nego_price_to" type="text" class="form-control datatable-input" name="end" placeholder="To" data-col-index="2" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-8">
{{--                                                        <div class="col-lg-4 mb-lg-0 mb-6">--}}
{{--                                                            <label>Termin Bayar</label>--}}
{{--                                                            <div class="input-group">--}}
{{--                                                                <select class="select-operation form-control datatable-input" data-col-index="3">--}}
{{--                                                                    <option selected>=</option>--}}
{{--                                                                    <option>></option>--}}
{{--                                                                    <option><</option>--}}
{{--                                                                    <option>>=</option>--}}
{{--                                                                    <option><=</option>--}}
{{--                                                                </select>--}}
{{--                                                                <input type="text" class="form-control datatable-input" data-col-index="3" />--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}
                                                        <div class="col-lg-4 mb-lg-0 mb-6">
                                                            <label>Tanggal Terima</label>
                                                            <div class="input-daterange input-group" id="kt_datepicker">
                                                                <input readonly id="nego_date_from" type="text" class="form-control datatable-input" name="start" placeholder="From" data-col-index="3" />
                                                                <div class="input-group-append">
															<span class="input-group-text">
																<i class="la la-ellipsis-h"></i>
															</span>
                                                                </div>
                                                                <input readonly id="nego_date_to" type="text" class="form-control datatable-input" name="end" placeholder="To" data-col-index="3" />
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 mb-lg-0 mb-6">
                                                            <label>Offer</label>
                                                            <div class="input-group">
                                                                <input id="offer_price_from" type="text" class="form-control datatable-input" name="start" placeholder="From" data-col-index="4" />
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">
                                                                        <i class="la la-ellipsis-h"></i>
                                                                    </span>
                                                                </div>
                                                                <input id="offer_price_to" type="text" class="form-control datatable-input" name="end" placeholder="To" data-col-index="4" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-8">
                                                        <div class="col-lg-12">
                                                            <button class="btn btn-primary btn-primary--icon" id="kt_search">
													<span>
														<i class="la la-search"></i>
														<span>Search</span>
													</span>
                                                            </button>&#160;&#160;
                                                            <button class="btn btn-secondary btn-secondary--icon" id="kt_reset">
													<span>
														<i class="la la-close"></i>
														<span>Reset</span>
													</span>
                                                            </button></div>
                                                    </div>
                                                </form>
                                                <!--begin: Datatable-->
                                                <table class="table table-bordered table-hover table-checkable" id="kt_datatable">
                                                    <thead>
                                                    <tr>
                                                        <th>Produk</th>
                                                        <th>Jumlah</th>
                                                        <th>Harga Nego</th>
                                                        <th>Tanggal Terima</th>
                                                        <th>Offer</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                    </thead>
                                                </table>
                                                <!--end: Datatable-->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--begin::Body-->

                                <!--end::Body-->
                            </div>
                            <!--end::Advance Table Widget 10-->
                            <!--end::Section-->
                        </div>
                        <!--end::Layout-->
                    </div>
                    <!--end::Page Layout-->
                </div>
                <!--end::Container-->
            </div>
            <!--end::Entry-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Entry-->
</div>
@include('pages/nego/modal')
@include('component/loading')
@endsection

@section('admin_js')
<script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
<script src="{{ asset('assets/plugins/custom/printThis.js') }}"></script>
<script src="{{ asset('assets/js/pages/crud/forms/widgets/select2.js') }}"></script>
<script src="{{ asset('js/pages/nego/index.js') }}??ver=00001"></script>
@if(session()->has('middleware-info'))
    <script>
        Swal.fire({
            title: "Peringatan!",
            text:  "{{ session()->get('middleware-info') }}",
            type: "error",
            confirmButtonClass: 'btn btn-primary',
            buttonsStyling: false,
        });
    </script>
@endif
@endsection



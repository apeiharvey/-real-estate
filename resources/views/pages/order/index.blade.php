@extends('includes.layout_admin')

@section('custom_title','Transaksi')

@section('admin_css')
<link href="{{asset('assets/plugins/custom/datatables/datatables.bundle.css')}}" rel="stylesheet" type="text/css"/>
@endsection

@section('pages')
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
                            <a href="{{route('dashboard')}}" class="text-muted">Home</a>
                        </li>
                        <li class="breadcrumb-item text-muted">
                            <a href="{{route('order')}}" class="text-muted">Transaksi</a>
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
    <div class="d-flex flex-column-fluid">
        <!--begin::Container-->
        <div class="container">

            <div class="row" data-sticky-container="">
                <div class="col-lg-3 col-xl-2">
                    <div class="card card-custom sticky" data-sticky="true" data-margin-top="140px" data-sticky-for="1023" data-sticky-class="sticky">
                        <div class="card-body p-0">
                            <ul class="navi navi-bold navi-hover my-5" role="tablist">
                                <li class="navi-item">
                                    <a class="navi-link btn-list-order active"  data-title="Semua Transaksi" data-status="">
                                        <span class="navi-text">Semua</span>
                                    </a>
                                </li>
                                <li class="navi-item">
                                    <a class="navi-link btn-list-order"  data-title="Transaksi Baru" data-status="CREATED">
                                        <span class="navi-text">Baru</span>
                                    </a>
                                </li>
                                <li class="navi-item">
                                    <a class="navi-link btn-list-order"  data-title="Transaksi Terkonfirmasi" data-status="ORDER_CONFIRM">
                                        <span class="navi-text">Dikonfirmasi Penyedia</span>
                                    </a>
                                </li>
                                <li class="navi-item">
                                    <a class="navi-link btn-list-order"  data-title="Transaksi Ditolak Penyedia" data-status="ORDER_REJECTED">
                                        <span class="navi-text">Ditolak Penyedia</span>
                                    </a>
                                </li>
                                <li class="navi-item">
                                    <a class="navi-link btn-list-order"  data-title="Transaksi Dibatalkan Pembeli" data-status="BUYER_REJECT">
                                        <span class="navi-text">Dibatalkan Pembeli</span>
                                    </a>
                                </li>
                                <li class="navi-item">
                                    <a class="navi-link btn-list-order"  data-title="Transaksi Disetujui Pembeli" data-status="BUYER_APPROVED">
                                        <span class="navi-text">Disetujui Pembeli</span>
                                    </a>
                                </li>
                                <li class="navi__separator"></li>
                                <li class="navi-item">
                                    <a class="navi-link btn-list-order"  data-title="Transaksi Diproses" data-status="ORDER_PROCESSED">
                                        <span class="navi-text">Proses</span>
                                    </a>
                                </li>
                                <li class="navi-item">
                                    <a class="navi-link btn-list-order"  data-title="Pembaharuan Transaksi" data-status="ORDER_UPDATE">
                                        <span class="navi-text">Pembaharuan Transaksi</span>
                                    </a>
                                </li>
                                <li class="navi-item">
                                    <a class="navi-link btn-list-order"  data-title="Pengiriman" data-status="ORDER SHIPPED,ORDER_DELIVERED">
                                        <span class="navi-text">Pengiriman</span>
                                    </a>
                                </li>
                                <li class="navi-item">
                                    <a class="navi-link btn-list-order"  data-title="Barang Diterima" data-status="ORDER_RECEIVED">
                                        <span class="navi-text">Barang Diterima</span>
                                    </a>
                                </li>
                                <li class="navi-item">
                                    <a class="navi-link btn-list-order"  data-title="Transaksi Dikomplen" data-status="COMPLAINT">
                                        <span class="navi-text">Transaksi Dikomplen</span>
                                    </a>
                                </li>
                                <li class="navi-item">
                                    <a  class="navi-link btn-list-order"  data-title="Berita Acara Serah Terima" data-status="BAST_CREATED"
                                        data-toggle="tooltip" title="" data-placement="right" data-original-title="Berita Acara Serah Terima">
                                        <span class="navi-text">BAST Dibuat</span>
                                    </a>
                                </li>
                                <li class="navi-item">
                                    <a  class="navi-link btn-list-order"  data-title="Berita Acara Serah Terima" data-status="BAST_SUBMITTED"
                                        data-toggle="tooltip" title="" data-placement="right" data-original-title="Berita Acara Serah Terima">
                                        <span class="navi-text">BAST Dikirim</span>
                                    </a>
                                </li>
                                <li class="navi-item">
                                    <a class="navi-link btn-list-order" data-title="Pembayaran" data-status="PAYMENT_PROCESSED,PAYMENT_CONFIRMED">
                                        <span class="navi-text">Pembayaran</span>
                                    </a>
                                </li>
                                <li class="navi-item">
                                    <a class="navi-link btn-list-order" data-title="Komplain Pembayaran" data-status="PAYMENT_COMPLAINED">
                                        <span class="navi-text">Pembayaran dikomplain</span>
                                    </a>
                                </li>
                                <li class="navi-item">
                                    <a class="navi-link btn-list-order"  data-title="Sudah Selesai" data-status="COMPLETED">
                                        <span class="navi-text">Selesai</span>
                                    </a>
                                </li>
                                <li class="navi-item">
                                    <a class="navi-link btn-list-order"  data-title="" data-status="EXPIRED">
                                        <span class="navi-text">Kadaluarsa</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9 col-xl-10">
                    <!--begin::Card-->
                    <div class="card card-custom gutter-b">
                        <div class="card-header flex-wrap border-0 pt-6 pb-0">
                            <div class="card-title">
                                <h3 class="card-label">
                                    <span id="info-status-title">Semua Transaksi</span>
                                    <span class="d-block text-muted pt-2 font-size-sm" id="info-status-subtitle"></span>
                                </h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <form class="mb-8">
                                <div class="row mb-6">
                                    <div class="col-lg-4 mb-lg-0 mb-6">
                                        <label>Nomor Order / PO</label>
                                        <input id="fl_po_no" type="text" class="form-control datatable-input" data-col-index="0" />
                                        <div id="invalid_po_no" class="hide invalid-feedback">Pencarian product name harus lebih dari 3 karakter</div>
                                    </div>
                                    <div class="col-lg-4 mb-lg-0 mb-6">
                                        <label>Pelanggan</label>
                                        <input id="fl_cust" type="text" class="form-control datatable-input" data-col-index="1" placeholder="Nama Pelanggan / Email / HP"/>
                                        <div id="invalid_cust" class="hide invalid-feedback">Pencarian product name harus lebih dari 3 karakter</div>
                                    </div>
                                    <div class="col-lg-4 mb-lg-0 mb-6">
                                        <label>Tujuan Pengiriman</label>
                                        <input id="fl_address" type="text" class="form-control datatable-input" data-col-index="2" placeholder="Alamat"/>
                                        <div id="invalid_address" class="hide invalid-feedback">Pencarian product name harus lebih dari 3 karakter</div>
                                    </div>
                                </div>
                                <div class="row mb-8">
                                    <div class="col-lg-4 mb-lg-0 mb-6">
                                        <label>Tanggal</label>
                                        <div class="input-daterange input-group" id="kt_datepicker">
                                            <input readonly id="fl_date_from" type="text" class="form-control datatable-input" name="start" placeholder="Dari" data-col-index="3" />
                                            <div class="input-group-append">
                                                <span class="input-group-text">
                                                    <i class="la la-ellipsis-h"></i>
                                                </span>
                                            </div>
                                            <input readonly id="fl_date_to" type="text" class="form-control datatable-input" name="end" placeholder="Sampai" data-col-index="3" />
                                        </div>
                                    </div>
                                    <div class="col-lg-4 mb-lg-0 mb-6">
                                        <label>Total Pembayaran</label>
                                        <div class="input-group">
                                            <input id="fl_price_from" type="text" class="form-control datatable-input" name="start" placeholder="Dari" data-col-index="4" />
                                            <div class="input-group-append">
                                                <span class="input-group-text">
                                                    <i class="la la-ellipsis-h"></i>
                                                </span>
                                            </div>
                                            <input id="fl_price_to" type="text" class="form-control datatable-input" name="end" placeholder="Sampai" data-col-index="4" />
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
                            <table class="table table-bordered table-hover table-checkable" id="kt_datatable" style="margin-top: 13px !important">
                                <thead>
                                <tr>
                                    <th>Pesanan</th>
                                    <th>Status</th>
                                    <th>Tujuan Pengiriman</th>
                                    <th>Tanggal</th>
                                    <th>Total</th>
                                    <th>Pembatalan</th>
                                    <th>Pembaharuan</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                            </table>
                            <!--end: Datatable-->
                        </div>
                    </div>
                    <!--end::Card-->
                </div>
            </div>

        </div>
        <!--end::Container-->
    </div>
    <!--end::Entry-->
</div>
@endsection
@section('admin_js')
	<script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('js/pages/order/index.js') }}?ver=0012"></script>
    @if(session()->has('middleware-info'))
    <script>
        Swal.fire({
            title: "Peringatan !",
            text:  "{{ session()->get('middleware-info') }}",
            type: "error",
            confirmButtonClass: 'btn btn-primary',
            buttonsStyling: false,
        });
    </script>
    @endif
@endsection


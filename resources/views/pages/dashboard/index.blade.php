@extends('includes.layout_admin')

@section('custom_title','Dashboard')

@section('admin_css')

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
                                <a href="{{url()->current()}}" class="text-muted">Home</a>
                            </li>
                            <li class="breadcrumb-item text-muted">
                                <a href="{{url()->current()}}" class="text-muted">@yield('custom_title')</a>
                            </li>
                        </ul>
                        <!--end::Breadcrumb-->
                    </div>
                    <!--end::Page Heading-->
                </div>
                <!--end::Info-->
                <!--begin::Toolbar-->
                <div class="d-flex align-items-center">
                    <!--begin::Actions-->
                    <a href="{{route('vendor')}}" class="btn btn-light-primary font-weight-bolder btn-sm">Informasi Vendor</a>
                    <!--end::Actions-->
                </div>
                <!--end::Toolbar-->
            </div>
        </div>
        <!--end::Subheader-->
        <!--begin::Entry-->
        <div class="d-flex flex-column-fluid">
            <!--begin::Container-->
            <div class="container">
                <div class="card">
                    <div class="card-body">
                        <div class="row m-0">
                            <div class="col bg-light-warning px-4 py-4 rounded-xl mr-4 mb-4">
                                <a href="{{route('order')}}" class="text-warning font-weight-bold font-size-h6">Sales</a>
                                <span class="svg-icon svg-icon-3x svg-icon-warning d-block my-2 font-weight-bold font-size-h3">
                                    Rp {{number_format($this_month_sales,0,",",".")}}
                                </span>
                                <span class="svg-icon svg-icon-3x svg-icon-warning d-block my-2 font-weight-bold">
                                    Net sales bulan ini
                                </span>
                            </div>
                            <div class="col bg-light-primary px-4 py-4 rounded-xl mr-4 mb-4">
                                <a href="{{route('order')}}" class="text-primary font-weight-bold font-size-h6 mt-2">Order</a>
                                <span class="svg-icon svg-icon-3x svg-icon-warning d-block my-2 font-weight-bold font-size-h3">
                                    {{number_format($total_order,0,",",".")}}
                                </span>
                                <span class="svg-icon svg-icon-3x svg-icon-warning d-block my-2 font-weight-bold">
                                    {{number_format($total_new_order,0,",",".")}} Baru, {{number_format($total_process_order,0,",",".")}} Diproses
                                </span>
                            </div>
                            <div class="col bg-light-danger px-4 py-4 rounded-xl mr-4 mb-4">
                                <a href="{{route('complaint')}}" class="text-danger font-weight-bold font-size-h6 mt-2">Komplain</a>
                                <span class="svg-icon svg-icon-3x svg-icon-warning d-block my-2 font-weight-bold font-size-h3">
                                    {{number_format($total_complain,0,",",".")}}
                                </span>
                                <span class="svg-icon svg-icon-3x svg-icon-warning d-block my-2 font-weight-bold">
                                    {{number_format($total_new_complain,0,",",".")}} Baru, {{number_format($total_process_complain,0,",",".")}} Selesai
                                </span>
                            </div>
                            <div class="col bg-light-success px-4 py-4 rounded-xl mr-4 mb-4">
                                <a href="{{route('nego')}}" class="text-success font-weight-bold font-size-h6 mt-2">Negosiasi</a>
                                <span class="svg-icon svg-icon-3x svg-icon-warning d-block my-2 font-weight-bold font-size-h3">
                                    {{number_format($total_negotiation,0,",",".")}}
                                </span>
                                <span class="svg-icon svg-icon-3x svg-icon-warning d-block my-2 font-weight-bold">
                                {{number_format($total_new_negotiation,0,",",".")}} Baru, {{number_format($total_process_negotiation,0,",",".")}} Diterima
                                </span>
                            </div>
                        </div>
                        <div class="row m-0">
                            <div class="col bg-light-warning px-4 py-4 rounded-xl mr-4 mb-4">
                                <a href="{{route('member')}}" class="text-warning font-weight-bold font-size-h6 mt-2">Satdik Aktif</a>
                                <span class="svg-icon svg-icon-3x svg-icon-warning d-block my-2 font-weight-bold font-size-h3">
                                    {{number_format($total_active_satdik,0,",",".")}}
                                </span>
                                <span class="svg-icon svg-icon-3x svg-icon-warning d-block my-2 font-weight-bold">
                                Jumlah Satdik yang terdaftar
                                </span>
                            </div>
                            <div class="col bg-light-primary px-4 py-4 rounded-xl mr-4 mb-4">
                                <a href="{{route('member')}}" class="text-primary font-weight-bold font-size-h6 mt-2">Penyedia Vendor Aktif</a>
                                <span class="svg-icon svg-icon-3x svg-icon-primary d-block my-2 font-weight-bold font-size-h3">
                                    {{number_format($total_active_user_of_vendor,0,",",".")}}
                                </span>
                                <span class="svg-icon svg-icon-3x svg-icon-warning d-block my-2 font-weight-bold">
                                Jumlah penyedia yang memegang vendor <b>{{$vendor_data->name}}</b>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="card-custom card-stretch gutter-b">
                        <!--begin::Header-->
                        <div class="card-header border-0 py-5">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label font-weight-bolder text-dark">Pesanan Terbaru</span>
                            </h3>
                        </div>
                        <!--end::Header-->
                        <!--begin::Body-->
                        <div class="card-body pt-0 pb-3">
                            <div class="tab-content">
                                <!--begin::Table-->
                                <div class="table-responsive">
                                    <table class="table table-vertical-center">
                                        <thead>
                                        <tr class="text-left text-uppercase">
                                            <th style="min-width: 200px" class="pl-7">No. Pesanan</th>
                                            <th style="min-width: 100px">Tujuan Pengiriman</th>
                                            <th style="min-width: 100px">Tanggal</th>
                                            <th style="min-width: 100px">Status</th>
                                            <th style="min-width: 130px" class="text-right">Total (Rp)</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(empty($list_order_checkout_detail->count()))
                                            <tr>
                                                <td colspan="5" class="text-center">Belum ada transaksi</td>
                                            </tr>
                                        @endif
                                        @foreach($list_order_checkout_detail as $order)
                                            <tr>
                                                <td class="pl-7 py-8">
                                                    <a href="{{@route('order-detail', @\Crypt::encryptString(@$order->id))}}" class="text-dark-75 font-weight-bolder d-block font-size-lg">{{ @$order->order_no }}</a>
                                                </td>
                                                <td>
                                                    <span class="text-dark-75 font-weight-bolder d-block font-size-lg">
                                                        {{ @ucfirst(@$order->order->delivery_address) }},<br>
                                                        Kab./Kota: {{ @$order->order->region->kota??'-' }}<br>
                                                        Prov: {{ @$order->order->region->province??'-' }},<br>
                                                        Kode Pos: {{@$order->order->delivery_postal_code}}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="text-dark-75 font-weight-bolder d-block font-size-lg">{{@(!empty($order->created_at)?date('d M Y H:i:s',strtotime(\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $order->created_at, 'UTC')->setTimezone('Asia/Jakarta'))):"-")??'-'}}</span>
                                                </td>
                                                <td>
                                                    <span class="text-dark-75 font-weight-bolder d-block font-size-lg">{{$order->status}}</span>
                                                </td>
                                                <td class="text-right">
                                                    <span class="text-dark-75 font-weight-bolder d-block font-size-lg">{{number_format($order->total_price,0,",",".")}}</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <!--end::Table-->
                            </div>
                        </div>
                        <!--end::Body-->
                    </div>
                </div>
            </div>
            <!--end::Container-->
        </div>
        <!--end::Entry-->
    </div>
@endsection
@section('admin_js')

@endsection

@extends('includes.layout_admin')

@section('custom_title','Transaksi')

@section('admin_css')
    <style>
        .table-delivery-info td{
            border-top: none !important;
        }
        .centerize{
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
    </style>
@endsection
@include('component/loading_modal')
@section('pages')
<!--Loading-->
<div class="content d-flex flex-column flex-column-fluid " id="kt_content">
    <!--begin::Subheader-->
    <div class="subheader py-2 py-lg-6 subheader-solid " id="kt_subheader">
        <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <!--begin::Info-->
            <div class="d-flex align-items-center flex-wrap mr-1">
                <!--begin::Page Heading-->
                <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <!--begin::Breadcrumb-->
                    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
                        <li class="breadcrumb-item text-muted">
                            <a href="{{url()->current()}}" class="text-muted">@yield('custom_title')</a>
                        </li>
                        <li class="breadcrumb-item text-muted">
                            <a href="{{url()->current()}}" class="text-muted">Detail Transaksi</a>
                        </li>
                    </ul>
                    <!--end::Breadcrumb-->
                </div>
                <!--end::Page Heading-->
            </div>
            <!--end::Info-->
            <!--begin::Toolbar-->
            <!--end::Toolbar-->
        </div>
    </div>
    <!--end::Subheader-->

    <!--begin::Content-->
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Entry-->
        <div class="d-flex flex-column-fluid">
            <!--begin::Container-->
            <div class="container">
                <!--begin::Page Layout-->
                <div class="row">
                    <!--begin::Aside-->
                    <div id="summary_order" class="col-sm-12 col-lg-4" id="kt_profile_aside">
                    @include('pages/order/summary')
                    </div>
                    <!--end::Aside-->
                    <!--begin::Layout-->
                    <div class="col-sm-12 col-lg-8 ">
                        <!--begin::Card-->
                        <div class="card card-custom gutter-b">
                            <div class="card-body p-0">
                                <!-- begin: Invoice-->
                                <!-- begin: Invoice header-->
                                <div class="row justify-content-center py-4 px-4 py-md-6 px-md-0">
                                    <div class="col-md-10">
                                        <div class="d-flex justify-content-between pt-6 pb-6">
                                            <div class="d-flex flex-column flex-root pl-2">
                                                <h4 class="font-weight-normal mb-4">Detail Transaksi</h4>
                                                <h3 class="font-weight-bolder">{{ @$selected->order_no ?? '-' }}</h3>
                                                <div class="row">
                                                    <div class="col-4">
                                                        <span class="label label-info label-inline font-weight-bolder mb-4">{{$selected->tax_paidby_type == "VENDOR" ? "Pajak dibayar Penyedia" : "Pajak dibayar Pembeli"}}</span>
                                                    </div>
                                                </div>
                                                @if(count($selected->bast) > 0)
                                                    @if($selected->bast[count($selected->bast)-1]->status != "REJECTED" && intval($selected->bast[count($selected->bast)-1]->penalty_amount) > 0)
                                                <h5 class="font-weight-bold">DENDA : <b class="text-danger">Rp. {{number_format($selected->bast[count($selected->bast)-1]->penalty_amount)}}</b></h5>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                        <div class="border-bottom w-100"></div>
                                        <div class="d-flex justify-content-between pt-6">
                                            <div class="d-flex flex-column flex-root pl-2">
                                                <span class="font-weight-bolder mb-2">TANGGAL PESAN</span>
                                                <span class="opacity-70">{{@\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $selected->order->order_date, 'UTC')->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s')}}</span>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between pt-6">
                                            <div class="d-flex flex-column flex-root pl-2">
                                                <span class="font-weight-bolder mb-2">PEMBELI</span>
                                                <span class="opacity-70">
                                                    <b>{{@$selected->customer->name ?? '-'}}</b><br/>
                                                    {{@$selected->customer->region->kota ?? '-'}},
                                                    {{@$selected->customer->region->province ?? '-'}}<br/>
                                                    {{@$selected->customer->email ?? '-'}}<br/>
                                                    {{@$selected->customer->phone ?? '-'}}
                                                </span>
                                            </div>
                                            <div class="d-flex flex-column flex-root pl-10">
                                                <span class="font-weight-bolder mb-2">DIKIRIM KE</span>
                                                <span class="opacity-70">
                                                    <b>{{@$selected->order->delivery_receiver_name ?? '-'}}</b><br/>
                                                    {{@$selected->order->delivery_address ?? '-'}}<br/>
                                                    {{@$selected->order->district->name ?? '-'}},
                                                    {{@$selected->order->region->kota ?? '-'}},
                                                    {{@$selected->order->region->province ?? '-'}}
                                                    ({{@$selected->order->delivery_postal_code ?? '-'}})<br/>
                                                    {{@$selected->order->delivery_receiver_phone ?? '-'}}
                                                </span>
                                            </div>
                                        </div>
                                        <table class="table table-delivery-info font-size-sm py-4">
                                            <thead>
                                            <tr>
                                                <td colspan="2">Informasi Pengiriman</td>
                                            </tr>
                                            </thead>
                                            <tbody class="opacity-70">
                                                <tr>
                                                    <td><b>Jasa Kurir Pengiriman</b></td>
                                                    <td>{{@$selected->shipment_service->provider->name}}</td>
                                                </tr>
                                                <tr>
                                                    <td><b>Dikirimkan kepada</b></td>
                                                    <td> <b>{{@$selected->order->delivery_receiver_name ?? '-'}}</b><br/>
                                                        {{@$selected->order->delivery_address ?? '-'}}<br/>
                                                        {{@$selected->order->district->name ?? '-'}},
                                                        {{@$selected->order->region->kota ?? '-'}},
                                                        {{@$selected->order->region->province ?? '-'}}
                                                        ({{@$selected->order->delivery_postal_code ?? '-'}})<br/>
                                                        {{@$selected->order->delivery_receiver_phone ?? '-'}}</td>
                                                </tr>
                                                <tr>
                                                    <td><b>Nomor Resi</b></td>
                                                    <td id="no_resi_detail">{{ @$selected->order_delivery->no_resi ?? '-' }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!-- end: Invoice header-->
                                <!-- begin: Invoice body-->
                                <div class="row justify-content-center py-4 px-4 py-md-6 px-md-0">
                                    <div class="col-md-10">
                                        <div class="table-responsive">
                                            <table class="table font-size-sm">
                                                <thead>
                                                    <tr>
                                                        <th class="pl-0 font-weight-bold text-muted text-uppercase" width="250px">Produk</th>
                                                        <th class="text-right font-weight-bold text-muted text-uppercase">Qty</th>
                                                        <th class="text-right font-weight-bold text-muted text-uppercase">Harga Satuan</th>
                                                        <th class="text-right pr-0 font-weight-bold text-muted text-uppercase">Subtotal</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($selected->order_item as $item)
                                                    <tr class="font-weight-boldest">
                                                        <td class="border-0 pl-0 pt-7 d-flex">
                                                            <div class="d-flex flex-column flex-root">
                                                                <div class="d-flex align-items-center">
                                                                    <!--begin::Symbol-->
                                                                    <div class="symbol symbol-40 flex-shrink-0 mr-4 bg-light">
                                                                        <div class="symbol-label" style="background-image: url('{{asset($url['disk']->url('').$item->product->image)}}')"></div>
                                                                    </div>
                                                                    <!--end::Symbol-->
                                                                    {{$item->product->name}}
                                                                </div>
                                                                @if($item->user_cart != null)
                                                                <div class="mt-4">
                                                                    <button data-index="{{Crypt::encryptString($item->user_cart->id)}}" class="btn btn-sm btn-light-primary btn-nego">Lihat Nego</button>
                                                                </div>
                                                                @endif
                                                            </div>
                                                        </td>
                                                        <td class="text-right pt-10">{{$item->quantity}}</td>
                                                        <td class="text-right pt-10">Rp. {{number_format(($item->price-$item->item_tax),0,",",".")}}</td>
                                                        <td class="text-primary pr-0 pt-10 text-right">RP. {{number_format($item->quantity*$item->price,0,",",".")}}</td>
                                                    </tr>
                                                    @endforeach
                                                    <tr>
                                                        <td colspan="2" class="text-right pt-7 align-middle">Subtotal <br>(termasuk pajak)</td>
                                                        <td colspan="2" class="font-weight-boldest text-primary pr-0 pt-7 text-right align-middle">Rp. {{number_format($selected->total_price,0,",",".")}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" class="text-right pt-7 align-middle">Pajak</td>
                                                        <td colspan="2" class="font-weight-boldest text-primary pr-0 pt-7 text-right align-middle">Rp. {{number_format($selected->total_tax,0,",",".")}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" class="text-right pt-7 align-middle">Ongkir</td>
                                                        <td colspan="2" class="font-weight-boldest text-primary pr-0 pt-7 text-right align-middle">Rp. {{number_format($selected->delivery_fee,0,",",".")}}</td>
                                                    </tr>
                                                    <tr class="font-weight-boldest">
                                                        <td colspan="2" class="text-right pt-7 align-middle">Total</td>
                                                        <td colspan="2" class="text-primary pr-0 pt-7 text-right align-middle font-size-h5">Rp. {{number_format($selected->total_price+$selected->delivery_fee,0,",",".")}}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <!-- end: Invoice body-->
                                <!-- begin: Invoice footer-->
                                <div class="row justify-content-center bg-gray-100 py-8 px-8 py-md-10 px-md-0 mx-0">
                                    <div class="col-md-10">
                                        <span class="font-weight-bold text-uppercase">Riwayat Transaksi</span>

                                        <div class="example-preview">
                                            <!--begin::Timeline-->
                                            <div class="timeline timeline-6 mt-3">
                                                @foreach($logs as $log)
                                                <div class="timeline-item align-items-start">
                                                    <!--begin::Label-->
                                                    <div class="timeline-label font-weight-bolder text-dark-75 font-size-lg" style="width:75px">
                                                        @if(\Carbon\Carbon::parse(\Carbon\Carbon::now())->diffInDays(date('Y-m-d H:i:s',(((int)$log->timestamp->__toString())/1000))) < 1)
                                                            {{@\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s',(((int)$log->timestamp->__toString())/1000)), 'UTC')->setTimezone('Asia/Jakarta')->format('H:i:s')}}
                                                        @else
                                                            {{@\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s',(((int)$log->timestamp->__toString())/1000)), 'UTC')->setTimezone('Asia/Jakarta')->diffForHumans()}}
                                                        @endif
                                                    </div>
                                                    <!--end::Label-->
                                                    <!--begin::Badge-->
                                                    <div class="timeline-badge">
                                                        <i class="fa fa-genderless text-primary icon-xl"></i>
                                                    </div>
                                                    <!--end::Badge-->
                                                    <!--begin::Text-->
                                                    <div class="timeline-content font-weight-bolder font-size-lg text-dark-75 pl-3">{{$log->event_desc}}</div>
                                                    <!--end::Text-->
                                                </div>
                                                @endforeach
                                            </div>
                                            <!--end::Timeline-->
                                        </div>
                                    </div>
                                </div>
                                <!-- end: Invoice footer-->
                                <!-- end: Invoice-->
                            </div>
                        </div>
                        <!--end::Card-->
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
    @include('component/loading')
    @include('pages/nego/modal')
    @include('pages/order/modal_receive')
    @include('pages/order/modal_edit')
</div>
@endsection
@section('admin_js')
    <script src="{{ asset('js/pages/order/detail.js') }}?ver=0011"></script>
@endsection


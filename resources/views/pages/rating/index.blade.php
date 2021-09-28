@extends('includes.layout_admin')

@section('custom_title','Penilaian')

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
                                <a href="{{route('dashboard')}}" class="text-muted">Home</a>
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
            </div>
        </div>
        <!--end::Subheader-->
        <!--begin::Entry-->
        <div class="d-flex flex-column-fluid">
            <!--begin::Container-->
            <div class="container">
                <div class="card">
                    <div class="card-custom card-stretch gutter-b mt-10">
                        <!--begin::Header-->
                        <div class="card-header border-0 py-5">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label font-weight-bolder text-dark">Penilaian Untuk Vendor</span>
                            </h3>
                            <div class="row m-0 card-body">
                                <div class="col rounded-xl">
                                    <span
                                        class="svg-icon svg-icon-3x svg-icon-warning d-block my-2 font-weight-bold font-size-h6">
                                        Total Penilaian
                                    </span>
                                    <span class="svg-icon svg-icon-3x svg-icon-warning d-block my-2 font-weight-bold">
                                        @if($rating_vendor)
                                            <img class="align-middle"
                                                 src="{{ asset('assets/media/logos/stars-'.round($rating_vendor).'.png')}}" alt="image"
                                                 style="height: 16px;"/>
                                        @endif
                                        <span style="font-size: 11px;">({{ $rating_vendor }}/5)</span>
                                    </span>
                                </div>
                                <div class="col rounded-xl">
                                    <span
                                        class="svg-icon svg-icon-3x svg-icon-warning d-block my-2 font-weight-bold font-size-h6">
                                        Bintang 1
                                    </span>
                                    <span class="svg-icon svg-icon-3x svg-icon-warning d-block my-2 font-weight-bold">
                                        {{ $start_1_count_vendor }}
                                    </span>
                                </div>
                                <div class="col rounded-xl">
                                    <span
                                        class="svg-icon svg-icon-3x svg-icon-warning d-block my-2 font-weight-bold font-size-h6">
                                        Bintang 2
                                    </span>
                                    <span class="svg-icon svg-icon-3x svg-icon-warning d-block my-2 font-weight-bold">
                                        {{ $start_2_count_vendor }}
                                    </span>
                                </div>
                                <div class="col rounded-xl">
                                    <span
                                        class="svg-icon svg-icon-3x svg-icon-warning d-block my-2 font-weight-bold font-size-h6">
                                        Bintang 3
                                    </span>
                                    <span class="svg-icon svg-icon-3x svg-icon-warning d-block my-2 font-weight-bold">
                                        {{ $start_3_count_vendor }}
                                    </span>
                                </div>
                                <div class="col rounded-xl">
                                    <span
                                        class="svg-icon svg-icon-3x svg-icon-warning d-block my-2 font-weight-bold font-size-h6">
                                        Bintang 4
                                    </span>
                                    <span class="svg-icon svg-icon-3x svg-icon-warning d-block my-2 font-weight-bold">
                                        {{ $start_4_count_vendor }}
                                    </span>
                                </div>
                                <div class="col rounded-xl">
                                    <span
                                        class="svg-icon svg-icon-3x svg-icon-warning d-block my-2 font-weight-bold font-size-h6">
                                        Bintang 5
                                    </span>
                                    <span class="svg-icon svg-icon-3x svg-icon-warning d-block my-2 font-weight-bold">
                                        {{ $start_5_count_vendor }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <!--end::Header-->
                        <!--begin::Body-->
                        <div class="card-body pt-0 pb-3">
                            <div class="tab-content">
                                <!--begin::Table-->
                                <table class="table table-vertical-center" id="kt_datatable">
                                    <thead>
                                    <tr class="text-left text-uppercase">
                                        <th class="pl-7">No.</th>
                                        <th>No. Transaksi</th>
                                        <th>Tanggal</th>
                                        <th>Penilai</th>
                                        <th>Nilai</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach(@(object)@$rating_list_vendor as $rating_obj_vendor)
                                        <tr>
                                            <td class="pl-7">
                                                <span
                                                    class="text-dark-75 font-weight-bolder d-block font-size-lg">{{$loop->iteration}}</span>
                                            </td>
                                            <td>
                                                <a  href="{{route('order-detail',@\Crypt::encryptString(@$rating_obj_vendor->order_detail->id))}}"
                                                    class="text-dark-75 font-weight-bolder d-block font-size-lg">{{@$rating_obj_vendor->order_detail->order_no??'Tidak Ada No. Pesanan'}}</a>
                                            </td>
                                            <td>
                                                <span class="text-dark-75 font-weight-bolder d-block font-size-lg">
                                                    {{@(!empty(@$rating_obj_vendor->created_at)?date('d M Y H:i:s',strtotime(\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', @$rating_obj_vendor->created_at, 'UTC')->setTimezone('Asia/Jakarta'))):"-")??'-'}}
                                                </span>
                                            </td>
                                            <td>
                                                <span
                                                    class="text-dark-75 font-weight-bolder d-block font-size-lg">{{@$rating_obj_vendor->order_detail->customer->school->school_name}}</span>
                                                <span
                                                    class="text-muted font-weight-bold">{{@$rating_obj_vendor->order_detail->customer->name}}</span>
                                                {{@$rating_obj_vendor->customer}}
                                            </td>
                                            <td>
                                                @if(@$rating_obj_vendor->rating)
                                                    <img
                                                        src="{{ asset('assets/media/logos/stars-'.@$rating_obj_vendor->rating.'.png')}}"
                                                        alt="image" style="height: 22px"/>
                                                @endif
                                                    ({{ @$rating_obj_vendor->rating }}/5)
                                                <span class="text-muted font-weight-bold d-block font-size-sm">&ldquo;{{@$rating_obj_vendor->note??"Tidak ada pesan"}}&rdquo;</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <!--end::Table-->
                            </div>
                        </div>
                        <!--end::Body-->
                    </div>
                    <hr>
                    <div class="card-custom card-stretch gutter-b mt-10">
                        <!--begin::Header-->
                        <div class="card-header border-0 py-5">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label font-weight-bolder text-dark">Penilaian Untuk Produk</span>
                            </h3>
                            <div class="row m-0 card-body">
                                <div class="col rounded-xl">
                                    <span
                                        class="svg-icon svg-icon-3x svg-icon-warning d-block my-2 font-weight-bold font-size-h6">
                                        Total Penilaian
                                    </span>
                                    <span class="svg-icon svg-icon-3x svg-icon-warning d-block my-2 font-weight-bold">
                                        @if($rating)
                                            <img class="align-middle"
                                                 src="{{ asset('assets/media/logos/stars-'.round($rating).'.png')}}" alt="image"
                                                 style="height: 16px;"/>
                                        @endif
                                        <span style="font-size: 11px;">({{ $rating }}/5)</span>
                                    </span>
                                </div>
                                <div class="col rounded-xl">
                                    <span
                                        class="svg-icon svg-icon-3x svg-icon-warning d-block my-2 font-weight-bold font-size-h6">
                                        Bintang 1
                                    </span>
                                    <span class="svg-icon svg-icon-3x svg-icon-warning d-block my-2 font-weight-bold">
                                        {{ $start_1_count }}
                                    </span>
                                </div>
                                <div class="col rounded-xl">
                                    <span
                                        class="svg-icon svg-icon-3x svg-icon-warning d-block my-2 font-weight-bold font-size-h6">
                                        Bintang 2
                                    </span>
                                    <span class="svg-icon svg-icon-3x svg-icon-warning d-block my-2 font-weight-bold">
                                        {{ $start_2_count }}
                                    </span>
                                </div>
                                <div class="col rounded-xl">
                                    <span
                                        class="svg-icon svg-icon-3x svg-icon-warning d-block my-2 font-weight-bold font-size-h6">
                                        Bintang 3
                                    </span>
                                    <span class="svg-icon svg-icon-3x svg-icon-warning d-block my-2 font-weight-bold">
                                        {{ $start_3_count }}
                                    </span>
                                </div>
                                <div class="col rounded-xl">
                                    <span
                                        class="svg-icon svg-icon-3x svg-icon-warning d-block my-2 font-weight-bold font-size-h6">
                                        Bintang 4
                                    </span>
                                    <span class="svg-icon svg-icon-3x svg-icon-warning d-block my-2 font-weight-bold">
                                        {{ $start_4_count }}
                                    </span>
                                </div>
                                <div class="col rounded-xl">
                                    <span
                                        class="svg-icon svg-icon-3x svg-icon-warning d-block my-2 font-weight-bold font-size-h6">
                                        Bintang 5
                                    </span>
                                    <span class="svg-icon svg-icon-3x svg-icon-warning d-block my-2 font-weight-bold">
                                        {{ $start_5_count }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <!--end::Header-->
                        <!--begin::Body-->
                        <div class="card-body pt-0 pb-3">
                            <div class="tab-content">
                                <!--begin::Table-->
                                <table class="table table-vertical-center" id="kt_datatable2">
                                    <thead>
                                    <tr class="text-left text-uppercase">
                                        <th class="pl-7">No.</th>
                                        <th>No. Transaksi</th>
                                        <th>Nama Produk</th>
                                        <th>Penilai</th>
                                        <th>Nilai</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach(@(object)@$rating_list as $rating_obj)
                                        <tr>
                                            <td class="pl-7">
                                                <span
                                                    class="text-dark-75 font-weight-bolder d-block font-size-lg">{{$loop->iteration}}</span>
                                            </td>
                                            <td>
                                                <span
                                                    class="text-dark-75 font-weight-bolder d-block font-size-lg">{{@$rating_obj->order_detail->order_no??'Tidak Ada No. Pesanan'}}</span>
                                                <a class="text-muted font-weight-bold"
                                                   href="{{route('order-detail',@\Crypt::encryptString(@$rating_obj->order_detail->id))}}">
                                                    {{@(!empty(@$rating_obj->created_at)?date('d M Y H:i:s',strtotime(\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', @$rating_obj->created_at, 'UTC')->setTimezone('Asia/Jakarta'))):"-")??'-'}}
                                                    &bull; Lihat Transaksi
                                                </a>
                                            </td>
                                            <td>
                                                <span
                                                    class="text-dark-75 font-weight-bolder d-block font-size-lg">{{@$rating_obj->product->name}}</span>
                                                <span
                                                    class="text-muted font-weight-bold">Rp {{number_format(@$rating_obj->product->listing_price,0,',','.')}}	</span>
                                            </td>
                                            <td>
                                                <span
                                                    class="text-dark-75 font-weight-bolder d-block font-size-lg">{{@$rating_obj->order_detail->customer->school->school_name}}</span>
                                                <span
                                                    class="text-muted font-weight-bold">{{@$rating_obj->order_detail->customer->name}}</span>
                                                {{@$rating_obj->customer}}
                                            </td>
                                            <td>
                                                @if(@$rating_obj->rating)
                                                    <img
                                                        src="{{ asset('assets/media/logos/stars-'.@$rating_obj->rating.'.png')}}"
                                                        alt="image" style="height: 22px"/>
                                                @endif
                                                    ({{ @$rating_obj->rating }}/5)
                                                <span class="text-muted font-weight-bold d-block font-size-sm">&ldquo;{{@$rating_obj->note??'Tidak ada pesan'}}&rdquo;</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
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
    <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('js/pages/rating/index.js') }}?ver=00005"></script>
@endsection

@extends('master')
@section('custom_title','Negosiasi Produk')
@section('content')
<div id="nego_print" class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container">
        <div class="divHeader row">
            <div class="col-12 text-right">
                <button id="btn_print" class="btn btn-primary">Print</button>
                <button id="btn_close" class="btn btn-secondary">Close</button>
            </div>
        </div>

        <!-- begin::Card-->
        <div class="card card-custom overflow-hidden">
            <div class="card-body p-0">
                <!-- begin: Invoice-->
                <!-- begin: Invoice header-->
                <div class="row justify-content-center py-5 px-5 py-md-10 px-md-0">
                    <div class="col-md-9">
                        <div class="d-flex justify-content-between flex-column flex-row">
                            <div class="d-flex flex-column align-items-end px-0">
                                <!--begin::Logo-->
                                <a class="mb-5">
                                    <img src="{{asset('assets/media/logos/logo-dark.png')}}" alt="" />
                                </a>
                                <!--end::Logo-->
                            </div>
                            <h1 class="display-4 font-weight-boldest mb-10">Negosiasi Produk</h1>
                        </div>
                        <div>
                            <span class="d-flex flex-row pb-5 pb-md-5 flex-column opacity-70">
                                <h2>{{ $nego['product_name'] }}</h2>
                            </span>
                        </div>
                        <div class="border-bottom w-100"></div>
                        <div class="d-flex justify-content-between pt-6">
                            <div class="d-flex flex-column flex-root">
                                <span class="opacity-70 mb-2">Produk</span>
                                <img width="150px" src="{{ $url['disk']->url('').$nego['product_image'] }}" />
                                <span class="font-weight-bolder">{{ $nego['product_name'] }}</span>
                            </div>
                            <div class="d-flex flex-column flex-root">
                                <span class="opacity-70 mb-2 text-center">Jumlah</span>
                                <span class="font-weight-bolder text-center">{{$nego['quantity']}}</span>
                            </div>
                            <div class="d-flex flex-column flex-root">
                                <span class="opacity-70 mb-2">Tanggal Kadar Luasa</span>
                                <span class="font-weight-bolder">{{$nego['offer_date']}}</span>
                            </div>
                            <div class="d-flex flex-column flex-root">
                                <span class="opacity-70 mb-2">Harga Nego</span>
                                <span class="font-weight-bolder">{{$nego['nego_price']}}</span>
                            </div>
                            <div class="d-flex flex-column flex-root">
                                <span class="opacity-70 mb-2">Offer Counter</span>
                                <span class="font-weight-bolder">{{$nego['offer_price']}}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end: Invoice header-->
                <!-- begin: Invoice body-->
                <div class="row justify-content-center py-5 px-8 py-md-8 px-md-0">
                    <div class="col-md-9">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th class="pl-0 font-weight-bold text-muted text-uppercase">Tanggal Nego</th>
                                    <th class="text-right font-weight-bold text-muted text-uppercase">Harga Nego</th>
                                    <th class="text-right font-weight-bold text-muted text-uppercase">Note</th>
                                    <th class="text-right pr-0 font-weight-bold text-muted text-uppercase">Pembuat</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($nego_detail as $n)
                                <tr class="font-weight-boldest">
                                    <td class="pl-0 pt-7">{{ $n['date'] }}</td>
                                    <td class="text-right pt-7">{{ $n['nego_price'] }}</td>
                                    <td class="text-right pt-7">{{ $n['nego_note'] }}</td>
                                    <td class="text-primary pr-0 pt-7 text-right">{{ $n['name'] }}</td>
                                </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- end: Invoice body-->
                <!-- begin: Invoice action-->
{{--                <div class="row justify-content-center py-8 px-8 py-md-10 px-md-0">--}}
{{--                    <div class="col-md-9">--}}
{{--                        <div class="d-flex flex-column flex-root">--}}
{{--                            <span class="font-weight-bolder mb-2">Dokumen ini dibuat secara otomatis melalui URL</span>--}}
{{--                            <span class="opacity-70">{{ url()->current() }}</span>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
                <!-- end: Invoice action-->
                <!-- end: Invoice-->
            </div>
        </div>
        <!-- end::Card-->
    </div>
    <!--end::Container-->
</div>
@endsection
@section('admin_js')
    <script type="text/javascript">
        $(document).ready(function(){
            window.print();
            // setTimeout(function () {
            //     window.close();
            // }, 3000);

            $('#btn_print').on('click',function(){
                window.print();
            })

            $('#btn_close').on('click',function(){
                window.close();
            })
        });
    </script>
@endsection

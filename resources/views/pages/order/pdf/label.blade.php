@extends('master')
@section('custom_title','Label JNE')
@section('admin_css')
    <style>
        body{
            line-height: 1.7;
        }
    </style>

@endsection
@section('content')
    <div id="label_pdf" class="d-flex flex-column-fluid">
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
                    <div class="row justify-content-center py-8 px-8 py-md-27 px-md-0">
                        <div class="col-md-9">
                            <div class="row text-center">
                                <div class="col-3 border border-dark border-right-0 border-bottom-0 pt-8 pb-4"><img style="max-width:120px" src="{{asset('assets/media/logos/logo_jne.png')}}" alt="" /></div>
                                <div class="col-6 border border-dark border-right-0 border-bottom-0" style="display: flex;align-items: center;justify-content: center;">
                                    <span class="font-size-h1 font-weight-boldest">{{@$order->order_delivery->no_resi ?? "-"}}</span>
                                </div>
                                <div class="col-3 border border-dark border-bottom-0 py-4"><img style="max-width:125px" src="{{asset('assets/media/logos/logo-dark.png')}}" alt="" /></div>
                            </div>
                            <div class="row">
                                <div class="col-3 border border-dark border-right-0 border-bottom-0 font-weight-bolder" style="display: flex;align-items: center;">Tracking No.</div>
                                <div class="col-9 border border-dark border-left-0 border-bottom-0 py-8" style="display: flex;align-items: center;justify-content: center;">{!! DNS1D::getBarcodeSVG($order->order_delivery->no_resi, 'C128',4,100,'black',false) !!}</div>
                            </div>
                            <div class="row">
                                <div class="col-6 border border-dark border-right-0 border-bottom-0 text-center font-weight-boldest font-size-h3">{{@$order->order_delivery->origin_code ?? "-"}}</div>
                                <div class="col-6 border border-dark border-bottom-0 text-center font-weight-boldest font-size-h3">{{@$order->order_delivery->destination_code ?? "-"}}</div>
                            </div>
                            <div class="row">
                                <div class="col-3 border border-dark border-right-0 border-bottom-0 text-center font-weight-boldest font-size-h3">{{@$order->shipment_service->code ?? "-"}}</div>
                                <div class="col-3 border border-dark border-right-0 border-bottom-0 text-center font-weight-boldest font-size-h3">{{$order->order_delivery->is_cod ? "Non COD" : "COD"}}</div>
                                <div class="col-6 border border-dark border-bottom-0 text-center font-weight-boldest font-size-h3">Rp. {{number_format(($order->order_delivery->total_price),0,",",".")}}</div>
                            </div>
                            <div class="row">
                                <div class="col-5 border border-dark border-right-0 border-bottom-0 font-weight-bolder" style="display: flex;align-items: center;">
                                    <div>
                                        <span>Postcode: </span><br>
                                        <h2>{{@$order->order_delivery->branch_code ?? "-"}}</h2>
                                    </div>
                                </div>
                                <div class="col-7 border border-dark border-left-0 border-bottom-0 py-8" style="display: flex;align-items: center;justify-content: center;">
                                    {!! DNS1D::getBarcodeSVG($order->order_delivery->branch_code, 'C128',3,100,'black',false) !!}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6 border border-dark border-right-0 border-bottom-0"><span class="font-weight-bolder">Sender:</span> <br>
                                    {{@$order->vendor->name ?? "-"}}<br>
                                    {{@$order->vendor->address ?? "-"}} - {{@$order->vendor->district->name ?? "-"}}<br>
                                    {{@$order->vendor->region->kota}} - {{@$order->vendor->address_zip_code ?? "-"}}<br></div>
                                <div class="col-6 border border-dark border-bottom-0"><span class="font-weight-bolder">Recipient:</span> <br>
                                    {{@$order->customer->jabatan ?? "-"}}, {{@$order->customer->school->school_name ?? "-"}}<br>
                                    {{@$order->order->delivery_address ?? "-"}}<br>
                                    {{@$order->order->region->province ?? "-"}}<br>
                                    {{@$order->order->region->kota ?? "-"}}<br>
                                    {{@$order->order->district->name ?? "-"}}<br>
                                    {{@$order->order->delivery_postal_code}}<br>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 border border-dark border-bottom-0 py-8" style="display: flex;align-items: center;justify-content: center;">{!! DNS1D::getBarcodeSVG(str_replace('/','',$order->order_no), 'C128',3,100,'black',false) !!}</div>
                            </div>
                            <div class="row text-center">
                                <div class="col-12 border border-dark border-bottom-0 font-weight-boldest">Package ID : {{str_replace('/','',$order->order_no)}}</div>
                            </div>
                            <div class="row">
                                <div class="col-12 border border-dark">
                                    @foreach($order->order_item as $item)
                                    <div class="font-weight-bolder">#Items: {{($loop->index+1)}}</div>
                                    <div>Item List: {{$item->product->name}}</div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
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

@extends('master')
@section('custom_title','Purchase Order')
@section('admin_css')
    <style type="text/css">
        body{
            line-height: 1.7;
        }
        .table-delivery-info td{
            border-top: none !important;
        }
        @media print {
            .bg-header {
                background: #0daccc !important;
                -webkit-print-color-adjust: exact !important;
            }
        }
        .table-pdf td{
            padding:10px;
        }
        .table-pdf th{
            padding:10px;
        }

        @media screen {
            div.divFooter {
                display: none;
            }
        }
        @media print {
            div.divFooter {
                position: fixed;
                bottom: 5px;
                right: 5px;
            }
        }
    </style>
@endsection

@section('content')
    <div class="d-flex flex-column-fluid">
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
                <div class="card-body">
                    <!-- begin: Invoice-->
                    <!-- begin: Invoice header-->
                    <div class="row justify-content-center py-8 px-8 py-md-27 px-md-0">
                        <div class="col-md-10 pb-30">
                            <div class="d-flex justify-content-between pb-5 flex-column flex-md-row">
                                <a href="#" class="mb-5">
                                    <img src="{{asset('assets/media/logos/logo-dark.png')}}" alt="" />
                                </a>
                                <div class="d-flex flex-column align-items-md-end px-0 mt-5">
                                    <h1 class="font-weight-boldest">Purchase Order</h1>
                                </div>
                            </div>
                            <table class="mb-2">
                                <tr>
                                    <td>Tanggal Pembelian</td>
                                    <td> : {{@\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $order->created_at, 'UTC')->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s') ?? "-"}}</td>
                                </tr>
                                <tr>
                                    <td>Nomor PO</td>
                                    <td> : {{@$order->po_no ?? "-"}}</td>
                                </tr>
                                <tr>
                                    <td>Disepakati Satdik Tanggal </td>
                                    <td> : {{($order->member_confirm_date) ? \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $order->member_confirm_date, 'UTC')->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s') : "-"}}</td>
                                </tr>
                                <tr>
                                    <td>Disepakati Penyedia Tanggal </td>
                                    <td> : {{($order->seller_confirm_date) ? \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $order->seller_confirm_date, 'UTC')->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s') : "-" }}</td>
                                </tr>
                            </table>
                            <div class="d-flex justify-content-between pt-6">
                                <div class="d-flex flex-column flex-root">
                                    <span class="font-weight-bolder">Satdik</span>
                                    <span>{{@$order->customer->school->school_name ?? "-"}}</span>
                                    <span>{{@$order->customer->name ?? "-"}}</span>
                                    <span>Phone : {{@$order->customer->school->phone ?? "-"}}</span>
                                    <span>NPWP : {{@$order->customer->school->npwp ?? "-"}}</span>
                                    <div class="mt-5 font-weight-bolder">Alamat Pengiriman</div>
                                    <span>{{@$order->order->delivery_address ?? "-"}}</span>
                                    <span>{{@$order->order->district->name ?? "-"}}, {{@$order->order->region->kota ?? "-"}}</span>
                                    <span>{{@$order->order->region->province ?? "-"}} {{@$order->delivery_postal_code ?? "-"}}</span>
                                </div>
                                <div class="d-flex flex-column flex-root align-items-end">
                                    <span class="font-weight-bolder">Penyedia</span>
                                    <span>{{@$order->vendor->name ?? "-"}}</span>
                                    <span>{{@$order->vendor->address ?? "-"}}</span>
                                    <span>{{@$order->vendor->district->name ?? "-"}}, {{@$order->vendor->region->kota ?? "-"}},</span>
                                    <span>{{@$order->vendor->region->province ?? "-"}} {{@$order->vendor->address_zip_code ?? "-"}}</span>
                                    <span>Phone : {{@$order->vendor->phone1 ?? "-"}}</span>
                                </div>
                            </div>
                            @foreach($order->order_item as $item)
                                @if($loop->first)
                                    <div>
                                        <table class="table-pdf mt-4" style="width: 100% !important;">
                                            <thead>
                                            <tr class="text-white" style="background: #0daccc !important; -webkit-print-color-adjust: exact !important;">
                                                <th width="350px">Nama Produk</th>
                                                <th width="229px">Jml.</th>
                                                <th>Harga</th>
                                                <th class="text-right">Total</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @endif


                                            <tr>
                                                <td class="font-weight-bolder">{{$item->product->name}}</td>
                                                <td>{{$item->quantity}}</td>
                                                <td>Rp. {{number_format($item->price,0,",",".")}}</td>
                                                <td class="text-right">Rp. {{number_format($item->price*$item->quantity,0,",",".")}}</td>
                                            </tr>
                                            @if($loop->iteration % 16 == 0)
                                            </tbody>
                                        </table>
                                    </div>
                                    <div style="page-break-before:always;">
                                        <table class="table-pdf mt-4" style="width: 100% !important;">
                                            <thead>
                                            <tr class="text-white" style="background: #0daccc !important; -webkit-print-color-adjust: exact !important;">
                                                <th width="350px">Nama Produk</th>
                                                <th>Jml.</th>
                                                <th>Harga</th>
                                                <th class="text-right">Total</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @endif
                                            @if($loop->last)
                                                <!-- Subtotal -->
                                                @if($order->tax_paidby_type == "VENDOR")
                                                <tr>
                                                    <td></td>
                                                    <td>Subtotal sudah termasuk pajak</td>
                                                    <td colspan="2" class="text-right">Rp. {{number_format($order->total_price,0,",",".")}}</td>
                                                </tr>
                                                @else
                                                <tr>
                                                    <td></td>
                                                    <td>Subtotal</td>
                                                    <td colspan="2" class="text-right">Rp. {{number_format($order->total_price - $order->total_tax_buyer,0,",",".")}}</td>
                                                </tr>
                                                @endif
                                                <!-- Subtotal -->
                                                <tr>
                                                    <td></td>
                                                    <td>Biaya Kirim</td>
                                                    <td colspan="2" class="text-right">Rp. {{number_format($order->delivery_fee,0,",",".")}}</td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    @if($order->tax_paidby_type == "VENDOR")
                                                    <td>Pajak</td>
                                                    <td colspan="2" class="text-right">Rp. {{number_format($order->total_tax_vendor,0,",",".")}}</td>
                                                    @else
                                                    <td>Pajak yang dibayar pembeli</td>
                                                    <td colspan="2" class="text-right">Rp. {{number_format($order->total_tax_buyer,0,",",".")}}</td>
                                                    @endif
                                                </tr>
                                            </tbody>
                                            <tfoot>
                                            <!-- Grand Total -->
                                            @if($order->taxpaidby_type == "VENDOR")
                                            <tr style="background: #0daccc !important; -webkit-print-color-adjust: exact !important;" class="font-weight-bold text-white">
                                                <td></td>
                                                <td>Total Pembayaran</td>
                                                <td colspan="2" class="text-right">Rp. {{number_format($order->total_price+$order->delivery_fee,0,",",".")}}</td>
                                            </tr>
                                            @else
                                            <tr style="background: #0daccc !important; -webkit-print-color-adjust: exact !important;" class="font-weight-bold text-white">
                                                <td></td>
                                                <td>Total Pembayaran</td>
                                                <td colspan="2" class="text-right">Rp. {{number_format($order->total_price+$order->delivery_fee-$order->total_tax_buyer,0,",",".")}}</td>
                                            </tr>
                                            @endif
                                            <!-- Grand Total -->
                                            <tr>
                                                <td colspan="2">Penyedia</td>
                                                <td colspan="2"><span>{{@$order->customer->school->school_name ?? "-"}}, {{@\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $order->created_at, 'UTC')->setTimezone('Asia/Jakarta')->format('d-m-Y') ?? "-"}}</span>
                                                    <br>
                                                    Pelaksana
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">{{@$order->vendor->name ?? "-"}}<br>{{@$order->vendor->pic_name}}</td>
                                                <td colspan="2"><span>{{@$order->customer->school->nama_kepsek ?? "-"}}<br>NIP : {{@$order->customer->school->nip_kepsek ?? "-"}}</span>
                                                </td>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                @endif
                            @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="divFooter">
                    <img class="pdf-img" src="{{asset('assets/media/pdf_document/footer_dokumen.png')}}" />
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

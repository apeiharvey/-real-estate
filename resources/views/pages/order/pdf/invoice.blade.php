@extends('master')
@section('custom_title','Invoice')
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

            .divHeader{
                display: none;
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
                                    <h1 class="font-weight-boldest">Invoice</h1>
                                    <h5 class="font-weight-boldest">#Pembayaran Terverifikasi</h5>
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
                                    <td>Nomor Invoice </td>
                                    <td> : {{@$order->invoice->invoice_no ?? "-"}}</td>
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
                            @foreach($order->bast[0]->bast_detail as $item)
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
                                                    <td class="font-weight-bolder">{{$item->product_name}}<br>{!!@$item->product->packet_description ?? '' !!}</td>
                                                    <td>{{$item->received_quantity}}</td>
                                                    <td>Rp. {{number_format($item->product_price,0,",",".")}}</td>
                                                    <td class="text-right">Rp. {{number_format($item->product_price*$item->received_quantity,0,",",".")}}</td>
                                                </tr>
                                @if($loop->iteration % 10 == 0)
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
                                                <tr>
                                                    <td></td>
                                                    <td>Subtotal sudah termasuk pajak</td>
                                                    <td colspan="2" class="text-right">Rp. {{number_format($order->invoice->total_price,0,",",".")}}</td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td>Biaya Kirim</td>
                                                    <td colspan="2" class="text-right">Rp. {{number_format($order->invoice->delivery_fee,0,",",".")}}</td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    @if($order->tax_paidby_type == "VENDOR")
                                                        <td>Informasi Pajak</td>
                                                        <td colspan="2" class="text-right">Rp. {{number_format($order->invoice->total_price_tax,0,",",".")}}</td>
                                                    @else
                                                        <td>Pajak dibayar oleh Pembeli</td>
                                                        <td colspan="2" class="text-right">(-) Rp. {{number_format($order->invoice->total_price_tax,0,",",".")}}</td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td>Denda</td>
                                                    <td colspan="2" class="text-right">(-) Rp. {{number_format($order->invoice->total_penalty,0,",",".")}}</td>
                                                </tr>
                                            </tbody>
                                            <tfoot>
                                            <tr style="background: #0daccc !important; -webkit-print-color-adjust: exact !important;" class="font-weight-bold text-white">
                                                <td></td>
                                                <td class="font-weight-boldest">Total Pembayaran</td>
                                                <td colspan="2" class="text-right font-weight-boldest">Rp. {{number_format($order->invoice->grant_total,0,",",".")}}</td>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                @endif
                            @endforeach
                            @if($order->invoice->grant_total > 25000000)
                            <div class="col-12 px-0 mt-8 pt-5">
                                <h3 class="font-weight-boldest">INFORMASI PEMBAYARAN</h3>
                                <table class="col-6">
                                    <tr>
                                        <td class="py-4"><h5 class="font-weight-boldest">Nama Bank </h5></td>
                                        <td><h5 class="font-weight-boldest">: {{$config['BANK_NAME']}}</h5></td>
                                    </tr>
                                    <tr>
                                        <td class="py-4"><h5 class="font-weight-boldest">Nomor Rekening </h5></td>
                                        <td><h5 class="font-weight-boldest">: {{$config['BANK_NUMBER']}}</h5></td>
                                    </tr>
                                    <tr>
                                        <td class="py-4"><h5 class="font-weight-boldest">Total Pembayaran </h5></td>
                                        <td><h5 class="font-weight-boldest">: Rp. {{number_format($order->invoice->grant_total,0,",",".")}}</h5></td>
                                    </tr>
                                </table>
                            </div>
                            @endif
                            <div class="col-12 border border-primary mt-5 pt-5">
                                <h3 class="font-weight-boldest font-size-h5">Perhatian : </h3>
                                <ul class="pl-5">
                                    <li>Harap melakukan pembayaran hanya setelah Anda melakukan konfirmasi penerimaan barang</li>
                                    <li>Pastikan melakukan pembayaran melalui metode pembayaran yang telah disediakan sesuai dengan nominal yang ada di Invoice</li>
                                </ul>
                            </div>
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

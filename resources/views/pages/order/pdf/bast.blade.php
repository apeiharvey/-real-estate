@extends('master')
@section('custom_title','Formulir BAST')
@section('admin_css')
    <style>
        body{
            line-height: 1.7;
        }
        @media screen {
            div.divFooter {
                display: none;
            }
            .overlay {
                display:none;
            }
        }
        @media print {
            div.divFooter {
                position: fixed;
                bottom: 5px;
                right: 5px;
            }
            .overlay {
                margin: 0;
                position: absolute;
                top: 20%;
                left: 20%;
            }
            .overlay img{
                opacity: 0.5;
            }
        }
    </style>

@endsection
@section('content')
    <div id="bast_pdf" class="d-flex flex-column-fluid">
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
                            <div class="d-flex justify-content-between pb-5 flex-column flex-md-row">
                                <a href="#" class="mb-5">
                                    <img src="{{asset('assets/media/logos/Logo_SIPLAH_BAST.png')}}" alt="" />
                                </a>
                                <div class="d-flex flex-column align-items-md-end px-0 mt-5">
                                    <h1 class="font-weight-boldest">BERITA ACARA SERAH TERIMA</h1>
                                    <!--begin::Logo-->

                                    <!--end::Logo-->
                                    <span class="d-flex flex-column align-items-md-end opacity-70">
                                        <span>Nomor PO : {{@$bast->order_detail->po_no ?? "-"}}</span>
                                    </span>
                                </div>
                            </div>
                            <div class="mb-2">Pada hari ini, Senin tanggal {{$bast->idn_created_date->formatLocalized('%d')}} bulan {{$bast->idn_created_date->formatLocalized('%B')}} tahun {{$bast->idn_created_date->formatLocalized('%Y')}}, sesuai dengan:</div>
                            @if($bast->bast_type == "DRAFT")
                            <div class="mb-1">Nomor Surat Perjanjian : {{$bast->draft_no}}</div>
                            @else
                            <div class="mb-1">Nomor Surat Perjanjian : {{$bast->bast_no}}</div>
                            @endif
                            <table class="mb-2">
                                <tr>
                                    <td width="140px">Tanggal</td>
                                    <td>: {{@\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $bast->created_at, 'UTC')->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s') ?? "-"}}</td>
                                </tr>
                                <tr>
                                    <td>Nama pekerjaan</td>
                                    <td>: Kegiatan Jual Beli melalui mitra Siplah klikMRO</td>
                                </tr>
                                <tr>
                                    <td>Tahun</td>
                                    <td>: {{$bast->idn_created_date->formatLocalized('%Y')}}</td>
                                </tr>
                            </table>
                            <div class="mb-1">Yang bertanda tangan dibawah ini:</div>
                            <table class="mb-2">
                                <tr>
                                    <td>1.</td>
                                    <td>Nama </td>
                                    <td>: {{$bast->vendor_pic_name}}</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Jabatan </td>
                                    <td>: {{$bast->vendor_pic_position}}</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Nama Perusahaan</td>
                                    <td>: {{$bast->vendor_name}}</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Alamat Perusahaan</td>
                                    <td>: {{$bast->vendor_address}}</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Nomor Telpon</td>
                                    <td>: {{$bast->vendor_phone}}</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td colspan="2">Sebagai pihak yang menyerahkan, Selanjutkan disebut PIHAK PERTAMA</td>
                                </tr>
                            </table>
                            <table class="mb-2">
                                <tr>
                                    <td>2. </td>
                                    <td>Nama </td>
                                    <td>: {{$bast->school_pic_name}}</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Jabatan </td>
                                    <td>: {{@$bast->customer->jabatan ?? "-"}}</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Nama Perusahaan</td>
                                    <td>: {{$bast->school_name}}</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Alamat Perusahaan</td>
                                    <td>: {{$bast->school_address}}</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>Nomor Telpon</td>
                                    <td>: {{$bast->school_phone}}</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td colspan="2">Sebagai pihak yang menerima, Selanjutkan disebut PIHAK KEDUA</td>
                                </tr>
                            </table>
                            <div>PIHAK PERTAMA menyerahkan hasil pekerjaan pengiriman barang/jasa atas kegiatan jual beli pada Mitra Siplah klikMRO kepada PIHAK KEDUA, dan PIHAK KEDUA telah menerima hasil pekerjaan tersebut dalam jumlah yang lengkap dan kondisi yang baik sesuai dengna rincian berikut:</div>
                            <table class="table text-center">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th width="200px">Nama Produk</th>
                                        <th width="200px">Harga Satuan</th>
                                        <th>Jumlah Dipesan</th>
                                        <th>Jumlah Diterima</th>
                                        <th>Kondisi Barang Diterima</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($bast->bast_detail as $detail)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$detail->product_name}}</td>
                                        <td>Rp. {{number_format($detail->product_price,0,",",".")}}</td>
                                        <td>{{$detail->quantity}}</td>
                                        <td>{{$detail->received_quantity}}</td>
                                        <td>Baik</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="mb-3">Berita Acara Serah Terima ini berfungsi sebagai bukti terima hasil pekerjaan kepada PIHAK KEDUA, untuk selanjutkan dicatat pada buku penerimaan barang Satuan Pendidikan</div>
                            <div class="mb-3">Demikian Berita Acara Serah Terima ini dibuat dengan sebenarnya untuk dipergunakan sebagaimana seharusnya.</div>

                            <div class="d-flex justify-content-between pt-6">
                                <div class="d-flex flex-column flex-root">
                                    <span class="font-weight-bolder mb-20">PIHAK KEDUA</span>
                                    <span class="opacity-70">......................................................</span>
                                    <span>{{$bast->school_pic_nip}}</span>
                                </div>
                                <div class="d-flex flex-column flex-root">
                                    <span class="font-weight-bolder mb-20">PIHAK PERTAMA</span>
                                    <span class="opacity-70">.................................................</span>
                                    <span>{{$bast->vendor_pic_name}}</span>
                                    <span>{{$bast->vendor_name}}</span>
                                </div>
                                <div class="d-flex flex-column flex-root">
                                    <div class="font-weight-bolder mb-20 border-bottom-1 border-dark">PEMERIKSA BARANG</div>
                                    <span class="opacity-70">................................................</span>
                                    <span></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="divFooter">
                    <img class="pdf-img" src="{{asset('assets/media/pdf_document/footer_dokumen.png')}}" />
                </div>
                @if($bast->bast_type == "DRAFT")
                <div class="overlay">
                    <img src="{{asset('assets/media/pdf_document/draft_2.png')}}" />
                </div>
                @endif
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

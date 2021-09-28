
    <!--begin::List Widget 17-->
    <div class="card card-custom gutter-b">
        <div class="card-header border-0 pt-5">
            <h3 class="card-title align-items-start flex-column">
                <span class="card-label font-weight-bolder text-dark mb-4">Ringkasan</span>
                <!--Status Transaksi-->
                <span class="text-muted font-weight-bold font-size-sm mb-2">Status Transaksi</span>
                @if(in_array($selected->status,$red_status))
                    <span class="label label-danger label-inline font-weight-bolder">{{$selected->id_order_status}}</span>
                @else
                    <span class="label label-success label-inline font-weight-bolder">{{$selected->id_order_status}}</span>
                @endif
                @if($selected->is_frozen)
                    <div class="col-12"><hr></div>
                    <span class="label label-danger label-inline font-weight-bolder mb-4">TRANSAKSI DIBEKUKAN</span>
                    <span class="d-block text-muted font-weight-bold font-size-sm mb-2">Dibekukan pada tanggal : </span>
                    <span class="d-block text-dark-75 font-weight-bolder font-size-lg mb-2">{{@\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $selected->frozen_at, 'UTC')->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s')}}</span>
                    <span class="d-block text-dark font-weight-bold font-size-sm mb-4">Mohon hubungi Admin untuk menindak lanjutkan Order</span>
                @endif
            </h3>
        </div>
        <!--end::Header-->
        <!--begin::Body-->
        @if(!$selected->is_frozen)
        <div class="card-body pt-0">
            <div class="row">
                @if(isset($selected->expired_date) && ($selected->cancel_status == "CANCELLATION_PROPOSED"))
                    <div class="col-12 mb-4">
                        <span class="d-block text-muted font-weight-bold font-size-sm mb-2">Batal Otomatis</span>
                        @if($selected->expired_date > 10)
                            <span class="label label-success label-inline font-weight-bolder">{{$selected->expired_date}} hari lagi</span>
                        @elseif($selected->expired_date > 6)
                            <span class="label label-warning label-inline font-weight-bolder">{{$selected->expired_date}} hari lagi</span>
                        @elseif($selected->expired_date > 3)
                            <span class="label label-danger label-inline font-weight-bolder">{{$selected->expired_date}} hari lagi</span>
                        @else
                            <span class="label label-danger label-inline font-weight-bolder">Mohon segera proses transaksi ini</span>
                        @endif
                    </div>
                @endif
                <div class="col-12">
                    @if(in_array($selected->status,$status_ship))
                        <hr>
                        <span class="d-block text-muted font-weight-bold font-size-sm">Jasa Kurir Pengiriman</span>
                        <span class="d-block text-dark-75 font-weight-bolder font-size-lg mb-2">{{@$selected->shipment_service->provider->name}}</span>
                        <span class="d-block text-muted font-weight-bold font-size-sm">Nomor Resi</span>
                        <span class="d-block text-dark-75 font-weight-bolder font-size-lg mb-2">{{ @$selected->order_delivery->no_resi ?? '-'}}</span>
                        @if($selected->invoice_payment != null)
                            <hr>
                            <span class="d-block text-muted font-weight-bold font-size-sm">Metode Pembayaran</span>
                            <span class="d-block text-dark-75 font-weight-bolder font-size-lg mb-2">{{$selected->invoice_payment->payment_channel != null ? str_replace("_"," ",$selected->invoice_payment->payment_channel) : "Bank Transfer"}}</span>
                            @if(@$selected->invoice_payment->payment_channel != null)
                                <span class="d-block text-muted font-weight-bold font-size-sm">Nomor Virtual Account</span>
                                <span class="d-block text-dark-75 font-weight-bolder font-size-lg mb-2">{{ @$selected->invoice_payment->payment_va_number ?? '-'}}</span>
                            @endif
                        @endif
                        @if($selected->status == "BAST_CREATED" || $selected->status == "BAST_REJECTED")
                            <div class="accordion accordion-light accordion-toggle-arrow mb-4" id="accordionExample2">
                                <div class="card">
                                    <div class="card-header" id="headingOne2">
                                        <div class="card-title" data-toggle="collapse" data-target="#collapseOne2">
                                            <i class="la la-file-contract text-success"></i> Dokumen BAST
                                        </div>
                                    </div>
                                    <div id="collapseOne2" class="collapse show" data-parent="#accordionExample2">
                                        <div class="card-body">
                                            <ul class="list-group">
                                                @foreach($selected->bast as $row_bast)
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        @if($row_bast->bast_type == "DRAFT")
                                                            <a href="{{url('order/download/bast/'.Illuminate\Support\Facades\Crypt::encryptString($row_bast->id))}}" target="_blank" class="btn-show-bast text-dark text-hover-primary font-weight-bold">{{@$row_bast->draft_no ?? "-"}}</a>
                                                        @else
                                                            <a href="{{url('order/download/bast/'.Illuminate\Support\Facades\Crypt::encryptString($row_bast->id))}}" target="_blank" class="btn-show-bast text-dark text-hover-primary font-weight-bold">{{@$row_bast->bast_no ?? "-"}}</a>
                                                        @endif
                                                        @if($row_bast->status == "CREATED")
                                                            <div class="badge badge-info font-weight-bolder">{{$bast_status[$row_bast->status]}}</div>
                                                        @endif
                                                        @if($row_bast->status == "REJECTED")
                                                            <div class="badge badge-danger font-weight-bolder">{{$bast_status[$row_bast->status]}}</div>
                                                        @endif
                                                        @if($row_bast->status == "SUBMITTED")
                                                            <div class="badge badge-success font-weight-bolder">{{$bast_status[$row_bast->status]}}</div>
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if($selected->status == "BAST_CREATED")
                                <button id="btn_accept_bast" class="btn btn-success btn-block font-weight-bolder font-size-sm py-2">Terima BAST</button>
                                <button id="btn_reject_bast" class="btn btn-danger btn-block font-weight-bolder font-size-sm py-2">Tolak BAST</button>
                            @endif
                        @endif
                    @endif
                    @if($selected->status == "CREATED")
                        <span class="d-block text-muted font-weight-bold font-size-sm">Catatan Transaksi</span>
                        <span class="d-block text-dark-75 font-weight-bolder font-size-md mb-2">{{@$selected->negotiation_note}}</span>
                        @if($selected->shipment_service->code == "PRIV")
                            <input id="inp_ongkir" maxlength="50" class="form-control mb-2" type="text" placeholder="Masukkan Ongkos Kirim"/>
                            <div id="invalid_inp_ongkir" class="hide invalid-feedback">Mohon masukkan harga ongkos kirim</div>
                            <hr>
                            <button id="btn_approve_order_priv" type="button" class="btn btn-success btn-block font-weight-bolder font-size-sm py-2">Terima Transaksi</button>
                        @else
                            <hr>
                            <button id="btn_approve_order" type="button" class="btn btn-success btn-block font-weight-bolder font-size-sm py-2">Terima Transaksi</button>
                        @endif
                            <button id="btn_reject_order" type="button" class="btn btn-danger btn-block font-weight-bolder font-size-sm py-2">Tolak Transaksi</button>
                    @elseif($selected->status == "BUYER_APPROVED")
                        <hr>
                        <button id="btn_proses" type="button" class="btn btn-success btn-block font-weight-bolder font-size-sm py-2">Proses Transaksi</button>
                    @elseif($selected->status == "ORDER_PROCESSED")
                        <hr>
                        @if($selected->status_update_order == "REQUEST")
                            <span class="d-block text-muted font-weight-bold font-size-sm mb-2">Pembaharuan Transaksi</span>
                            <button class="btn btn-show-edit btn-success btn-block font-weight-bolder font-size-sm py-2">Lihat Pembaharuan</button>
                        @else
                            <span class="d-block text-muted font-weight-bold font-size-sm">Jasa Kurir Pengiriman</span>
                            <span class="d-block text-dark-75 font-weight-bolder font-size-lg mb-2">{{@$selected->shipment_service->provider->name}}</span>
                            @if($selected->shipment_service->code == "PRIV")
                                <span class="d-block text-muted font-weight-bold font-size-sm">Nomor Resi</span>
                                <input id="inp_resi" class="form-control mb-2" type="text" placeholder=""/>
                                <div id="invalid_inp_resi" class="hide invalid-feedback">Mohon masukkan nomor resi</div>
                                <button id="btn_ship_priv" type="button" class="btn btn-success btn-block font-weight-bolder font-size-sm py-2">Kirim</button>
                            @else
                                @if($selected->order_delivery!= null)
                                    @if($selected->order_delivery->no_resi != "" && $selected->order_delivery->no_resi != null)
                                        <span class="d-block text-muted font-weight-bold font-size-sm">Nomor Resi</span>
                                        <span class="d-block text-dark-75 font-weight-bolder font-size-lg mb-2">{{ @$selected->order_delivery->no_resi ?? '-'}}</span>
                                        <div class="d-flex align-items-center my-5">
                                            <!--begin::Symbol-->
                                            <div class="symbol symbol-40 symbol-light-primary mr-5">
                                                <span class="symbol-label">
                                                    <span class="svg-icon svg-icon-xl svg-icon-primary">
                                                        <!--begin::Svg Icon | path:assets/media/svg/icons/Home/Library.svg-->
                                                       <i class="la la-shopping-cart text-primary icon-2x"></i>
                                                        <!--end::Svg Icon-->
                                                    </span>
                                                </span>
                                            </div>
                                            <!--end::Symbol-->
                                            <!--begin::Text-->
                                            <div class="d-flex flex-column font-weight-bold">
                                                <a id="btn_show_label" href="{{url('order/download/label_jne/'.Illuminate\Support\Facades\Crypt::encryptString($selected->id))}}" target="_blank" class="text-dark text-hover-primary mb-1 font-size-lg">Label Pengiriman</a>
                                            </div>
                                            <!--end::Text-->
                                        </div>
                                        <button id="btn_ship" type="button" class="btn btn-success btn-block font-weight-bolder font-size-sm py-2">Kirim</button>
                                    @else
                                        <button id="btn_request_awb" type="button" class="btn btn-success btn-block font-weight-bolder font-size-sm py-2">Mengajukan Nomor Resi</button>
                                    @endif
                                @endif
                            @endif
                        @endif
                    @elseif($selected->status == "PAYMENT_PROCESSED" || $selected->status == "BAST_SUBMITTED")
                        <hr>
                        @if(@$selected->tax_document != null)
                        <div class="d-flex align-items-center mb-4">
                            <!--begin::Symbol-->
                            <div class="symbol symbol-40 symbol-light-danger mr-5">
                                <span class="symbol-label">
                                    <span class="svg-icon svg-icon-lg svg-icon-info">
                                        <!--begin::Svg Icon | path:assets/media/svg/icons/General/Attachment2.svg-->
                                       <i class="la la-scroll text-info icon-2x"></i>
                                        <!--end::Svg Icon-->
                                    </span>
                                </span>
                            </div>
                            <!--end::Symbol-->
                            <!--begin::Text-->
                            <div class="d-flex flex-column font-weight-bold">
                                <a href="{{$url['disk']->url('').$selected->tax_document}}" target="_blank" class="text-dark text-hover-primary mb-1 font-size-lg">Dokumen Pajak</a>
                            </div>
                            <!--end::Text-->
                        </div>
                        @endif
                        @if($selected->tax_paidby_type == "VENDOR")
                        <span class="d-block text-muted font-weight-bold font-size-sm mb-2">Upload Pajak</span>
                        <div class="row mb-10">
                            <div class="centerize">
                                <div class="dropzone dropzone-default dropzone-success" id="dataListUpload">
                                    <div class="dropzone-msg dz-message needsclick">
                                        <h3 class="dropzone-msg-title">Taruh file disini atau <span class="text-success">cari</span></h3>
                                        <span class="dropzone-msg-desc">File yang boleh diupload adalah file .jpg, .jpeg, .png, .pdf</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center mb-10">
                            <button type="reset" class="p-2 btn btn-light-primary font-weight-bolder font-size-lg px-6 mr-2
                            button-process button-process-upload">Proses Unggah</button>
                            <button type="reset" class="p-2 btn btn-primary font-weight-bolder font-size-lg px-6
                            button-process button-process-cancel">Batalkan</button>
                        </div>
                        @endif
                        @if($selected->payment_diff > 3 && !$selected->is_complained)
                        <hr>
                        <button id="btn_payment_complaint" type="button" class="btn btn-danger btn-block font-weight-bolder font-size-sm py-2">KOMPLAIN PEMBAYARAN</button>
                        @elseif($selected->payment_diff <= 3)
                        <hr>
                        <button id="btn_payment_complaint" type="button" class="btn btn-danger btn-block font-weight-bolder font-size-sm py-2" disabled>KOMPLAIN PEMBAYARAN</button>
                        @elseif($selected->is_complained)
                        <span class="label label-warning label-inline font-weight-bolder mb-4">PEMBAYARAN TELAH DIKOMPLAIN</span>
                        @endif

{{--                        @if(!$selected->is_complained)--}}
{{--                            <hr>--}}
{{--                            <button id="btn_payment_complaint" type="button" class="btn btn-danger btn-block font-weight-bolder font-size-sm py-2">KOMPLAIN PEMBAYARAN</button>--}}
{{--                        @elseif($selected->is_complained)--}}
{{--                            <hr>--}}
{{--                            <span class="label label-warning label-inline font-weight-bolder mb-4">PEMBAYARAN TELAH DIKOMPLAIN</span>--}}
{{--                        @endif--}}
                    @elseif($selected->status == "PAYMENT_CONFIRMED")
                        <hr>
                        <button id="btn_payment_received" type="button" class="btn btn-success btn-block font-weight-bolder font-size-sm py-2">PEMBAYARAN DITERIMA</button>
                    @endif
                    @if($selected->complaint != null)
                        <hr>
                        <span class="label label-danger label-inline font-weight-bolder mb-4">Transaksi Dikomplain</span>
                        <a href="{{url('complaint')."?ch=".hash('sha256',$selected->complaint->id)}}" target="_blank" class="btn btn-success btn-block font-weight-bolder font-size-sm py-2">Lihat Komplain</a>
                    @endif
                </div>

                <!--Pembatalan Transaksi-->
                @if($selected->cancel_status != null)
                    @if($selected->status != "CANCELED" && $selected->status != "COMPLETED")
                    <div class="col-12">
                        <hr>
                        <span class="d-block text-muted font-weight-bold font-size-sm mb-2">Pembatalan Transaksi</span>

                        @if($selected->cancel_status == 'CANCELLATION_PROPOSED')
                            <span class="label label-danger label-inline font-weight-bolder mb-4">{{$selected->id_cancel_status}}</span>
                            <span class="d-block text-muted font-weight-bold font-size-sm">Note Pembatalan : </span>
                            <span class="d-block text-muted font-weight-bolder font-size-sm">{{@$selected->cancel_note_buyer ?? '-'}}</span>
                            <br>
                            <button id="btn_cancel_reject" type="button" class="btn btn-danger font-weight-bolder font-size-sm py-2">TOLAK</button>
                            <button id="btn_cancel_approve" type="button" class="btn btn-success font-weight-bolder font-size-sm py-2">TERIMA</button>
                        @else
                            <span class="label label-warning label-inline font-weight-bolder mb-4">{{$selected->id_cancel_status}}</span>
                        @endif
                    </div>
                    @endif
                @endif
            </div>
        </div>
        @endif
    </div>
    <!--end::List Widget 17-->
    <!--begin::List Widget 21-->
    @if(count($selected->receive) > 0)
    <div class="card card-custom gutter-b">
        <!--begin::Header-->
        <div class="card-header border-0 pt-5">
            <h3 class="card-title align-items-start flex-column">
                <span class="card-label font-weight-bolder text-dark">Bukti terima order</span>
            </h3>
        </div>
        <!--end::Header-->
        <!--begin::Body-->
        <div class="card-body pt-4">
            <div data-scroll="true" data-height="300">
                <div class="scroll-body pt-2">
                    @foreach($selected->receive as $receive)
                        @foreach($receive->receive_detail as $detail)
                            <div class="d-flex align-items-center mb-8 receive-item">
                                <!--begin::Symbol-->
                                <div class="symbol mr-5 pt-1">
                                    <div class="symbol-label min-w-80px min-h-150px" style="background-image: url({{$url['disk']->url('').$detail->document1}})"></div>
                                </div>
                                <!--end::Symbol-->
                                <!--begin::Info-->
                                <div class="d-flex flex-column">
                                    <!--begin::Title-->
                                    <span class="text-dark-50 font-weight-bold">{{@\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $receive->created_at, 'UTC')->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s')}}</span>
                                    <span class="text-dark-75 font-weight-bolder font-size-lg">{{$detail->product->name}}</span>
                                    <span class="text-dark-75 font-size-md">Barang diterima : <span class="text-success font-weight-boldest">{{$detail->received_quantity}}</span></span>
                                    <!--end::Title-->
                                    <!--begin::Text-->
                                    <span class="text-dark-50 font-weight-bold font-size-sm pb-4">{{@$detail->note ?? "-"}}</span>
                                    <!--end::Text-->
                                    <!--begin::Action-->
                                    <div>
                                        <button type="button" class="btn-show-receive btn btn-light font-weight-bolder font-size-sm py-2" data-image="{{$url['disk']->url('').$detail->document1}}">Lihat</button>
                                    </div>
                                    <!--end::Action-->
                                </div>
                                <!--end::Info-->
                            </div>
                        @endforeach
                    @endforeach
                </div>
            </div>
        </div>
        <!--end::Body-->
    </div>
    @endif

    @if($selected->po_no != null)
        <div class="card card-custom gutter-b">
            <!--begin::Header-->
            <div class="card-header border-0 pt-5">
                <h3 class="card-title align-items-start flex-column mb-5">
                    <span class="card-label font-weight-bolder text-dark mb-1">Cetak Dokumen</span>
                </h3>
            </div>
            <!--end::Header-->
            <!--begin::Body-->
            <div class="card-body pt-8">
                <!--begin::Item-->

                <div class="d-flex align-items-center mb-10">
                    <!--begin::Symbol-->
                    <div class="symbol symbol-40 symbol-light-primary mr-5">
                        <span class="symbol-label">
                            <span class="svg-icon svg-icon-xl svg-icon-primary">
                                <!--begin::Svg Icon | path:assets/media/svg/icons/Home/Library.svg-->
                               <i class="la la-shopping-cart text-primary icon-2x"></i>
                                <!--end::Svg Icon-->
                            </span>
                        </span>
                    </div>
                    <!--end::Symbol-->
                    <!--begin::Text-->
                    <div class="d-flex flex-column font-weight-bold">
                        <a id="btn_show_po" href="{{url('order/download/po/'.Illuminate\Support\Facades\Crypt::encryptString($selected->id))}}" target="_blank" class="text-dark text-hover-primary mb-1 font-size-lg">Purchase Order</a>
                    </div>
                    <!--end::Text-->
                </div>
                <!--begin::Item -->
                @if(in_array($selected->status,$status_invoice))
                    <div class="accordion accordion-light accordion-toggle-arrow mb-4" id="accordionExample2">
                        <div class="card">
                            <div class="card-header" id="headingOne2">
                                <div class="card-title" data-toggle="collapse" data-target="#collapseOne2">
                                    <div class="symbol symbol-40 symbol-light-success mr-5">
                                        <span class="symbol-label">
                                            <span class="svg-icon svg-icon-lg svg-icon-success">
                                                <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Group-chat.svg-->
                                                <i class="la la-file-contract text-success icon-2x"></i>
                                                <!--end::Svg Icon-->
                                            </span>
                                        </span>
                                    </div>
                                    <!--end::Symbol-->
                                    <!--begin::Text-->
                                    <div class="d-flex flex-column font-weight-bold">
                                        <span class="text-dark mb-1 font-size-lg">BAST</span>
                                    </div>
                                </div>
                            </div>
                            <div id="collapseOne2" class="collapse show" data-parent="#accordionExample2">
                                <div class="card-body">
                                    <ul class="list-group">
                                        @foreach($selected->bast as $row_bast)
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                @if($row_bast->bast_type == "DRAFT")
                                                    <a href="{{url('order/download/bast/'.Illuminate\Support\Facades\Crypt::encryptString($row_bast->id))}}" target="_blank" class="btn-show-bast text-dark text-hover-primary font-weight-bold">{{@$row_bast->draft_no ?? "-"}}</a>
                                                @else
                                                    <a href="{{url('order/download/bast/'.Illuminate\Support\Facades\Crypt::encryptString($row_bast->id))}}" target="_blank" class="btn-show-bast text-dark text-hover-primary font-weight-bold">{{@$row_bast->bast_no ?? "-"}}</a>
                                                @endif
                                                @if($row_bast->status == "CREATED")
                                                    <div class="badge badge-info font-weight-bolder">{{$bast_status[$row_bast->status]}}</div>
                                                @endif
                                                @if($row_bast->status == "REJECTED")
                                                    <div class="badge badge-danger font-weight-bolder">{{$bast_status[$row_bast->status]}}</div>
                                                @endif
                                                @if($row_bast->status == "SUBMITTED")
                                                    <div class="badge badge-success font-weight-bolder">{{$bast_status[$row_bast->status]}}</div>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            <!--end::Item-->
                <!--begin::Item-->
                @if(in_array($selected->status,$status_invoice))
                    <!-- begin::Invoice -->
                    <div class="d-flex align-items-center mb-10">
                        <!--begin::Symbol-->
                        <div class="symbol symbol-40 symbol-light-danger mr-5">
                            <span class="symbol-label">
                                <span class="svg-icon svg-icon-lg svg-icon-danger">
                                    <!--begin::Svg Icon | path:assets/media/svg/icons/General/Attachment2.svg-->
                                   <i class="la la-file-invoice text-danger icon-2x"></i>
                                    <!--end::Svg Icon-->
                                </span>
                            </span>
                        </div>
                        <!--end::Symbol-->
                        <!--begin::Text-->
                        <div class="d-flex flex-column font-weight-bold">
                            <a id="btn_show_invoice" href="{{url('order/download/invoice/'.Illuminate\Support\Facades\Crypt::encryptString($selected->id))}}" target="_blank" class="text-dark text-hover-primary mb-1 font-size-lg">Invoice</a>
                        </div>
                        <!--end::Text-->
                    </div>
                    <!-- end::Invoice -->
                    @endif
                    @if(in_array($selected->status,$status_after_payment))
                    <!-- begin::Pajak -->
                    <div class="d-flex align-items-center mb-10">
                        <!--begin::Symbol-->
                        <div class="symbol symbol-40 symbol-light-danger mr-5">
                            <span class="symbol-label">
                                <span class="svg-icon svg-icon-lg svg-icon-info">
                                    <!--begin::Svg Icon | path:assets/media/svg/icons/General/Attachment2.svg-->
                                   <i class="la la-scroll text-info icon-2x"></i>
                                    <!--end::Svg Icon-->
                                </span>
                            </span>
                        </div>
                        <!--end::Symbol-->
                        <!--begin::Text-->
                        <div class="d-flex flex-column font-weight-bold">
                            <a href="{{$url['disk']->url('').$selected->tax_document}}" target="_blank" class="text-dark text-hover-primary mb-1 font-size-lg">Dokumen Pajak</a>
                        </div>
                        <!--end::Text-->
                    </div>
                    @endif
                    <!-- end::Pajak -->
            <!--end::Item-->
            </div>
            <!--end::Body-->
        </div>
    @endif
<!--end::List Widget 21-->

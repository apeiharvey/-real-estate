@extends('includes.layout_admin')

@section('custom_title','Vendor')

@section('admin_css')
    <link href="{{ asset('assets/css/pages/wizard/wizard-3.css') }}" rel="stylesheet" type="text/css" />
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
                                <a href="{{url()->current()}}" class="text-muted">@yield('custom_title')</a>
                            </li>
                            <li class="breadcrumb-item text-muted">
                                <a href="{{url()->current()}}" class="text-muted">Informasi Vendor</a>
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
                    @if(!empty(auth()->user()->user_ref_id))
                        <a href="{{route('member')}}" class="btn btn-light-primary font-weight-bolder btn-sm mr-1">Lihat Member</a>
                    @endif
                    <a href="{{route('vendor-edit')}}" class="btn btn-light-primary font-weight-bolder btn-sm">{{empty(auth()->user()->user_ref_id)?"Lengkapi Data":"Ubah Data"}} Vendor</a>
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
                <div class="card card-custom">
                    <div class="card-body p-0">
                        <!--begin: Wizard-->
                        <div class="wizard wizard-3" id="kt_wizard_v3" data-wizard-state="step-first" data-wizard-clickable="true">
                            <!--begin: Wizard Nav-->
                            <div class="wizard-nav">
                                <div class="wizard-steps px-8 py-8 px-lg-15 py-lg-3">
                                    <!--begin::Wizard Step 1 Nav-->
                                    <div class="wizard-step" data-wizard-type="step" data-wizard-state="current">
                                        <div class="wizard-label">
                                            <h3 class="wizard-title">
                                                Perusahaan</h3>
                                            <div class="wizard-bar"></div>
                                        </div>
                                    </div>
                                    <!--end::Wizard Step 1 Nav-->
                                    <!--begin::Wizard Step 2 Nav-->
                                    <div class="wizard-step" data-wizard-type="step">
                                        <div class="wizard-label">
                                            <h3 class="wizard-title">
                                                Penandatangan</h3>
                                            <div class="wizard-bar"></div>
                                        </div>
                                    </div>
                                    <!--end::Wizard Step 2 Nav-->
                                    <!--begin::Wizard Step 3 Nav-->
                                    <div class="wizard-step" data-wizard-type="step">
                                        <div class="wizard-label">
                                            <h3 class="wizard-title">
                                                Bank</h3>
                                            <div class="wizard-bar"></div>
                                        </div>
                                    </div>
                                    <!--end::Wizard Step 3 Nav-->
                                    <!--begin::Wizard Step 4 Nav-->
                                    <div class="wizard-step" data-wizard-type="step">
                                        <div class="wizard-label">
                                            <h3 class="wizard-title">
                                                Dokumen</h3>
                                            <div class="wizard-bar"></div>
                                        </div>
                                    </div>
                                    <!--end::Wizard Step 4 Nav-->
                                </div>
                            </div>
                            <!--end: Wizard Nav-->
                            <!--begin: Wizard Body-->
                            <div class="row justify-content-center py-10 px-8 py-lg-12 px-lg-10">
                                <div class="col-xl-12 col-xxl-7">
                                    <!--begin: Wizard Form-->
                                    <form class="form" id="kt_form">
                                        <!--begin: Wizard Step 1-->
                                        <div class="pb-5" data-wizard-type="step-content" data-wizard-state="current">
                                            <h4 class="mb-10 font-weight-bold text-dark">Informasi Perusahaan</h4>
                                            <div class="row">
                                                <div class="col-xl-6">
                                                    <!--begin::Input-->
                                                    <div class="form-group">
                                                        <div class="symbol symbol-100 mr-5">
                                                            <div class="symbol-label" style="background-image:url('{{ @$disk->url(@$vendor_data->avatar_img) }}')"></div>
                                                        </div>
                                                    </div>
                                                    <!--end::Input-->
                                                </div>
                                                <div class="col-xl-6">
                                                    <!--begin::Input-->
                                                    <div class="form-group">
                                                        <label>Status</label>
                                                        <span class="form-text text-muted">{{ @$vendor_data->status ?? "-" }}</span>
                                                    </div>
                                                    <!--end::Input-->
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xl-6">
                                                    <!--begin::Input-->
                                                    <div class="form-group">
                                                        <label>Nama Perusahaan</label>
                                                        <span class="form-text text-muted">{{ @$vendor_data->name ?? "-" }}</span>
                                                    </div>
                                                    <!--end::Input-->
                                                    <!--begin::Input-->
                                                    <div class="form-group">
                                                        <label>Jenius Usaha</label>
                                                        <span class="form-text text-muted">{{ @$vendor_data->business_type ?? "-" }}</span>
                                                    </div>
                                                    <!--end::Input-->
                                                    <!--begin::Input-->
                                                    <div class="form-group">
                                                        <label>Kelas Usaha</label>
                                                        <span class="form-text text-muted">{{ @$vendor_data->business_class ?? "-" }}</span>
                                                    </div>
                                                    <!--end::Input-->
                                                    <!--begin::Input-->
                                                    <div class="form-group">
                                                        <label>Status</label>
                                                        <span class="form-text text-muted">{{ @$vendor_data->tax_status ?? "-" }}</span>
                                                    </div>
                                                    <!--end::Input-->
                                                    <!--begin::Input-->
                                                    <div class="form-group">
                                                        <label>NPWP</label>
                                                        <span class="form-text text-muted">{{ @$vendor_data->npwp ?? "-" }}</span>
                                                    </div>
                                                    <!--end::Input-->
                                                    <!--begin::Input-->
                                                    <div class="form-group">
                                                        <label>SIUP/NIB</label>
                                                        <span class="form-text text-muted">{{ @$vendor_data->siup_nib ?? "-" }}</span>
                                                    </div>
                                                    <!--end::Input-->
                                                    <!--begin::Input-->
                                                    <div class="form-group">
                                                        <label>Supplier Code</label>
                                                        <span class="form-text text-muted">{{ @$vendor_data->sap_supplier_code ?? "-" }}</span>
                                                    </div>
                                                    <!--end::Input-->
                                                </div>
                                                <div class="col-xl-6">
                                                    <!--begin::Input-->
                                                    <div class="form-group">
                                                        <label>TDP</label>
                                                        <span class="form-text text-muted">{{ @$vendor_data->tdp ?? "-" }}</span>
                                                    </div>
                                                    <!--end::Input-->
                                                    <!--begin::Input-->
                                                    <div class="form-group">
                                                        <label>Alamat</label>
                                                        <span class="form-text text-muted">{{ @$vendor_data->address ?? "-" }}</span>
                                                    </div>
                                                    <!--end::Input-->
                                                    <!--begin::Input-->
                                                    <div class="form-group">
                                                        <label>Wilayah</label>
                                                        <span class="form-text text-muted">{{ @$vendor_data->district->name ?? "-" }}, {{ @$vendor_data->region->kota ?? "-" }}</span>
                                                    </div>
                                                    <!--end::Input-->
                                                    <!--begin::Input-->
                                                    <div class="form-group">
                                                        <label>Kode Pos</label>
                                                        <span class="form-text text-muted">{{ @$vendor_data->address_zip_code ?? "-" }}</span>
                                                    </div>
                                                    <!--end::Input-->
                                                    <!--begin::Input-->
                                                    <div class="form-group">
                                                        <label>Geolokasi</label>
                                                        <span class="form-text text-muted">{{ @$vendor_data->address_latitude ?? "-" }}, {{ @$vendor_data->address_longitude ?? "-" }}</span>
                                                    </div>
                                                    <!--end::Input-->
                                                    <!--begin::Input-->
                                                    <div class="form-group">
                                                        <label>Email</label>
                                                        <span class="form-text text-muted">{{ @$vendor_data->email ?? "-" }}</span>
                                                    </div>
                                                    <!--end::Input-->
                                                </div>
                                            </div>
                                        </div>
                                        <!--end: Wizard Step 1-->
                                        <!--begin: Wizard Step 2-->
                                        <div class="pb-5" data-wizard-type="step-content">
                                            <h4 class="font-weight-bold text-dark">Penanggungjawab / Penandatangan</h4>
                                            <span class="mb-10 form-text text-muted">Direksi/pimpinan perusahaan yang akan dicantumkan dalam dokumen administrasi (Berita Acara Serah Terima, Invoice, dll)</span>
                                            <div class="row">
                                                <div class="col-xl-6">
                                                    <!--begin::Input-->
                                                    <div class="form-group">
                                                        <label>Nama Lengkap</label>
                                                        <span class="form-text text-muted">{{ @$vendor_data->pic_name ?? "-" }}</span>
                                                    </div>
                                                    <!--end::Input-->
                                                    <!--begin::Input-->
                                                    <div class="form-group">
                                                        <label>Jabatan</label>
                                                        <span class="form-text text-muted">{{ @$vendor_data->pic_position ?? "-" }}</span>
                                                    </div>
                                                    <!--end::Input-->
                                                    <!--begin::Input-->
                                                    <div class="form-group">
                                                        <label>Email</label>
                                                        <span class="form-text text-muted">{{ @$vendor_data->pic_email ?? "-" }}</span>
                                                    </div>
                                                    <!--end::Input-->
                                                </div>
                                                <div class="col-xl-6">
                                                    <!--begin::Input-->
                                                    <div class="form-group">
                                                        <label>NIK/KTP</label>
                                                        <span class="form-text text-muted">{{ @$vendor_data->pic_id_card_no ?? "-" }}</span>
                                                    </div>
                                                    <!--end::Input-->
                                                    <!--begin::Input-->
                                                    <div class="form-group">
                                                        <label>Nomor Ponsel</label>
                                                        <span class="form-text text-muted">{{ @$vendor_data->phone2 ?? "-" }}</span>
                                                    </div>
                                                    <!--end::Input-->
                                                    <!--begin::Input-->
                                                    <div class="form-group">
                                                        <label>Nomor Telpon Kantor</label>
                                                        <span class="form-text text-muted">{{ @$vendor_data->phone1 ?? "-" }}</span>
                                                    </div>
                                                    <!--end::Input-->
                                                </div>
                                            </div>
                                        </div>
                                        <!--end: Wizard Step 2-->
                                        <!--begin: Wizard Step 3-->
                                        <div class="pb-5" data-wizard-type="step-content">
                                            <h4 class="mb-10 font-weight-bold text-dark">Informasi Bank</h4>
                                            <div class="row">
                                                <div class="col-xl-6">
                                                    <!--begin::Input-->
                                                    <div class="form-group">
                                                        <label>Nama Bank</label>
                                                        <span class="form-text text-muted">{{ @$vendor_data->bank_name ?? "-" }}</span>
                                                    </div>
                                                    <!--end::Input-->
                                                    <!--begin::Input-->
                                                    <div class="form-group">
                                                        <label>Nomor Rekening</label>
                                                        <span class="form-text text-muted">{{ @$vendor_data->bank_account_no ?? "-" }}</span>
                                                    </div>
                                                    <!--end::Input-->
                                                </div>
                                                <div class="col-xl-6">
                                                    <!--begin::Input-->
                                                    <div class="form-group">
                                                        <label>Nama Pemilik Bank</label>
                                                        <span class="form-text text-muted">{{ @$vendor_data->bank_account_name ?? "-" }}</span>
                                                    </div>
                                                    <!--end::Input-->
                                                    <!--begin::Input-->
                                                    <div class="form-group">
                                                        <label>Cabang Bank</label>
                                                        <span class="form-text text-muted">{{ @$vendor_data->bank_branch_name ?? "-" }}</span>
                                                    </div>
                                                    <!--end::Input-->
                                                </div>
                                            </div>
                                        </div>
                                        <!--end: Wizard Step 3-->
                                        <!--begin: Wizard Step 4-->
                                        <div class="pb-5" data-wizard-type="step-content">
                                            <h4 class="mb-10 font-weight-bold text-dark">Dokumen</h4>
                                            <div class="row">
                                                <div class="col-xl-6">
                                                    <!--begin::Input-->
                                                    <div class="form-group">
                                                        <label>KTP Penanggungjawab</label>
                                                        @if(!empty(@optional(@optional($vendor_data->documents)->where("type","KTP"))->last()->url))
                                                            <a class="form-text text-muted" target="_blank" href="{{ @$disk->url(@optional(@optional($vendor_data->documents)->where("type","KTP"))->last()->url) ?? "#"}}">{{ @$disk->url(@optional(@optional($vendor_data->documents)->where("type","KTP"))->last()->url) ?? "-"}}</a>
                                                        @else
                                                            -
                                                        @endif
                                                    </div>
                                                    <!--end::Input-->
                                                    <!--begin::Input-->
                                                    <div class="form-group">
                                                        <label>NPWP</label>
                                                        @if(!empty(@optional(@optional($vendor_data->documents)->where("type","NPWP"))->last()->url))
                                                            <a class="form-text text-muted" target="_blank" href="{{ @$disk->url(@optional(@optional($vendor_data->documents)->where("type","NPWP"))->last()->url) ?? "#"}}">{{ @$disk->url(@optional(@optional($vendor_data->documents)->where("type","NPWP"))->last()->url) ?? "-"}}</a>
                                                        @else
                                                            -
                                                        @endif
                                                    </div>
                                                    <!--end::Input-->
                                                </div>
                                                <div class="col-xl-6">
                                                    <!--begin::Input-->
                                                    <div class="form-group">
                                                        <label>SIUP/NIB</label>
                                                        @if(!empty(@optional(@optional($vendor_data->documents)->where("type","SIUP_NIB"))->last()->url))
                                                            <a class="form-text text-muted" target="_blank" href="{{ @$disk->url(@optional(@optional($vendor_data->documents)->where("type","SIUP_NIB"))->last()->url) ?? "#"}}">{{ @$disk->url(@optional(@optional($vendor_data->documents)->where("type","SIUP_NIB"))->last()->url) ?? "-"}}</a>
                                                        @else
                                                            -
                                                        @endif
                                                    </div>
                                                    <!--end::Input-->
                                                    <!--begin::Input-->
                                                    <div class="form-group">
                                                        <label>TDP</label>
                                                        @if(!empty(@optional(@optional($vendor_data->documents)->where("type","TDP"))->last()->url))
                                                            <a class="form-text text-muted" target="_blank" href="{{ @$disk->url(@optional(@optional($vendor_data->documents)->where("type","TDP"))->last()->url) ?? "#"}}">{{ @$disk->url(@optional(@optional($vendor_data->documents)->where("type","TDP"))->last()->url) ?? "-"}}</a>
                                                        @else
                                                            -
                                                        @endif
                                                    </div>
                                                    <!--end::Input-->
                                                </div>
                                            </div>
                                        </div>
                                        <!--end: Wizard Step 4-->
                                        <!--begin: Wizard Actions-->
                                        <div class="d-flex justify-content-between border-top mt-5 pt-10">
                                            <div class="mr-2">
                                                <button type="button" class="btn btn-light-primary font-weight-bolder text-uppercase px-9 py-4" data-wizard-type="action-prev">Sebelumnya</button>
                                            </div>
                                            <div>
                                                <button type="button" class="btn btn-primary font-weight-bolder text-uppercase px-9 py-4" data-wizard-type="action-next">Selanjutnya</button>
                                            </div>
                                        </div>
                                        <!--end: Wizard Actions-->
                                    </form>
                                    <!--end: Wizard Form-->
                                </div>
                            </div>
                            <!--end: Wizard Body-->
                        </div>
                        <!--end: Wizard-->
                    </div>
                </div>
            </div>
            <!--end::Container-->
        </div>
        <!--end::Entry-->
    </div>
@endsection
@section('admin_js')
    <script src="{{ asset('assets/js/pages/custom/wizard/wizard-3.js') }}"></script>
    @if(session()->has('middleware-info'))
        <script>
            Swal.fire({
                title: "Vendor Info!",
                text:  "{{ session()->get('middleware-info') }}",
                type: "info",
                confirmButtonClass: 'btn btn-primary',
                buttonsStyling: false,
            });
        </script>
    @endif
@endsection

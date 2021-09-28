@extends('includes.layout_admin')

@section('custom_title','Vendor')

@section('admin_css')
    <link href="{{ asset('assets/css/pages/wizard/wizard-1.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/pages/vendor/index.css') }}?ver=00001" rel="stylesheet" type="text/css" />
    <style>
        .select2-selection{
            background-color: #EBEDF3 !important;
            border-color: #EBEDF3 !important;
            color: #3F4254 !important;
        }
        .custom-file-label{
            white-space: nowrap;
            text-align: right;
            direction: rtl;
            padding-right: 80px;
        }
    </style>
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
                                <a href="{{route('vendor')}}" class="text-muted">@yield('custom_title')</a>
                            </li>
                            <li class="breadcrumb-item text-muted">
                                <a href="{{route('vendor-edit')}}" class="text-muted">{{empty(auth()->user()->user_ref_id)?"Lengkapi Data":"Ubah Data"}} Vendor</a>
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
                    <div id="action-loading" class="spinner spinner-primary spinner-lg mr-15"></div>
                    @if(!empty(auth()->user()->user_ref_id))
                        <a href="{{route('vendor')}}" class="btn btn-light-primary font-weight-bolder btn-sm">Batalkan</a>
                    @endif
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
                        <!--begin::Wizard-->
                        <div class="wizard wizard-1" id="kt_wizard" data-wizard-state="step-first" data-wizard-clickable="false">
                            <!--begin::Wizard Nav-->
                            <div class="wizard-nav border-bottom">
                                <div class="wizard-steps p-8 p-lg-10">
                                    <!--begin::Wizard Step 1 Nav-->
                                    <div class="wizard-step" data-wizard-type="step" data-wizard-state="current">
                                        <div class="wizard-label">
                                            <i class="wizard-icon flaticon-buildings"></i>
                                            <h3 class="wizard-title">1. Perusahaan</h3>
                                        </div>
                                        <span class="svg-icon svg-icon-xl wizard-arrow">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Navigation/Arrow-right.svg-->
															<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
																<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																	<polygon points="0 0 24 0 24 24 0 24" />
																	<rect fill="#000000" opacity="0.3" transform="translate(12.000000, 12.000000) rotate(-90.000000) translate(-12.000000, -12.000000)" x="11" y="5" width="2" height="14" rx="1" />
																	<path d="M9.70710318,15.7071045 C9.31657888,16.0976288 8.68341391,16.0976288 8.29288961,15.7071045 C7.90236532,15.3165802 7.90236532,14.6834152 8.29288961,14.2928909 L14.2928896,8.29289093 C14.6714686,7.914312 15.281055,7.90106637 15.675721,8.26284357 L21.675721,13.7628436 C22.08284,14.136036 22.1103429,14.7686034 21.7371505,15.1757223 C21.3639581,15.5828413 20.7313908,15.6103443 20.3242718,15.2371519 L15.0300721,10.3841355 L9.70710318,15.7071045 Z" fill="#000000" fill-rule="nonzero" transform="translate(14.999999, 11.999997) scale(1, -1) rotate(90.000000) translate(-14.999999, -11.999997)" />
																</g>
															</svg>
                                            <!--end::Svg Icon-->
														</span>
                                    </div>
                                    <!--end::Wizard Step 1 Nav-->
                                    <!--begin::Wizard Step 2 Nav-->
                                    <div class="wizard-step" data-wizard-type="step">
                                        <div class="wizard-label">
                                            <i class="wizard-icon flaticon-avatar"></i>
                                            <h3 class="wizard-title">2. Penandatangan</h3>
                                        </div>
                                        <span class="svg-icon svg-icon-xl wizard-arrow">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Navigation/Arrow-right.svg-->
															<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
																<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																	<polygon points="0 0 24 0 24 24 0 24" />
																	<rect fill="#000000" opacity="0.3" transform="translate(12.000000, 12.000000) rotate(-90.000000) translate(-12.000000, -12.000000)" x="11" y="5" width="2" height="14" rx="1" />
																	<path d="M9.70710318,15.7071045 C9.31657888,16.0976288 8.68341391,16.0976288 8.29288961,15.7071045 C7.90236532,15.3165802 7.90236532,14.6834152 8.29288961,14.2928909 L14.2928896,8.29289093 C14.6714686,7.914312 15.281055,7.90106637 15.675721,8.26284357 L21.675721,13.7628436 C22.08284,14.136036 22.1103429,14.7686034 21.7371505,15.1757223 C21.3639581,15.5828413 20.7313908,15.6103443 20.3242718,15.2371519 L15.0300721,10.3841355 L9.70710318,15.7071045 Z" fill="#000000" fill-rule="nonzero" transform="translate(14.999999, 11.999997) scale(1, -1) rotate(90.000000) translate(-14.999999, -11.999997)" />
																</g>
															</svg>
                                            <!--end::Svg Icon-->
														</span>
                                    </div>
                                    <!--end::Wizard Step 2 Nav-->
                                    <!--begin::Wizard Step 3 Nav-->
                                    <div class="wizard-step" data-wizard-type="step">
                                        <div class="wizard-label">
                                            <i class="wizard-icon flaticon-coins"></i>
                                            <h3 class="wizard-title">3. Bank</h3>
                                        </div>
                                        <span class="svg-icon svg-icon-xl wizard-arrow">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Navigation/Arrow-right.svg-->
															<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
																<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																	<polygon points="0 0 24 0 24 24 0 24" />
																	<rect fill="#000000" opacity="0.3" transform="translate(12.000000, 12.000000) rotate(-90.000000) translate(-12.000000, -12.000000)" x="11" y="5" width="2" height="14" rx="1" />
																	<path d="M9.70710318,15.7071045 C9.31657888,16.0976288 8.68341391,16.0976288 8.29288961,15.7071045 C7.90236532,15.3165802 7.90236532,14.6834152 8.29288961,14.2928909 L14.2928896,8.29289093 C14.6714686,7.914312 15.281055,7.90106637 15.675721,8.26284357 L21.675721,13.7628436 C22.08284,14.136036 22.1103429,14.7686034 21.7371505,15.1757223 C21.3639581,15.5828413 20.7313908,15.6103443 20.3242718,15.2371519 L15.0300721,10.3841355 L9.70710318,15.7071045 Z" fill="#000000" fill-rule="nonzero" transform="translate(14.999999, 11.999997) scale(1, -1) rotate(90.000000) translate(-14.999999, -11.999997)" />
																</g>
															</svg>
                                            <!--end::Svg Icon-->
														</span>
                                    </div>
                                    <!--end::Wizard Step 3 Nav-->
                                    <!--begin::Wizard Step 4 Nav-->
                                    <div class="wizard-step" data-wizard-type="step">
                                        <div class="wizard-label">
                                            <i class="wizard-icon flaticon-file-1"></i>
                                            <h3 class="wizard-title">4. Dokumen</h3>
                                        </div>
                                        <span class="svg-icon svg-icon-xl wizard-arrow">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Navigation/Arrow-right.svg-->
															<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
																<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																	<polygon points="0 0 24 0 24 24 0 24" />
																	<rect fill="#000000" opacity="0.3" transform="translate(12.000000, 12.000000) rotate(-90.000000) translate(-12.000000, -12.000000)" x="11" y="5" width="2" height="14" rx="1" />
																	<path d="M9.70710318,15.7071045 C9.31657888,16.0976288 8.68341391,16.0976288 8.29288961,15.7071045 C7.90236532,15.3165802 7.90236532,14.6834152 8.29288961,14.2928909 L14.2928896,8.29289093 C14.6714686,7.914312 15.281055,7.90106637 15.675721,8.26284357 L21.675721,13.7628436 C22.08284,14.136036 22.1103429,14.7686034 21.7371505,15.1757223 C21.3639581,15.5828413 20.7313908,15.6103443 20.3242718,15.2371519 L15.0300721,10.3841355 L9.70710318,15.7071045 Z" fill="#000000" fill-rule="nonzero" transform="translate(14.999999, 11.999997) scale(1, -1) rotate(90.000000) translate(-14.999999, -11.999997)" />
																</g>
															</svg>
                                            <!--end::Svg Icon-->
														</span>
                                    </div>
                                    <!--end::Wizard Step 4 Nav-->
                                    <!--begin::Wizard Step 5 Nav-->
                                    <div class="wizard-step" data-wizard-type="step">
                                        <div class="wizard-label">
                                            <i class="wizard-icon flaticon-list-1"></i>
                                            <h3 class="wizard-title">5. Submit</h3>
                                        </div>
                                        <span class="svg-icon svg-icon-xl wizard-arrow last">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Navigation/Arrow-right.svg-->
															<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
																<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																	<polygon points="0 0 24 0 24 24 0 24" />
																	<rect fill="#000000" opacity="0.3" transform="translate(12.000000, 12.000000) rotate(-90.000000) translate(-12.000000, -12.000000)" x="11" y="5" width="2" height="14" rx="1" />
																	<path d="M9.70710318,15.7071045 C9.31657888,16.0976288 8.68341391,16.0976288 8.29288961,15.7071045 C7.90236532,15.3165802 7.90236532,14.6834152 8.29288961,14.2928909 L14.2928896,8.29289093 C14.6714686,7.914312 15.281055,7.90106637 15.675721,8.26284357 L21.675721,13.7628436 C22.08284,14.136036 22.1103429,14.7686034 21.7371505,15.1757223 C21.3639581,15.5828413 20.7313908,15.6103443 20.3242718,15.2371519 L15.0300721,10.3841355 L9.70710318,15.7071045 Z" fill="#000000" fill-rule="nonzero" transform="translate(14.999999, 11.999997) scale(1, -1) rotate(90.000000) translate(-14.999999, -11.999997)" />
																</g>
															</svg>
                                            <!--end::Svg Icon-->
														</span>
                                    </div>
                                    <!--end::Wizard Step 5 Nav-->
                                </div>
                            </div>
                            <!--end::Wizard Nav-->
                            <!--begin::Wizard Body-->
                            <div class="row justify-content-center my-10 px-8 my-lg-15 px-lg-10">
                                <div class="col-xl-12 col-xxl-7">
                                    <!--begin::Wizard Form-->
                                    <form class="form" id="kt_form">
                                        <!--begin::Wizard Step 1-->
                                        <div class="pb-5" data-wizard-type="step-content" data-wizard-state="current">
                                            <h3 class="mb-10 font-weight-bold text-dark">Informasi Perusahaan</h3>
                                            <!--begin::Input-->
                                            <div class="form-group">
                                                <label>Nama Perusahaan</label>
                                                <input type="text" class="form-control form-control-solid form-control-lg" name="company_name" placeholder="Nama Perusahaan" maxlength="150"
                                                       value="{{ @$vendor_data->name }}"/>
                                                <span class="form-text text-muted">Silahkan masukan nama perusahaan.</span>
                                            </div>
                                            <!--end::Input-->
                                            <div class="row">
                                                <div class="col-xl-4">
                                                    <!--begin::Input-->
                                                    <div class="form-group">
                                                        <label>Jenis Usaha</label>
                                                        <select name="business_type" class="form-control form-control-solid form-control-lg">
                                                            <option value="">Pilih Jenis Usaha</option>
                                                            @foreach($list_business_type as $business_type)
                                                                <option value="{{$business_type->name}}"
                                                                {{ @$vendor_data->business_type !== $business_type->name ?: "selected"}}>
                                                                    {{$business_type->name}}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <span class="form-text text-muted">Silahkan pilih jenis usaha.</span>
                                                    </div>
                                                    <!--end::Input-->
                                                </div>
                                                <div class="col-xl-4">
                                                    <!--begin::Input-->
                                                    <div class="form-group">
                                                        <label>Kelas Usaha</label>
                                                        <select name="business_class" class="form-control form-control-solid form-control-lg">
                                                            <option value="">Pilih Kelas Usaha</option>
                                                            @foreach($list_business_class as $business_class)
                                                                <option value="{{$business_class->name}}"
                                                                    {{ @$vendor_data->business_class !== $business_class->name ?: "selected"}}
                                                                >{{$business_class->name}}</option>
                                                            @endforeach
                                                        </select>
                                                        <span class="form-text text-muted">Silahkan pilih kelas usaha.</span>
                                                    </div>
                                                    <!--end::Input-->
                                                </div>
                                                <div class="col-xl-4">
                                                    <!--begin::Input-->
                                                    <div class="form-group">
                                                        <label>Status</label>
                                                        <select id="legal_status_selection" name="legal_status" class="form-control form-control-solid form-control-lg">
                                                            <option value="">Pilih Status</option>
                                                            @foreach($list_legal_status as $legal_status)
                                                                <option value="{{$legal_status->name}}"
                                                                    {{ @$vendor_data->tax_status !== $legal_status->name ?: "selected"}}
                                                                >{{$legal_status->name}}</option>
                                                            @endforeach
                                                        </select>
                                                        <span class="form-text text-muted">Silahkan pilih status.</span>
                                                    </div>
                                                    <!--end::Input-->
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xl-4">
                                                    <!--begin::Input-->
                                                    <div class="form-group">
                                                        <label>NPWP</label>
                                                        <input type="text" class="form-control form-control-solid form-control-lg" name="npwp" placeholder="NPWP" maxlength="15" minlength="15"
                                                               value="{{ @$vendor_data->npwp }}"/>
                                                        <span class="form-text text-muted">Silahkan masukan NPWP.</span>
                                                    </div>
                                                    <!--end::Input-->
                                                </div>
                                                <div class="col-xl-4">
                                                    <!--begin::Input-->
                                                    <div class="form-group">
                                                        <label>SIUP/NIB</label>
                                                        <input type="text" class="form-control form-control-solid form-control-lg" name="siup_nib" placeholder="SIUP/NIB" maxlength="13" minlength="13"
                                                               value="{{ @$vendor_data->siup_nib }}" />
                                                        <span class="form-text text-muted">Silahkan masukan SIUP/NIB.</span>
                                                    </div>
                                                    <!--end::Input-->
                                                </div>
                                                <div class="col-xl-4">
                                                    <!--begin::Input-->
                                                    <div class="form-group">
                                                        <label>TDP</label>
                                                        <input type="text" class="form-control form-control-solid form-control-lg" name="tdp" placeholder="TDP" maxlength="30"
                                                               value="{{ @$vendor_data->tdp }}"/>
                                                        <span class="form-text text-muted">Silahkan masukan TDP.</span>
                                                    </div>
                                                    <!--end::Input-->
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xl-6">
                                                    <!--begin::Input-->
                                                    <div class="form-group">
                                                        <label>Alamat</label>
                                                        <textarea class="form-control form-control-solid form-control-lg" name="address" placeholder="Alamat" style="height: 300px;" maxlength="250">{{ @$vendor_data->address }}</textarea>
                                                        <span class="form-text text-muted">Silahkan masukan alamat lengkap.</span>
                                                    </div>
                                                    <!--end::Input-->
                                                </div>
                                                <div class="col-xl-6">
                                                    <!--begin::Input-->
                                                    <div class="form-group">
                                                        <label>Geolokasi</label>
                                                        <div style="display: none;">
                                                            <input type="text" class="form-control form-control-solid form-control-lg bg-white" id="pac-input"
                                                                   style="margin-left: 8px;margin-top: 8px;max-width: 300px;width: 100%;" placeholder="Cari lokasi" />
                                                            <input type="hidden" name="geolocation" id="input-geolocation" class="form-control form-control-solid form-control-lg"
                                                                   value="{{ @$vendor_data->address_latitude }}@if(!empty(@$vendor_data->address_latitude) && !empty( @$vendor_data->address_longitude)),@endif{{ @$vendor_data->address_longitude }}"/>
                                                        </div>
                                                        <div id="geolocation" class="form-control form-control-solid form-control-lg" style="height: 300px"></div>
                                                        <span class="form-text text-muted" id="info-geolocation">Pilih lokasi.</span>
                                                    </div>
                                                    <!--end::Input-->
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xl-6">
                                                    <!--begin::Input-->
                                                    <div class="form-group">
                                                        <label>Wilayah</label>
                                                        <select id="region-list" name="region" class="form-control form-control-solid form-control-lg">
                                                            <option value="">Pilih Wilayah</option>
                                                            @foreach($list_regions as $region)
                                                                <option value="{{$region->region_id}}"
                                                                    {{ @$vendor_data->address_region_id !== $region->region_id ?: "selected"}}
                                                                >{{$region->province}} / {{$region->kota}}</option>
                                                            @endforeach
                                                        </select>
                                                        <span class="form-text text-muted">Silahkan pilih wilayah.</span>
                                                    </div>
                                                    <!--end::Input-->
                                                </div>
                                                <div class="col-xl-6">
                                                    <!--begin::Input-->
                                                    <div class="form-group">
                                                        <label>Kecamatan</label>
                                                        <select id="district-list" name="districts" class="form-control form-control-solid form-control-lg">
                                                            <option value="">Pilih Kecamatan</option>
                                                            @if(!@empty(@$vendor_data->address_region_id))
                                                                @foreach((object)@$vendor_data->region->districts as $district)
                                                                    <option value="{{$district->id}}"
                                                                        {{ @$vendor_data->address_district_id !== $district->id ?: "selected"}}
                                                                    >{{$district->name}}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                        <span class="form-text text-muted">Silahkan pilih kecamatan.</span>
                                                    </div>
                                                    <!--end::Input-->
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xl-4">
                                                    <!--begin::Input-->
                                                    <div class="form-group">
                                                        <label>Kode Pos</label>
                                                        <input type="text" class="form-control form-control-solid form-control-lg" name="zip-code" placeholder="Kode Pos" maxlength="5" minlength="5"
                                                               value="{{ @$vendor_data->address_zip_code }}"/>
                                                        <span class="form-text text-muted">Silahkan masukan Kode Pos.</span>
                                                    </div>
                                                    <!--end::Input-->
                                                </div>
                                                <div class="col-xl-4">
                                                    <!--begin::Input-->
                                                    <div class="form-group row">
                                                        <label class="col-xl-3 col-lg-3 col-form-label text-right">Logo</label>
                                                        <div class="col-lg-9 col-xl-6">
                                                            <div class="image-input image-input-outline" id="kt_image_1">
                                                                <div {{ empty(auth()->user()->user_ref_id)?: "id=profile_avatar_edit" }} class="image-input-wrapper" style="background-image: url({{!empty(@$vendor_data->avatar_img)?@$disk->url(@$vendor_data->avatar_img):asset('assets/media/users/default.jpg')}})"></div>
                                                                <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Upload logo usaha">
                                                                    <i class="fa fa-pen icon-sm text-muted"></i>
                                                                    <input type="file" name="profile_avatar" accept=".png, .jpg, .jpeg" />
                                                                    <input type="hidden" name="profile_avatar_remove" />
                                                                </label>
                                                                <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Batal">
															<i class="ki ki-bold-close icon-xs text-muted"></i>
														</span>
                                                            </div>
                                                            <span class="form-text text-muted">Allowed file types: png, jpg, jpeg.</span>
                                                        </div>
                                                    </div>
                                                    <!--end::Input-->
                                                </div>
                                                <div class="col-xl-4">
                                                    <!--begin::Input-->
                                                    <div class="form-group">
                                                        <label>Email</label>
                                                        <input type="email" class="form-control form-control-solid form-control-lg" name="email" placeholder="Email"
                                                               value="{{ @$vendor_data->email }}"/>
                                                        <span class="form-text text-muted">Silahkan masukan email.</span>
                                                    </div>
                                                    <!--end::Input-->
                                                </div>
                                            </div>
                                        </div>
                                        <!--end::Wizard Step 1-->
                                        <!--begin::Wizard Step 2-->
                                        <div class="pb-5" data-wizard-type="step-content">
                                            <h4 class="font-weight-bold text-dark">Penanggungjawab / Penandatangan</h4>
                                            <label class="mb-10">Direksi/pimpinan perusahaan yang akan dicantumkan dalam dokumen administrasi (Berita Acara Serah Terima, Invoice, dll)</label>
                                            <div class="row">
                                                <div class="col-xl-6">
                                                    <!--begin::Input-->
                                                    <div class="form-group">
                                                        <label>Nama Lengkap</label>
                                                        <input type="text" class="form-control form-control-solid form-control-lg" name="pic-fullname" placeholder="Nama Lengkap" maxlength="100"
                                                               value="{{ @$vendor_data->pic_name }}"/>
                                                        <span class="form-text text-muted">Silahkan masukan Nama Lengkap.</span>
                                                    </div>
                                                    <!--end::Input-->
                                                </div>
                                                <div class="col-xl-6">
                                                    <!--begin::Input-->
                                                    <div class="form-group">
                                                        <label>NIK/KTP</label>
                                                        <input type="text" class="form-control form-control-solid form-control-lg" name="pic-nik" placeholder="NIK/KTP" maxlength="16"
                                                               value="{{ @$vendor_data->pic_id_card_no }}"/>
                                                        <span class="form-text text-muted">Silahkan masukan NIK/KTP.</span>
                                                    </div>
                                                    <!--end::Input-->
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xl-6">
                                                    <!--begin::Input-->
                                                    <div class="form-group">
                                                        <label>Jabatan</label>
                                                        <input type="text" class="form-control form-control-solid form-control-lg" name="pic-title" placeholder="Jabatan" maxlength="50"
                                                               value="{{ @$vendor_data->pic_position }}"/>
                                                        <span class="form-text text-muted">Silahkan masukan Jabatan.</span>
                                                    </div>
                                                    <!--end::Input-->
                                                </div>
                                                <div class="col-xl-6">
                                                    <!--begin::Input-->
                                                    <div class="form-group">
                                                        <label>Email</label>
                                                        <input type="email" class="form-control form-control-solid form-control-lg" name="pic-email" placeholder="Email" maxlength="50"
                                                               value="{{ @$vendor_data->pic_email }}"/>
                                                        <span class="form-text text-muted">Silahkan masukan Email.</span>
                                                    </div>
                                                    <!--end::Input-->
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xl-6">
                                                    <!--begin::Input-->
                                                    <div class="form-group">
                                                        <label>Nomor Ponsel</label>
                                                        <input type="text" class="form-control form-control-solid form-control-lg" name="pic-phone" placeholder="Nomor Ponsel" maxlength="50"
                                                               value="{{ @$vendor_data->phone2 }}"/>
                                                        <span class="form-text text-muted">Silahkan masukan Nomor Ponsel.</span>
                                                    </div>
                                                    <!--end::Input-->
                                                </div>
                                                <div class="col-xl-6">
                                                    <!--begin::Input-->
                                                    <div class="form-group">
                                                        <label>Nomor Telpon Kantor</label>
                                                        <input type="text" class="form-control form-control-solid form-control-lg" name="pic-office-phone" placeholder="Nomor Telpon Kantor" maxlength="50"
                                                               value="{{ @$vendor_data->phone1 }}"/>
                                                        <span class="form-text text-muted">Silahkan masukan Nomor Telpon Kantor.</span>
                                                    </div>
                                                    <!--end::Input-->
                                                </div>
                                            </div>
                                        </div>
                                        <!--end::Wizard Step 2-->
                                        <!--begin::Wizard Step 3-->
                                        <div class="pb-5" data-wizard-type="step-content">
                                            <h4 class="mb-10 font-weight-bold text-dark">Informasi Bank</h4>
                                            <div class="row">
                                                <div class="col-xl-6">
                                                    <!--begin::Input-->
                                                    <div class="form-group">
                                                        <label>Nama Bank</label>
                                                        <input type="text" class="form-control form-control-solid form-control-lg" name="bank-name" placeholder="Nama Bank" maxlength="100"
                                                               value="{{ @$vendor_data->bank_name }}"/>
                                                        <span class="form-text text-muted">Silahkan masukan Nama Bank.</span>
                                                    </div>
                                                    <!--end::Input-->
                                                </div>
                                                <div class="col-xl-6">
                                                    <!--begin::Input-->
                                                    <div class="form-group">
                                                        <label>Nama Pemilik Bank</label>
                                                        <input type="text" class="form-control form-control-solid form-control-lg" name="bank-owner-name" placeholder="Nama Pemilik Bank" maxlength="100"
                                                               value="{{ @$vendor_data->bank_account_name }}"/>
                                                        <span class="form-text text-muted">Silahkan masukan Nama Pemilik Bank.</span>
                                                    </div>
                                                    <!--end::Input-->
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xl-6">
                                                    <!--begin::Input-->
                                                    <div class="form-group">
                                                        <label>Nomor Rekening</label>
                                                        <input type="text" class="form-control form-control-solid form-control-lg" name="bank-number" placeholder="Nomor Rekening" maxlength="30"
                                                               value="{{ @$vendor_data->bank_account_no }}"/>
                                                        <span class="form-text text-muted">Silahkan masukan Nomor Rekening.</span>
                                                    </div>
                                                    <!--end::Input-->
                                                </div>
                                                <div class="col-xl-6">
                                                    <!--begin::Input-->
                                                    <div class="form-group">
                                                        <label>Kantor Cabang Bank</label>
                                                        <input type="text" class="form-control form-control-solid form-control-lg" name="bank-branch" placeholder="Kantor Cabang Bank" maxlength="100"
                                                               value="{{ @$vendor_data->bank_branch_name }}"/>
                                                        <span class="form-text text-muted">Silahkan masukan Kantor Cabang Bank.</span>
                                                    </div>
                                                    <!--end::Input-->
                                                </div>
                                            </div>
                                        </div>
                                        <!--end::Wizard Step 3-->
                                        <!--begin::Wizard Step 4-->
                                        <div class="pb-5" data-wizard-type="step-content">
                                            <h4 class="mb-10 font-weight-bold text-dark">Dokumen</h4>
                                            <div class="row">
                                                <div class="col-xl-6">
                                                    <!--begin::Input-->
                                                    <div class="form-group">
                                                        <label>KTP Penanggungjawab</label>
                                                        <div></div>
                                                        <div class="custom-file">
                                                            <input type="file" name="ktp-file" class="custom-file-input" id="ktp-file" accept="application/pdf, image/*" />
                                                            <label class="custom-file-label" for="ktp-file">{{ @optional(@optional($vendor_data->documents)->where("type","KTP"))->last()->url ?? "Pilih File KTP Penanggungjawab"}}</label>
                                                        </div>
                                                    </div>
                                                    <!--end::Input-->
                                                </div>
                                                <div class="col-xl-6">
                                                    <!--begin::Input-->
                                                    <div class="form-group">
                                                        <label>SIUP/NIB</label>
                                                        <div></div>
                                                        <div class="custom-file">
                                                            <input type="file" name="siup-nib-file" class="custom-file-input" id="siup-nib-file" accept="application/pdf, image/*" />
                                                            <label class="custom-file-label" for="siup-nib-file">{{ @optional(@optional($vendor_data->documents)->where("type","SIUP_NIB"))->last()->url ?? "Pilih File SIUP/NIB" }}</label>
                                                        </div>
                                                    </div>
                                                    <!--end::Input-->
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xl-6">
                                                    <!--begin::Input-->
                                                    <div class="form-group">
                                                        <label>NPWP</label>
                                                        <div></div>
                                                        <div class="custom-file">
                                                            <input type="file" name="npwp-file" class="custom-file-input" id="npwp-file" accept="application/pdf, image/*" />
                                                            <label class="custom-file-label" for="npwp-file">{{ @optional(@optional($vendor_data->documents)->where("type","NPWP"))->last()->url ?? "Pilih File NPWP" }}</label>
                                                        </div>
                                                    </div>
                                                    <!--end::Input-->
                                                </div>
                                                <div class="col-xl-6">
                                                    <!--begin::Input-->
                                                    <div class="form-group">
                                                        <label>TDP</label>
                                                        <div></div>
                                                        <div class="custom-file">
                                                            <input type="file" name="tdp-file" class="custom-file-input" id="tdp-file" accept="application/pdf, image/*" />
                                                            <label class="custom-file-label" for="tdp-file">{{ @optional(@optional($vendor_data->documents)->where("type","TDP"))->last()->url ?? "Pilih File TDP" }}</label>
                                                        </div>
                                                    </div>
                                                    <!--end::Input-->
                                                </div>
                                            </div>
                                        </div>
                                        <!--end::Wizard Step 4-->
                                        <!--begin::Wizard Step 5-->
                                        <div class="pb-5" data-wizard-type="step-content">
                                            <!--begin::Section-->
                                            <span class="form-text text-muted">Dengan menekan tombol submit berarti anda telah meninjau dan menyetujui
                                                <a href="https://dev-siplah.klikmro.com/ketentuan-penyedia" target="_blank">Ketentuan Penyedia</a>.
                                            </span>
                                        </div>
                                        <!--end::Wizard Step 5-->
                                        <!--begin::Wizard Actions-->
                                        <div class="d-flex justify-content-between border-top mt-5 pt-10">
                                            <div class="mr-2">
                                                <button type="button" class="btn btn-light-primary font-weight-bolder text-uppercase px-9 py-4" data-wizard-type="action-prev">Previous</button>
                                            </div>
                                            <div>
                                                <button type="button" class="btn btn-success font-weight-bolder text-uppercase px-9 py-4" data-wizard-type="action-submit">{{empty(auth()->user()->user_ref_id)?"Submit Data":"Perbaharui Data"}}</button>
                                                <button type="button" class="btn btn-primary font-weight-bolder text-uppercase px-9 py-4" data-wizard-type="action-next">Next</button>
                                            </div>
                                        </div>
                                        <!--end::Wizard Actions-->
                                    </form>
                                    <!--end::Wizard Form-->
                                </div>
                            </div>
                            <!--end::Wizard Body-->
                        </div>
                        <!--end::Wizard-->
                    </div>
                    <!--end::Wizard-->
                </div>
            </div>
            <!--end::Container-->
        </div>
        <!--end::Entry-->
    </div>
@endsection
@section('admin_js')
    <script src="{{ asset('assets/js/pages/custom/wizard/wizard-1.js') }}?ver=00004"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCYYFIUiRodqWKw-5Lao7x59KzKsWEJFTU&callback=initMap&libraries=places&v=weekly" async></script>
    <script src="{{ asset('js/pages/vendor/index.js') }}?ver=00005"></script>
    <script src="{{ asset('assets/js/pages/crud/file-upload/image-input.js') }}"></script>
    <script src="{{ asset('assets/plugins/custom/form/form.min.js') }}"></script>
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

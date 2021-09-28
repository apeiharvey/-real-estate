@extends('includes.layout_admin')

@section('custom_title','Pesan')

@section('admin_css')
    <link href="{{asset('assets/plugins/custom/lightbox/ekko-lightbox.css')}}" rel="stylesheet" type="text/css"/>
    <style>
        .treat-msg{
            white-space: pre;
            white-space: pre-line;
        }
        input::-webkit-input-placeholder {
            font-size: 10px;
            line-height: 3;
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
                <!--begin::Chat-->
                <div class="d-flex flex-row">
                    <!--begin::Aside-->
                    <div class="flex-row-auto offcanvas-mobile w-350px w-xl-400px" id="kt_chat_aside">
                        <!--begin::Card-->
                        <div class="card card-custom overlay rounded" id="complaint_rows_more_loading_container">
                            <!--begin::Body-->
                            <div class="card-body overlay-wrapper">
                                <!--begin:Search-->
                                <div class="d-flex justify-content-center mb-5">
                                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                        <label class="btn btn-primary btn-lg active">
                                            <input type="radio" name="search_type" data-type="transaction" checked="checked"/> Transaksi
                                        </label>
                                        <label class="btn btn-primary btn-lg">
                                            <input type="radio" name="search_type" data-type="customer"/> Non-Transaksi
                                        </label>
                                    </div>
                                </div>
                                <div class="input-group input-group-solid">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <span class="svg-icon svg-icon-lg">
                                                <!--begin::Svg Icon | path:assets/media/svg/icons/General/Search.svg-->
                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                        <rect x="0" y="0" width="24" height="24" />
                                                        <path d="M14.2928932,16.7071068 C13.9023689,16.3165825 13.9023689,15.6834175 14.2928932,15.2928932 C14.6834175,14.9023689 15.3165825,14.9023689 15.7071068,15.2928932 L19.7071068,19.2928932 C20.0976311,19.6834175 20.0976311,20.3165825 19.7071068,20.7071068 C19.3165825,21.0976311 18.6834175,21.0976311 18.2928932,20.7071068 L14.2928932,16.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                                        <path d="M11,16 C13.7614237,16 16,13.7614237 16,11 C16,8.23857625 13.7614237,6 11,6 C8.23857625,6 6,8.23857625 6,11 C6,13.7614237 8.23857625,16 11,16 Z M11,18 C7.13400675,18 4,14.8659932 4,11 C4,7.13400675 7.13400675,4 11,4 C14.8659932,4 18,7.13400675 18,11 C18,14.8659932 14.8659932,18 11,18 Z" fill="#000000" fill-rule="nonzero" />
                                                    </g>
                                                </svg>
                                                <!--end::Svg Icon-->
                                            </span>
                                        </span>
                                    </div>
                                    <input  type="text" class="form-control py-4 h-auto" name="search" onchange="reloadComplainList()"
                                            placeholder="Cari dengan nama pelanggan atau no order"/>

                                </div>
                                <!--end:Search-->
                                <!--begin:Users-->
                                <div class="mt-7 scroll scroll-pull" id="complaint_rows_more"></div>
                                <!--end:Users-->
                            </div>

                            <div class="overlay-layer rounded bg-primary-o-20" id="complaint_rows_more_loading" style="display:none">
                                <div class="spinner spinner-primary"></div>
                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end::Card-->
                    </div>
                    <!--end::Aside-->
                    <!--begin::Content-->
                    <div class="flex-row-fluid ml-lg-8" id="kt_chat_content">
                        <!--begin::Card-->
                        <div class="card card-custom" id="complaint_row_detail_card">
                            <!--begin::Header-->
                            <div class="card-header align-items-center px-4 py-3" style="display:flex">
                                <div class="text-left flex-grow-1">
                                    <!--begin::Aside Mobile Toggle-->
                                    <button type="button" class="btn btn-clean btn-sm btn-icon btn-icon-md d-lg-none" id="kt_app_chat_toggle">
                                        <span class="svg-icon svg-icon-lg">
                                            <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Adress-book2.svg-->
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24" />
                                                    <path d="M18,2 L20,2 C21.6568542,2 23,3.34314575 23,5 L23,19 C23,20.6568542 21.6568542,22 20,22 L18,22 L18,2 Z" fill="#000000" opacity="0.3" />
                                                    <path d="M5,2 L17,2 C18.6568542,2 20,3.34314575 20,5 L20,19 C20,20.6568542 18.6568542,22 17,22 L5,22 C4.44771525,22 4,21.5522847 4,21 L4,3 C4,2.44771525 4.44771525,2 5,2 Z M12,11 C13.1045695,11 14,10.1045695 14,9 C14,7.8954305 13.1045695,7 12,7 C10.8954305,7 10,7.8954305 10,9 C10,10.1045695 10.8954305,11 12,11 Z M7.00036205,16.4995035 C6.98863236,16.6619875 7.26484009,17 7.4041679,17 C11.463736,17 14.5228466,17 16.5815,17 C16.9988413,17 17.0053266,16.6221713 16.9988413,16.5 C16.8360465,13.4332455 14.6506758,12 11.9907452,12 C9.36772908,12 7.21569918,13.5165724 7.00036205,16.4995035 Z" fill="#000000" />
                                                </g>
                                            </svg>
                                            <!--end::Svg Icon-->
                                        </span>
                                    </button>
                                    <!--end::Aside Mobile Toggle-->
                                    <!--begin::Dropdown Menu-->
                                    <div class="dropdown dropdown-inline">
                                        <button type="button" class="btn btn-clean btn-sm btn-icon btn-icon-md on-not-blank" style="display:none"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="ki ki-bold-more-hor icon-md"></i>
                                        </button>
                                        <div class="dropdown-menu p-0 m-0 dropdown-menu-left dropdown-menu-md">
                                            <!--begin::Navigation-->
                                            <ul class="navi navi-hover py-5">
                                                <li class="navi-item">
                                                    <a href="#" class="navi-link download-script">
                                                        <span class="navi-icon">
                                                            <i class="flaticon2-printer"></i>
                                                        </span>
                                                        <span class="navi-text">Cetak Transkrip</span>
                                                    </a>
                                                </li>
                                                <!-- <li class="navi-separator my-3"></li> -->
                                            </ul>
                                            <!--end::Navigation-->
                                        </div>
                                    </div>
                                    <!--end::Dropdown Menu-->
                                </div>
                                <div class="text-center flex-grow-1">
                                    <div class="text-dark-75 font-weight-bold font-size-h5" id="complaint_detail_cust_name"></div>
                                    <!-- <div>
                                        <span class="label label-sm label-dot label-success"></span>
                                        <span class="font-weight-bold text-muted font-size-sm">Active</span>
                                    </div> -->
                                </div>
                                <div class="text-right flex-grow-1">
                                    <!--begin::Dropdown Menu-->
                                    <!--end::Dropdown Menu-->
                                </div>
                            </div>
                            <!--end::Header-->
                            <!--begin::Body-->
                            <div class="card-body overlay rounded" id="complaint_row_detail_more_loading_container">
                                <!--begin::Scroll-->
                                <div class="scroll scroll-pull overlay-wrapper" data-mobile-height="350">
                                    <!--begin::Messages-->
                                    <div class="messages" id="complaint_row_detail_more"></div>
                                    <!--end::Messages-->
                                </div>
                                <div class="overlay-layer rounded bg-primary-o-20" id="complaint_row_detail_more_loading" style="display:none">
                                    <div class="spinner spinner-primary"></div>
                                </div>
                                <!--end::Scroll-->
                            </div>
                            <!--end::Body-->
                            <!--begin::Footer-->
                            <div class="card-footer align-items-center" style="padding: 0.5rem 2rem !important;">
                                <!--begin::Attachments-->
                                <div class="dropzone dropzone-multi px-8 py-1" id="kt_inbox_compose_attachments">
                                    <div class="dropzone-items">
                                        <div class="dropzone-item" style="display:none">
                                            <div class="dropzone-file">
                                                <div class="dropzone-filename" title="some_image_file_name.jpg">
                                                    <span data-dz-name="">some_image_file_name.jpg</span>
                                                    <strong>(
                                                    <span data-dz-size="">340kb</span>)</strong>
                                                </div>
                                                <div class="dropzone-error" data-dz-errormessage=""></div>
                                            </div>
                                            <div class="dropzone-progress">
                                                <div class="progress">
                                                    <div class="progress-bar bg-primary" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0" data-dz-uploadprogress=""></div>
                                                </div>
                                            </div>
                                            <div class="dropzone-toolbar">
                                                <span class="dropzone-delete" data-dz-remove="">
                                                    <i class="flaticon2-cross"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end::Attachments-->
                                <input name="vendor_name" type="text" hidden>
                                <input name="vendor_pp_src" type="text" hidden>
                                <input name="ref_id_hash" type="text" hidden>
                                <input name="room_hash" type="text" hidden>
                                <input name="room_hash_get_url" type="text" value="{{@$_GET['rh']}}" hidden>
                                <!--begin::Compose-->
                                <form id="addForm" onsubmit="return false;">
                                    <textarea   name="message" class="add-form-el form-control border-0 p-0 on-not-blank" rows="2" placeholder="Ketik pesan disini ..." required
                                                style="display:none"></textarea>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="mr-3">
                                            <a href="#" class="add-form-el btn btn-clean btn-icon btn-md mr-1 on-not-blank"  id="kt_inbox_compose_attachments_select" style="display:none">
                                                <i class="flaticon2-photograph icon-lg"></i>
                                            </a>
                                        </div>
                                        <div>
                                            <button type="button" class="add-form-el btn btn-primary btn-md text-uppercase font-weight-bold py-2 px-6 button-action button-send on-not-blank"
                                                    style="display:none">
                                                    Kirim
                                            </button>
                                        </div>
                                    </div>
                                </form>
                                <!--begin::Compose-->
                            </div>
                            <!--end::Footer-->
                        </div>
                        <!--end::Card-->
                    </div>
                    <!--end::Content-->
                </div>
                <!--end::Chat-->
            </div>
            <!--end::Container-->
        </div>
        <!--end::Entry-->
    </div>
@endsection
@section('admin_js')
    <script src="{{ asset('assets/js/pages/crud/forms/widgets/select2.js') }}"></script>
    <script src="{{ asset('assets/plugins/custom/form/form.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/custom/lightbox/ekko-lightbox.min.js') }}"></script>
    <script src="{{ asset('assets/js/libraries/jquery-ui.min.js') }}"></script>
	<script src="{{ asset('assets/js/pages/custom/chat/chat.js') }}"></script>
    <script src="{{ asset('js/pages/messaging/index.js') }}?ver=000020"></script>
@endsection


@extends('includes.layout_admin')

@section('custom_title','Produk')

@section('admin_css')
    <style>
        .centerize{
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
    </style>
@endsection

@section('pages')
    <div class="content pt-0 d-flex flex-column flex-column-fluid" id="kt_content">

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
                                <a href="{{url()->current()}}" class="text-muted">Unggah Produk</a>
                            </li>
                        </ul>
                        <!--end::Breadcrumb-->
                    </div>
                    <!--end::Page Heading-->
                </div>
                <!--end::Info-->
                <!--begin::Toolbar-->
                <div class="d-flex align-items-center">
                    <!--begin::Dropdown-->
                    <div class="dropdown dropdown-inline">
                        <a href="#" class="btn btn-light-primary font-weight-bolder btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Tambah Produk
                        </a>
                        <div class="dropdown-menu dropdown-menu-md dropdown-menu-right p-0 m-0">
                            <!--begin::Navigation-->
                            <ul class="navi navi-hover">
                                <li class="navi-header font-weight-bold py-4">
                                    <span class="font-size-lg">Pilih opsi:</span>
                                </li>
                                <li class="navi-separator mb-3 opacity-70"></li>
                                <li class="navi-footer py-4">
                                    <a class="btn btn-clean font-weight-bold btn-sm" href="{{route('product-form-add')}}">
                                        <i class="ki ki-plus icon-sm"></i>Tambah Melalui Form</a>
                                </li>
                                <li class="navi-footer py-4">
                                    <a class="btn btn-clean font-weight-bold btn-sm" href="{{route('product-form-upload')}}">
                                        <i class="ki ki-long-arrow-up icon-sm"></i>Unggah Melalui File CSV</a>
                                </li>
                            </ul>
                            <!--end::Navigation-->
                        </div>
                    </div>
                    <!--end::Dropdown-->
                </div>
                <!--end::Toolbar-->
            </div>
        </div>
        <!--end::Subheader-->

        <!--begin::Entry-->
        <!--begin::Hero-->
        <div class="d-flex flex-row-fluid bgi-size-cover bgi-position-top" style="background-image: url({{asset('assets/media/bg/bg-8.jpg')}}">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center pt-25 pb-35">
                    <h2 class="font-weight-bolder text-light mb-0">Tambah Produk (Unggah Bulk)</h2>
                    <div class="d-flex">
                        <a  class="h5 text-light font-weight-bold mr-5" href="{{ asset('assets/media/doc/siplah-template_bulk_upload_product.csv') }}"
                            id="download-upload-template-link" download hidden>Unduh Template
                        </a>
                        <a  class="h5 text-light font-weight-bold mr-5" href="https://drive.google.com/file/d/1v93w2-EHNKeFUKaf3ioBlQKfP5Cjz1Qo/view?usp=sharing"
                            id="guide-filling-template-link" download hidden target="_blank">Panduan Pengisian
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Hero-->
        <!--begin::Section-->
        <div class="container mt-n15 gutter-b">
            <div class="card card-custom">
                <div class="card-body py-12">                
                        <div class="row mb-10">
                            <div class="centerize">
                            
                                <div class="dropzone dropzone-default dropzone-success" id="dataListUpload">
                                    <div class="dropzone-msg dz-message needsclick">
                                        <h3 class="dropzone-msg-title">Taruh file disini atau <span class="text-success">cari</span></h3>
                                        <span class="dropzone-msg-desc">Hanya CSV sesuai format template</span>
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
                    <div class="d-flex justify-content-center mb-10" style="margin-top:100px">
                        <span>Histori:</span>
                        <!--begin: Datatable-->
                        <div class="datatable datatable-bordered datatable-head-custom" id="kt_datatable"></div>
                        <!--end: Datatable-->
                    </div>

                </div>
            </div>
        </div>
        <!--end::Section-->
        <!--begin::Section-->
        <div class="container gutter-b">
            <div class="row">
                <div class="col-lg-6">
                    <!--begin::Callout-->
                    <a href="#" class="card card-custom wave wave-animate wave-success mb-8 mb-lg-0">
                        <div class="card-body">
                            <div class="d-flex align-items-center p-6">
                                <!--begin::Icon-->
                                <div class="mr-6">
                                    <span class="svg-icon svg-icon-4x">
                                        <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Clipboard-check.svg-->
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24" />
                                                <path d="M8,3 L8,3.5 C8,4.32842712 8.67157288,5 9.5,5 L14.5,5 C15.3284271,5 16,4.32842712 16,3.5 L16,3 L18,3 C19.1045695,3 20,3.8954305 20,5 L20,21 C20,22.1045695 19.1045695,23 18,23 L6,23 C4.8954305,23 4,22.1045695 4,21 L4,5 C4,3.8954305 4.8954305,3 6,3 L8,3 Z" fill="#000000" opacity="0.3" />
                                                <path d="M10.875,15.75 C10.6354167,15.75 10.3958333,15.6541667 10.2041667,15.4625 L8.2875,13.5458333 C7.90416667,13.1625 7.90416667,12.5875 8.2875,12.2041667 C8.67083333,11.8208333 9.29375,11.8208333 9.62916667,12.2041667 L10.875,13.45 L14.0375,10.2875 C14.4208333,9.90416667 14.9958333,9.90416667 15.3791667,10.2875 C15.7625,10.6708333 15.7625,11.2458333 15.3791667,11.6291667 L11.5458333,15.4625 C11.3541667,15.6541667 11.1145833,15.75 10.875,15.75 Z" fill="#000000" />
                                                <path d="M11,2 C11,1.44771525 11.4477153,1 12,1 C12.5522847,1 13,1.44771525 13,2 L14.5,2 C14.7761424,2 15,2.22385763 15,2.5 L15,3.5 C15,3.77614237 14.7761424,4 14.5,4 L9.5,4 C9.22385763,4 9,3.77614237 9,3.5 L9,2.5 C9,2.22385763 9.22385763,2 9.5,2 L11,2 Z" fill="#000000" />
                                            </g>
                                        </svg>
                                        <!--end::Svg Icon-->
                                    </span>
                                </div>
                                <!--end::Icon-->
                                <!--begin::Content-->
                                <div class="d-flex flex-column">
                                    <h3 class="text-dark h3 mb-3">1.</h3>
                                    <div class="text-dark-50">
                                        Unduh Template CSV
                                        <div class="btn btn-success font-weight-bolder font-size-lg py-3 px-6 ml-10 download-upload-template">
                                            Unduh Disini
                                        </div>
                                    </div>
                                </div>
                                <!--end::Content-->
                            </div>
                        </div>
                    </a>
                    <!--end::Callout-->
                </div>
                <div class="col-lg-6">
                    <!--begin::Callout-->
                    <a href="#" class="card card-custom wave wave-animate-fast wave-success mb-8 mb-lg-0">
                        <div class="card-body">
                            <div class="d-flex align-items-center p-6">
                                <!--begin::Icon-->
                                <div class="mr-6">
                                    <span class="svg-icon svg-icon-4x">
                                        <!--begin::Svg Icon | path:assets/media/svg/icons/General/User.svg-->
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <polygon points="0 0 24 0 24 24 0 24" />
                                                <path d="M12,11 C9.790861,11 8,9.209139 8,7 C8,4.790861 9.790861,3 12,3 C14.209139,3 16,4.790861 16,7 C16,9.209139 14.209139,11 12,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                                <path d="M3.00065168,20.1992055 C3.38825852,15.4265159 7.26191235,13 11.9833413,13 C16.7712164,13 20.7048837,15.2931929 20.9979143,20.2 C21.0095879,20.3954741 20.9979143,21 20.2466999,21 C16.541124,21 11.0347247,21 3.72750223,21 C3.47671215,21 2.97953825,20.45918 3.00065168,20.1992055 Z" fill="#000000" fill-rule="nonzero" />
                                            </g>
                                        </svg>
                                        <!--end::Svg Icon-->
                                    </span>
                                </div>
                                <!--end::Icon-->
                                <!--begin::Content-->
                                <div class="d-flex flex-column guide-filling-template">
                                    <h3 class="text-dark h3 mb-3">2.</h3>
                                    <div class="text-dark-50">
                                        Panduan Pengisian Template
                                        <span class="svg-icon svg-icon-success svg-icon-2x">
                                            <!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\themes\metronic\theme\html\demo1\dist/../src/media/svg/icons\Navigation\Angle-double-right.svg-->
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <polygon points="0 0 24 0 24 24 0 24"/>
                                                    <path d="M12.2928955,6.70710318 C11.9023712,6.31657888 11.9023712,5.68341391 12.2928955,5.29288961 C12.6834198,4.90236532 13.3165848,4.90236532 13.7071091,5.29288961 L19.7071091,11.2928896 C20.085688,11.6714686 20.0989336,12.281055 19.7371564,12.675721 L14.2371564,18.675721 C13.863964,19.08284 13.2313966,19.1103429 12.8242777,18.7371505 C12.4171587,18.3639581 12.3896557,17.7313908 12.7628481,17.3242718 L17.6158645,12.0300721 L12.2928955,6.70710318 Z" fill="#000000" fill-rule="nonzero"/>
                                                    <path d="M3.70710678,15.7071068 C3.31658249,16.0976311 2.68341751,16.0976311 2.29289322,15.7071068 C1.90236893,15.3165825 1.90236893,14.6834175 2.29289322,14.2928932 L8.29289322,8.29289322 C8.67147216,7.91431428 9.28105859,7.90106866 9.67572463,8.26284586 L15.6757246,13.7628459 C16.0828436,14.1360383 16.1103465,14.7686056 15.7371541,15.1757246 C15.3639617,15.5828436 14.7313944,15.6103465 14.3242754,15.2371541 L9.03007575,10.3841378 L3.70710678,15.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(9.000003, 11.999999) rotate(-270.000000) translate(-9.000003, -11.999999) "/>
                                                </g>
                                            </svg>
                                            <!--end::Svg Icon-->
                                        </span><br><br>
                                    </div>
                                </div>
                                <!--end::Content-->
                            </div>
                        </div>
                    </a>
                    <!--end::Callout-->
                </div>
            </div>
        </div>
        <!--end::Section-->
        <!--end::Entry-->
    </div>
@endsection
@section('admin_js')
    <script src="{{ asset('assets/js/pages/crud/forms/widgets/select2.js') }}"></script>
    <script src="{{ asset('assets/plugins/custom/form/form.min.js') }}"></script>
    <script src="{{ asset('assets/js/libraries/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('js/pages/product/form_add_bulk.js') }}?ver=00026"></script>
@endsection


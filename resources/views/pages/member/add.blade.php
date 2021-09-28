@extends('includes.layout_admin')

@section('custom_title','Tambah Vendor Member')

@section('admin_css')

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
                                <a href="{{route('vendor')}}" class="text-muted">Vendor</a>
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
                <!--begin::Toolbar-->
                <div class="d-flex align-items-center">
                    <!--begin::Actions-->
                        <a href="{{route('member')}}" class="btn btn-light-primary font-weight-bolder btn-sm mr-1">Kembali</a>
                   <!--end::Actions-->
                </div>
                <!--end::Toolbar-->
            </div>
        </div>
        <!--end::Subheader-->
        <!--begin::Entry-->
        <div class="d-flex flex-column-fluid">
            <!--begin::Container-->
            <div class="container col-8">
                <div class="card">
                    <div class="card-header flex-wrap border-0 pt-6 pb-0">
                        <div class="card-title">
                            <h3 class="card-label">
                                <span id="info-status-title">Tambah Member</span>
                                <span class="d-block text-muted pt-2 font-size-sm" id="info-status-subtitle"></span>
                            </h3>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center">
                        @if(@$success)
                            <div  class="mb-4 alert alert-success" style="max-width: 500px;text-align: left;">
                                <div class="font-medium text-red-600">Berhasil menambahkan member baru.</div>
                            </div>
                        @endif
                        @include('auth.validation-errors')
                    </div>
                    <div class="card-custom card-stretch gutter-b mt-10">
                        <form class="form" id="kt_form_1" method="POST">
                         @csrf
                            <!--begin::Body-->
                            <div class="card-body pt-0 pb-3">
                                <div class="tab-content">

                                    <div class="form-group row">
                                        <label class="col-form-label text-right col-lg-2 col-sm-12">Name</label>
                                        <div class="col-lg-10 col-md-10 col-sm-12">
                                            <input type="text" class="form-control" name="name" required placeholder="Enter member name" />
                                            <span class="form-text text-muted">Masukan nama member baru.</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label text-right col-lg-2 col-sm-12">Email</label>
                                        <div class="col-lg-10 col-md-10 col-sm-12">
                                            <input type="email" class="form-control" name="email" required placeholder="Enter member email" />
                                            <span class="form-text text-muted">Masukkan email member baru yang belum terdaftar.</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label text-right col-lg-2 col-sm-12">Password</label>
                                        <div class="col-lg-10 col-md-10 col-sm-12">
                                            <input type="password" class="form-control" name="password" required placeholder="Enter member password" />
                                            <span class="form-text text-muted">Masukan password untuk member baru.</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label text-right col-lg-2 col-sm-12">Password</label>
                                        <div class="col-lg-10 col-md-10 col-sm-12">
                                            <input type="password" class="form-control" name="password_confirmation" required placeholder="Confirm member password" />
                                            <span class="form-text text-muted">Konfirmasi password untuk member baru.</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end::Body-->
                            <div class="card-footer">
                                <div class="row">
                                    @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                                        <div class="col-lg-10 ml-lg-auto mb-5">
                                            <div class="checkbox-inline">
                                                <label class="checkbox m-0">
                                                    <x-jet-checkbox name="terms" required id="terms"/>
                                                    <span></span>
                                                    {!! __('Saya setuju dengan :terms_of_service dan :privacy_policy', [
                                                                'terms_of_service' => '<a target="_blank" href="https://dev-siplah.klikmro.com/ketentuan-penyedia" class="font-weight-bold ml-1 mr-1 underline text-sm text-gray-600 hover:text-gray-900">'.__('Persyaratan Layanan').'</a>',
                                                                'privacy_policy' => '<a target="_blank" href="https://dev-siplah.klikmro.com/kebijakan-privasi" class="font-weight-bold ml-1 underline text-sm text-gray-600 hover:text-gray-900">'.__('Kebijakan Privasi').'</a>',
                                                        ]) !!}
                                                </label>
                                            </div>
                                            <div class="form-text text-muted text-center"></div>
                                        </div>
                                    @endif
                                    <div class="col-lg-10 ml-lg-auto">
                                        <button type="submit" class="btn btn-primary font-weight-bold mr-2">Tambahkan Member Baru Untuk Vendor Anda</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!--end::Container-->
        </div>
        <!--end::Entry-->
    </div>
@endsection
@section('admin_js')
    <script src="{{ asset('js/pages/member/add.js') }}?ver=00001"></script>
@endsection

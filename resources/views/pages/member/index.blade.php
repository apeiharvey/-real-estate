@extends('includes.layout_admin')

@section('custom_title','Vendor Member')

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
                    <a href="{{route('vendor')}}" class="btn btn-light-primary font-weight-bolder btn-sm mr-1">Lihat Data Vendor</a>
                    @if(auth()->user()->id===auth()->user()->vendor->created_by)
                        <a href="{{route('member.add')}}" class="btn btn-light-primary font-weight-bolder btn-sm">Tambah Member</a>
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
                <div class="card">
                    <div class="card-custom card-stretch gutter-b mt-10">
                        <!--begin::Body-->
                        <div class="card-body pt-0 pb-3">
                            <div class="tab-content">
                                <!--begin::Table-->
                                <table class="table table-vertical-center" id="kt_datatable">
                                    <thead>
                                    <tr class="text-left text-uppercase">
                                        <th class="pl-7">No.</th>
                                        <th >Nama</th>
                                        <th >Email</th>
                                        <th >Tanggal Verifikasi Email</th>
                                        <th >Dibuat Oleh</th>
                                        <th >Dibuat Tanggal</th>
                                        <th >Diubah Oleh</th>
                                        <th >Diubah Tanggal</th>
                                        <th >Level</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach(@(object)@$member_list as $member)
                                        <tr>
                                            <td class="pl-7">{{ $loop->iteration }}</td>
                                            <td>{{ @$member->name }}</td>
                                            <td>{{ @$member->email }}</td>
                                            <td>{{ (!empty($member->email_verified_at)?date('d M Y H:i:s',strtotime(\Carbon\Carbon::createFromFormat('U.u', @optional(@optional(@$member->email_verified_at)->toDateTime())->format('U.u'), 'UTC')->setTimezone('Asia/Jakarta'))):"-") }}</td>
                                            <td>{{ !empty($member->created_by)?@$member->creator->name:@$member->name }}</td>
                                            <td>{{ (!empty($member->created_at)?date('d M Y H:i:s',strtotime(\Carbon\Carbon::createFromFormat('U.u', @optional(@optional(@$member->created_at)->toDateTime())->format('U.u'), 'UTC')->setTimezone('Asia/Jakarta'))):"-") }}</td>
                                            <td>{{ !empty($member->updated_by)?@$member->updater->name:'-' }}</td>
                                            <td>{{ (!empty($member->updated_at)?date('d M Y H:i:s',strtotime(\Carbon\Carbon::createFromFormat('U.u', @optional(@optional(@$member->updated_at)->toDateTime())->format('U.u'), 'UTC')->setTimezone('Asia/Jakarta'))):"-") }}</td>
                                            <td>
                                                {{$member->vendor->created_by===$member->id?'Administrator':'Member'}}
                                                @if($member->vendor->created_by!==$member->id && auth()->user()->id===$member->vendor->created_by)
                                                    <span class="text-danger cursor-pointer action-delete" data-title="{{$member->name}}" data-hash="{{ \Crypt::encryptString($member->id) }}">Hapus</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <!--end::Table-->
                            </div>
                        </div>
                        <!--end::Body-->
                    </div>
                </div>
            </div>
            <!--end::Container-->
        </div>
        <!--end::Entry-->
    </div>
@endsection
@section('admin_js')
    <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('js/pages/member/index.js') }}?ver=00002"></script>
@endsection

@extends('includes.layout_admin')

@section('custom_title','Catatan')

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
                                            <th >Log</th>
                                            <th >IP</th>
                                            <th >User agent</th>
                                            <th >Description</th>
                                            <th >Waktu</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach(@(object)@$log_list as $log)
                                        <tr>
                                            <td class="pl-7">{{ $loop->iteration }}</td>
                                            <td>{{ $log->agregation_name }}</td>
                                            <td>{{ $log->ip }}</td>
                                            <td>{{ $log->browser }}</td>
                                            <td>{{ $log->event_desc }}</td>
                                            <td>{{ (!empty($log->timestamp)?date('d M Y H:i:s',strtotime(\Carbon\Carbon::createFromFormat('U.u', @optional(@optional(@$log->timestamp)->toDateTime())->format('U.u'), 'UTC')->setTimezone('Asia/Jakarta'))):"-") }}</td>
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
    <script src="{{ asset('js/pages/log/index.js') }}?ver=00002"></script>
    <script>
        let start = "{{$start}}"
        let end = "{{$end}}"
    </script>
@endsection

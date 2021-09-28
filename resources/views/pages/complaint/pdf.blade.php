@extends('master')
@section('custom_title','Komplain')
@section('admin_css')
    <style>
        .treat-msg{
            white-space: pre;
            white-space: pre-line;
        }
        .treat-msg-wrapper{
            max-width:400px;
            word-wrap:break-word;
        }
    </style>
@endsection
@section('content')

<?php
    // date_default_timezone_set("Asia/Jakarta");
    // dump($order_detail);
    // dump($parent);
    // dump($child);
?>


<div id="complaint_print" class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container">
        <!-- begin::Card-->
        <div class="card card-custom overflow-hidden">
            <!--begin::Header-->
            <div class="card-header border-0 py-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label font-weight-bolder text-dark">{{@$order_detail->order_no}}</span>
                    <span class="text-muted mt-3 font-weight-bold font-size-sm">Transkrip Komplain</span>
                </h3>
            </div>
            <!--end::Header-->
            <!--begin::Body-->
            <div class="card-body pt-0 pb-3">
                <!--begin::Table-->
                <div class="table-responsive">
                    <table class="table table-head-custom table-head-bg table-vertical-center table-borderless">
                        <thead>
                            <tr class="bg-gray-100 text-left">
                                <th style="min-width: 250px" class="pl-7">
                                    <span class="text-dark-75">pengirim</span>
                                </th>
                                <th style="max-width: 400px">pesan</th>
                                <th style="min-width: 110px">waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $prev_talker = ''; ?>
                            @foreach($child as $key => $item)
                            <tr>
                                <td class="pl-0 py-8">
                                    @if(@$item->from_member && $prev_talker != 'member')
                                        <?php $prev_talker = 'member'; ?>
                                        <div class="d-flex align-items-center">
                                            <div class="symbol symbol-50 flex-shrink-0 mr-4">
                                                <div class="symbol-label" style="background-image: url('{{@$parent->customer->profile_pic}}')"></div>
                                            </div>
                                            <div>
                                                <a href="#" class="text-dark-75 font-weight-bolder text-hover-primary mb-1 font-size-lg">{{@$parent->customer->name}}</a>
                                                <span class="text-muted d-block">kustomer</span>
                                            </div>
                                        </div>
                                    @elseif(@$item->from_vendor && $prev_talker != 'vendor')
                                        <?php $prev_talker = 'vendor'; ?>
                                        <div class="d-flex align-items-center">
                                            <div class="symbol symbol-50 flex-shrink-0 mr-4">
                                                <div class="symbol-label" style="background-image: url('{{@$disk->url(@$parent->vendor->avatar_img)}}')"></div>
                                            </div>
                                            <div>
                                                <a href="#" class="text-dark-75 font-weight-bolder text-hover-primary mb-1 font-size-lg">{{@$parent->vendor->name}}</a>
                                                <span class="text-muted d-block">penyedia</span>
                                            </div>
                                        </div>
                                    @elseif(@$item->from_admin && $prev_talker != 'admin')
                                        <?php $prev_talker = 'admin'; ?>
                                        <div class="d-flex align-items-center">
                                            <div class="symbol symbol-50 flex-shrink-0 mr-4">
                                                <div class="symbol-label" style="background-image: url('')"></div>
                                            </div>
                                            <div>
                                                <a href="#" class="text-dark-75 font-weight-bolder text-hover-primary mb-1 font-size-lg"><b>Admin</b></a>
                                            </div>
                                        </div>
                                    @else

                                    @endif
                                </td>
                                <td>
                                    <div class="treat-msg-wrapper">
                                        <?php
                                            if($item->image){
                                                echo '<img alt="upload-picture"';
                                                if (@getimagesize($item->image)) {
                                                    echo ' src="'.$item->image.'" ';
                                                } else {
                                                    echo ' src="'.$disk->url($item->image).'" ';
                                                }
                                                echo 'class="mb-10" style="max-width:270px"><br>';
                                            }
                                        ?>
                                        <span class="treat-msg" wrap="hard">{!! @$item->complain_message !!}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="label label-lg label-light-primary label-inline">
                                        {{date("j F Y, g:i a", strtotime(@$item->created_at))}}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!--end::Table-->
            </div>
            <!--end::Body-->
        </div>
    </div>
</div>
        
@endsection
@section('admin_js')
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.2/jspdf.min.js"></script> -->
    <script type="text/javascript">
        $(document).ready(function(){
            // let doc = new jsPDF('p', 'pt', 'a4');
            // doc.addHTML(document.body, function () {
            //     doc.save('transkrip_komplain.pdf');
            // })
            window.print();
        });
    </script>
@endsection

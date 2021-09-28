'use strict';

console.log('..we run test');

var status = '';
var status_title = '';

$(".btn-list-order").click(function(){
    $('.btn-list-order').each(function(){
        $(this).removeClass('active');
    })
    $(this).addClass('active');
    if ( $.fn.DataTable.isDataTable('#kt_datatable') ) {
        console.log('datatable EXIST');
        status = $(this).data('status');
        status_title = $(this).data('title');
        $('#info-status-title').html(status_title);
        $('#kt_datatable').DataTable().clear().destroy();
        KTDatatablesDataSourceAjaxClient.init();
    }
});

$('#fl_price_from').on('change',function(e){
    var nego_from = $(this).val();
    var nego_to = $('#fl_price_to').val();

    if(parseInt(nego_from) < 0 || nego_from == ""){
        if(parseInt(nego_to) > 0){
            $(this).val(0);
        }
    }
    if(parseInt(nego_from) > 0){
        if(nego_to != ""){
            if(parseInt(nego_from) > parseInt(nego_to)){
                $(this).val(nego_to);
            }
        }
    }
});

$('#fl_price_to').on('change',function(e){
    var nego_from = $('#fl_price_from').val();
    var nego_to = $(this).val();

    if(parseInt(nego_to) <= 0){
        $(this).val("");
    }else if(parseInt(nego_to) > 0){
        if(parseInt(nego_from) < 1 || nego_from == ""){
            $('#fl_price_from').val(0);
        }
        if(parseInt(nego_to) < parseInt(nego_from)){
            $(this).val($('#fl_price_from').val())
        }
    }else{
        if(parseInt(nego_from) == 0){
            $('#fl_price_from').val("")
        }
    }
})

$('#fl_price_from').mask("#.##0", {
    reverse: true
});

$('#fl_price_to').mask("#.##0", {
    reverse: true
});

$('#kt_datepicker').datepicker({
    todayHighlight: true,
    templates: {
        leftArrow: '<i class="la la-angle-left"></i>',
        rightArrow: '<i class="la la-angle-right"></i>',
    },
    format: 'yyyy-mm-dd'
});

var KTDatatablesDataSourceAjaxClient = function() {

    $.fn.dataTable.Api.register('column().title()', function() {
        return $(this.header()).text().trim();
    });

    var initTable1 = function() {
        // begin first table
        var table = $('#kt_datatable').DataTable({
            processing: true,
            serverSide: true,
            ordering: false,
            responsive: true,
            /*dom: `<'row'<'col-sm-12'tr>>
			<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>`,*/
            dom: `<"top"<"row"<"col-sm-12"i><"col-sm-12 col-md-5"l><"col-sm-12 col-md-7"p>>><"col-sm-12"tr><"bottom"<"row"<"col-sm-12 col-md-5"l><"col-sm-12 col-md-7"p><"col-sm-12"i>>><"clear">`,
            ajax: {
                url: HOST_URL + '/order/api/get_list',
                type: 'POST',
                headers: {
                    'x-csrf-token': $('meta[name="csrf-token"]').attr('content'),
                },
                data: {
                    status: status
                },
            },
            searchDelay: 500,
            columns: [
                {data: 'customer'},
                {data: 'status'},
                {data: 'order'},
                {data: 'created_at'},
                {data: 'total_price'},
                {data: 'cancel_status'},
                {data: 'edit_status'},
                {data: 'id', responsivePriority: -1},
            ],
            initComplete: function(settings, json){
                if(json.status == 'error'){
                    Swal.fire({
                        text: "Oppss.. Error",
                        html: "Mohon untuk mencoba kembali atau klik muat ulang",
                        icon: "error",
                        showCancelButton: true,
                        buttonsStyling: false,
                        confirmButtonText: "Muat Ulang",
                        cancelButtonText: "Batal",
                        customClass: {
                            confirmButton: "btn font-weight-bold btn-primary",
                            cancelButton: "btn font-weight-bold btn-default"
                        }
                    }).then(function (result) {
                        location.reload();
                    });
                }
                this.api().columns().every(function() {
                    var column = this;


                });
            },
            columnDefs: [
                {
                    targets: -1,
                    title: 'Aksi',
                    orderable: false,
                    render: function(data, type, full, meta) {
                        return '\
							<a href="'+HOST_URL + '/order/detail/'+data+'" target="_blank" class="btn btn-sm btn-primary btn-icon" title="Edit details">\
								<i class="la la-edit"></i>\
							</a>\
						';
                    },
                },
                {
                    targets: 1,
                    title: 'Status',
                    orderable: false,
                    render: function(data, type, full, meta) {
                        return '<span class="label label-lg font-weight-bold text-center py-6 label-light-info label-inline">' + data + '</span>';
                    },
                },
                {
                    width: '200px',
                    targets: 0,
                    render: function(data, type, full, meta) {
                        var cust = data.split("|");
                        if(cust[4] == "") cust[4] = "-";
                        // console.log('price',data);
                        if(data){
                            let template = '<span><b>'+cust[0]+'</b></span><br>'+
                                '<span>'+cust[1]+'</span><br>'+
                                '<span>'+cust[2]+'</span><br><br>'+
                                '<span class="font-weight-bold">Nomor Order : </span><br>'+
                                '<span>'+cust[3]+'</span><br>'+
                                '<span class="font-weight-bold">Nomor PO : </span><br>'+
                                '<span>'+cust[4]+'</span>';
                            return template;
                        }else{
                            return '-';
                        }
                    },
                },
                {
                    width: '200px',
                    targets: 2,
                    render: function(data, type, full, meta) {
                        // console.log('price',data);
                        var order = data.split("|");
                        if(data){
                            let template = '<span>'+order[0]+'</span><br><span>'+order[1]+'</span>';
                            return template;
                        }else{
                            return '-';
                        }
                    },
                },
                {
                    targets: -2,
                    render: function(data, type, full, meta) {
                        var status = {
                            null: {'title': '-', 'class': 'label-light-info'},
                            'REQUEST': {'title': 'Mengajukan Pembaharuan', 'class': ' label-light-info'},
                            'APPROVED': {'title': 'Pembatalan Disetujui', 'class': ' label-light-primary'},
                            'REJECTED': {'title': 'Pembatalan Ditolak', 'class': ' label-light-danger'},
                        };
                        if (typeof status[data] === 'undefined') {
                            return data;
                        }
                        return '<span class="label label-lg font-weight-bold text-center py-6' + status[data].class + ' label-inline">' + status[data].title + '</span>';
                    },
                },
                {
                    targets: -3,
                    render: function(data, type, full, meta) {
                        var status = {
                            null: {'title': '-', 'class': 'label-light-info'},
                            'CANCELLATION_PROPOSED': {'title': 'Mengajukan Pembatalan', 'class': ' label-light-danger'},
                            'CANCELLATION_APPROVED': {'title': 'Pembatalan Disetujui', 'class': ' label-light-primary'},
                            'CANCELLATION_REJECTED': {'title': 'Pembatalan Ditolak', 'class': ' label-light-info'},
                            'CANCEL_SISTEM' : {'title': 'Pembatalan Otomatis', 'class': ' label-light-info'}
                        };
                        if (typeof status[data] === 'undefined') {
                            return data;
                        }
                        return '<span class="label label-lg font-weight-bold text-center py-6' + status[data].class + ' label-inline">' + status[data].title + '</span>';
                    },
                }
            ]
        });

        $('#kt_search').on('click', function(e) {
            e.preventDefault();
            var params = {};

            var poNo = $('#fl_po_no').val().length;
            var cust = $('#fl_cust').val().length;
            var dest = $('#fl_address').val().length;

            var flagPoNo = false;
            var flagCust = false;
            var flagDest = false;

            if(poNo == 0 || poNo > 2){
                $('#fl_po_no').removeClass('is-invalid');
                $('#invalid_po_no').addClass('hide');
                flagPoNo = true;
            }else{
                $('#fl_po_no').addClass('is-invalid');
                $('#invalid_po_no').removeClass('hide');
            }

            if(cust == 0 || cust > 2){
                $('#fl_cust').removeClass('is-invalid');
                $('#invalid_cust').addClass('hide');
                flagCust = true;
            }else{
                $('#fl_cust').addClass('is-invalid');
                $('#invalid_cust').removeClass('hide');
            }

            if(dest == 0 || dest > 2){
                $('#fl_address').removeClass('is-invalid');
                $('#invalid_address').addClass('hide');
                flagDest = true;
            }else{
                $('#fl_address').addClass('is-invalid');
                $('#invalid_address').removeClass('hide');
            }
            console.log(flagPoNo, flagCust, flagDest);

            if(flagPoNo && flagCust && flagDest){
                $('.datatable-input').each(function() {
                    var i = $(this).data('col-index');
                    if (params[i]) {
                        params[i] += '|' + $(this).val();
                    }
                    else {
                        params[i] = $(this).val();
                    }
                });
                $.each(params, function(i, val) {
                    // apply search params to datatable
                    table.column(i).search(val ? val : '', false, false);
                });
                table.table().draw();
            }
        });

        $('#kt_reset').on('click', function(e) {
            e.preventDefault();
            $('.datatable-input').each(function() {
                $(this).val('');
                table.column($(this).data('col-index')).search('', false, false);
            });
            table.table().draw();
            $('.select-operation').val($('.select-operation option:first').val());
        });
    };

    return {
        //main function to initiate the module
        init: function() {
            initTable1();
        },

    };

}();

jQuery(document).ready(function() {
    KTDatatablesDataSourceAjaxClient.init();
});

// ----------------------- limit


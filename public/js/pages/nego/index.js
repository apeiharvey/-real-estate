"use strict";
var KTDatatablesSearchOptionsAdvancedSearch = function() {

    $.fn.dataTable.Api.register('column().title()', function() {
        return $(this.header()).text().trim();
    });
    var filterInput = [];
    const urlParams = new URLSearchParams(window.location.search);
    filterInput['stid'] = urlParams.get('stid');

    let formatter = new Intl.NumberFormat('ID');

    var initTable1 = function() {
        // begin first table
        var table = $('#kt_datatable').DataTable({
            responsive: true,
            // Pagination settings
            dom: `<'row'<'col-sm-12'tr>>
			<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>`,
            // read more: https://datatables.net/examples/basic_init/dom.html

            lengthMenu: [5, 10, 25, 50],

            pageLength: 10,

            language: {
                'lengthMenu': 'Display _MENU_',
            },
            order: [[ 3, "desc" ]],

            searchDelay: 500,
            processing: true,
            serverSide: true,
            ajax: {
                url: HOST_URL+ '/nego/api/get_list',
                headers: {
                    'x-csrf-token': $('meta[name="csrf-token"]').attr('content'),
                },
                type: 'POST',
                data: filterInput
            },
            columns: [
                {data: 'product'},
                {data: 'quantity'},
                {data: 'nego_price'},
                {data: 'offer_date'},
                {data: 'offer_price'},
                {data: 'actions', responsivePriority: -1},
            ],

            initComplete: function(settings, json) {
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

                    switch (column.title()) {
                        // case 'Country':
                        //     // column.data().unique().sort().each(function(d, j) {
                        //     //     $('.datatable-input[data-col-index="2"]').append('<option value="' + d + '">' + d + '</option>');
                        //     // });
                        //     break;

                        // case 'Status':
                        //     var status = {
                        //         1: {'title': 'Pending', 'class': 'label-light-primary'},
                        //         2: {'title': 'Delivered', 'class': ' label-light-danger'},
                        //         3: {'title': 'Canceled', 'class': ' label-light-primary'},
                        //         4: {'title': 'Success', 'class': ' label-light-success'},
                        //         5: {'title': 'Info', 'class': ' label-light-info'},
                        //         6: {'title': 'Danger', 'class': ' label-light-danger'},
                        //         7: {'title': 'Warning', 'class': ' label-light-warning'},
                        //     };
                        //     column.data().unique().sort().each(function(d, j) {
                        //         $('.datatable-input[data-col-index="6"]').append('<option value="' + d + '">' + status[d].title + '</option>');
                        //     });
                        //     break;

                        case 'Type':
                            var status = {
                                1: {'title': 'Online', 'state': 'danger'},
                                2: {'title': 'Retail', 'state': 'primary'},
                                3: {'title': 'Direct', 'state': 'success'},
                            };
                            column.data().unique().sort().each(function(d, j) {
                                $('.datatable-input[data-col-index="7"]').append('<option value="' + d + '">' + status[d].title + '</option>');
                            });
                            break;
                    }
                });

            },

            columnDefs: [
                {
                    targets: -1,
                    orderable: false,
                    render: function(data, type, full, meta) {
                        return '\
							<a target="_blank" href="'+HOST_URL+'/nego/download/'+data+'" class="btn btn-sm btn-light-dark btn-icon btn-download" title="Download">\
								<i class="flaticon2-download"></i>\
							</a>\
							\<button type="button" class="btn btn-sm btn-light-primary btn-icon btn-nego-detail" data-index="'+data+'" data-target="#negoModal"><i class="flaticon-chat"></i></button>\
						';
                    },
                },
                {
                    targets: 0,
                    width: '100px',
                    render: function(data, type, full, meta) {
                        var product = data.split("|");
                        return `<div class="text-center">
                                    <img width="150px" src=`+IMG_URL+product[1]+` />
                                    <div class="font-weight-bolder mt-3 text-center">`+product[0]+`</div>
                                </div>`;
                    },
                },
                {
                    targets: 2,
                    render: function(data, type, full, meta){
                        return "Rp. "+formatter.format(data);
                    }
                },
                {
                    targets: 3,
                    render: function(data, type, full, meta){
                        return moment.utc(data, 'YYYY-MM-DD HH:mm:ss').local().format('YYYY-MM-DD HH:mm:ss');
                    }
                },
                {
                    targets: 4,
                    render: function(data, type, full, meta){
                        if(data == null){
                            if(filterInput['stid'] == "1" || filterInput['stid'] == null){
                                return "Menunggu Respon Penjual";
                            }
                            return "-";
                        }
                        return "Rp. "+formatter.format(data);
                    }
                }
            ],
        });

        var filter = function() {
            var val = $.fn.dataTable.util.escapeRegex($(this).val());
            table.column($(this).data('col-index')).search(val ? val : '', false, false).draw();
        };

        var asdasd = function(value, index) {
            var val = $.fn.dataTable.util.escapeRegex(value);
            table.column(index).search(val ? val : '', false, true);
        };

        $('#kt_search').on('click', function(e) {
            e.preventDefault();

            var params = {};
            var productName = $('#product_name').val().length;

            if(productName == 0 || productName > 2){
                $('#product_name').removeClass('is-invalid');
                $('#invalid_product_name').addClass('hide');
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
            }else{
                $('#product_name').addClass('is-invalid');
                $('#invalid_product_name').removeClass('hide');
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

        $('#kt_datepicker').datepicker({
            todayHighlight: true,
            templates: {
                leftArrow: '<i class="la la-angle-left"></i>',
                rightArrow: '<i class="la la-angle-right"></i>',
            },
            format: 'yyyy-mm-dd'
        });

        // $('#kt_datepicker').on('changeDate',function(e){
        //    console.log($('#nego_date_from').val() < $('#nego_date_to').val());
        //    console.log($('#nego_date_to').val());
        // });

        $('#nego_price_from').on('change',function(e){
            var nego_from = $(this).val();
            var nego_to = $('#nego_price_to').val();

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

        $('#nego_price_to').on('change',function(e){
            var nego_from = $('#nego_price_from').val();
            var nego_to = $(this).val();

            if(parseInt(nego_to) <= 0){
                $(this).val("");
            }else if(parseInt(nego_to) > 0){
                if(parseInt(nego_from) < 1 || nego_from == ""){
                    $('#nego_price_from').val(0);
                }
                if(parseInt(nego_to) < parseInt(nego_from)){
                    $(this).val($('#nego_price_from').val())
                }
            }else{
                if(parseInt(nego_from) == 0){
                    $('#nego_price_from').val("")
                }
            }
        })

        $('#offer_price_from').on('change',function(e){
            var offer_from = $(this).val();
            var offer_to = $('#offer_price_to').val();

            if(parseInt(offer_from) < 0 || offer_from == ""){
                if(parseInt(offer_to) > 0){
                    $(this).val(0);
                }
            }
            if(parseInt(offer_from) > 0){
                if(offer_to != ""){
                    if(parseInt(offer_from) > parseInt(offer_to)){
                        $(this).val(offer_to);
                    }
                }
            }
        });

        $('#offer_price_to').on('change',function(e){
            var offer_from = $('#offer_price_from').val();
            var offer_to = $(this).val();

            if(parseInt(offer_to) <= 0){
                $(this).val("");
            }else if(parseInt(offer_to) > 0){
                if(parseInt(offer_from) < 1 || offer_from == ""){
                    $('#offer_price_from').val(0);
                }
                if(parseInt(offer_to) < parseInt(offer_from)){
                    $(this).val($('#offer_price_from').val())
                }
            }else{
                if(parseInt(offer_from) == 0){
                    $('#offer_price_from').val("")
                }
            }
        })


        var id = '';
        $(document).on('click','.btn-nego-detail',function(){
            id = $(this).data('index');
            $('.action-nego-container').addClass('d-none');
            $('#nego_price_inp').removeClass('is-invalid');
            $('.invalid-feedback').addClass('hide');
            $('#nego_price_inp').val('');
            $('#nego_note_inp').val('');

            var html = $('#loading_modal').html();
            $('#scrollContent').empty();
            $('#scrollContent').append(html);
            $('#negoModal').modal('show');

            $.ajax({
                type:"POST",
                url: HOST_URL+ '/nego/api/get_detail',
                headers: {
                    'x-csrf-token': $('meta[name="csrf-token"]').attr('content'),
                },
                data:{'id':id},
                success: function(result){
                    $('#scrollContent').empty();
                    if(result.length == 0){
                        $('#scrollContent').append('<h4 class="text-center">Tidak ada riwayat negosiasi</h4>')
                    }else{
                        for(let i = 0; i < result.length; i++){
                            $('#scrollContent').append(
                                `<div class="d-flex mb-8">`+
                                `<div class="d-flex flex-column flex-grow-1 my-lg-0 my-2 pr-3">`+
                                `<span class="text-primary font-weight-bolder text-hover-primary font-size-lg mb-2">`+result[i].name+`</span>`+
                                `<span class="text-dark-50 font-weight-bold font-size-sm mb-3">`+result[i].date+`</span>`+
                                `<span class="text-muted font-weight-bold font-size-lg mb-3">Harga Nego : `+
                                `<span class="text-dark-75 font-weight-bolder mb-3">`+result[i].nego_price+`</span></span>`+
                                `<span class="text-muted font-weight-bold font-size-sm">Note : `+result[i].nego_note+`</span>`+
                                `</div>`+
                                `</div>`+
                                `<hr>`
                            )
                            if(filterInput['stid'] == 1 || filterInput['stid'] == null){
                                $('.action-nego-container').removeClass('d-none');
                                $('.btn-nego-action').removeClass('d-none');
                            }
                        }
                    }
                },
                error: function(result){
                    console.log("ERROR");
                }
            })
        });

        $(document).on('click','.btn-nego-action',function(){
            var action = $(this).data('action');
            var open = true;
            var data = {
                'nego_price' : '-',
                'nego_note' : ''
            };
            var icon = "warning";
            data.nego_note = $('#nego_note_inp').val();

            if(action == "menolak") icon = "error";
            if(action == "menerima") icon = "success";
            if(action == "menawar"){

                var nego_price = $('#nego_price_inp').val();
                data.nego_price = nego_price;

                if(nego_price == ""){
                    open = false;
                    $('#nego_price_inp').addClass('is-invalid');
                    $('.invalid-feedback').removeClass('hide');
                }else{
                    if(parseInt(nego_price) < 1){
                        open = false;
                        $('#nego_price_inp').addClass('is-invalid');
                        $('.invalid-feedback').removeClass('hide');
                    }
                }
            }
            if(action == 'cancel') open = false;

            if(open){
                Swal.fire({
                    text: "Apakah anda yakin untuk "+action+" tawaran ?",
                    icon: icon,
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "Ya, submit!",
                    cancelButtonText: "Tidak, batalkan",
                    customClass: {
                        confirmButton: "btn font-weight-bold btn-primary",
                        cancelButton: "btn font-weight-bold btn-default"
                    }
                }).then(function (result) {
                    if(result.isConfirmed){
                        $('#negoModal').modal('hide');
                        $('#loader').modal({backdrop: 'static', keyboard: false});
                        $.ajax({
                            type:"POST",
                            url: HOST_URL+ '/nego/api/action_nego',
                            headers: {
                                'x-csrf-token': $('meta[name="csrf-token"]').attr('content'),
                            },
                            data:{
                                'id': id,
                                'status': action,
                                'data': data
                            },
                            success: function(result){

                                console.log(result);
                                var title = "Opps.. Error!";
                                var type = "error";
                                var html = result.message;
                                if(result.status == "success"){
                                    title = 'Berhasil diperbaharui';
                                    type = "success";
                                    html = result.message;
                                }
                                Swal.fire({
                                    title: title,
                                    html: html,
                                    icon: type,
                                    confirmButtonClass: 'btn btn-primary',
                                    buttonsStyling: false,
                                }).then(function(resp){
                                    $('#loader').modal('hide');
                                    if(result.status == "success"){

                                        table.table().draw();
                                    }
                                })
                            },
                            error: function(xhr, status, error){
                                var err = eval("("+xhr.responseText+")");
                                Swal.fire({
                                    title: "Opps.. Error!",
                                    html: err.message,
                                    icon: "error",
                                    confirmButtonClass: 'btn btn-primary',
                                    buttonsStyling: false,
                                }).then(function(resp){
                                    $('#loader').modal('hide');
                                })
                            }
                        });
                    }
                })
            }
        });

        $('#nego_price_inp').mask("#.##0", {
            reverse: true
        });

        $(document).on('click','.btn-download',function(e){
            e.preventDefault();
            var url = $(this).attr("href");
            $.ajax({
                type:"GET",
                url: url,
                success: function(result){
                    var w = window.open();
                    w.document.write(result);
                    w.document.close(); //this seems to be the thing doing the trick
                    w.focus();
                }
            });
        })
    };

    return {

        //main function to initiate the module
        init: function() {
            initTable1();
        },

    };

}();

jQuery(document).ready(function() {
    KTDatatablesSearchOptionsAdvancedSearch.init();
});

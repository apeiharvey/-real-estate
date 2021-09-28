
$(document).ready(function(){
    var id = window.location.pathname.split("/").pop()

    $('#inp_ongkir').mask("#.##0", {
        reverse: true
    });

    $("#btn_approve_order_priv").on("click",function(){
        var inp_ongkir = $('#inp_ongkir').val();
        if(inp_ongkir != ""){
            $('#inp_ongkir').removeClass('is-invalid');
            $('#invalid_inp_ongkir').addClass('hide');
            Swal.fire({
                html: "Apakah anda yakin untuk MENERIMA transaksi dengan Harga Ongkir "+inp_ongkir+" ?",
                icon: "question",
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: "Terima",
                cancelButtonText: "Batal",
                customClass: {
                    confirmButton: "btn font-weight-bold btn-success",
                    cancelButton: "btn font-weight-bold btn-secondary"
                }
            }).then(function (res) {
                if(res.isConfirmed){
                    $('#loader').modal({backdrop: 'static', keyboard: false});
                    $.ajax({
                        type:"POST",
                        url: HOST_URL+ '/order/api/update_status',
                        headers: {
                            'x-csrf-token': $('meta[name="csrf-token"]').attr('content'),
                        },
                        data:{
                            'id': id,
                            'status': "ORDER_CONFIRM",
                            'delivery' : inp_ongkir
                        },
                        success: function(result){
                            console.log(result);
                            var title = "Opps.. Error!";
                            var type = "error";
                            var html = result.message;
                            if(result.status === "success"){
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
                                if(result.status === "success"){
                                    $('#loader').modal('hide');
                                    location.reload();
                                }
                            })
                        }
                    });
                }
            })
        }else{
            $('#inp_ongkir').addClass('is-invalid');
            $('#invalid_inp_ongkir').removeClass('hide');
        }

    });

    $("#btn_approve_order").on("click",function(){
        Swal.fire({
            html: "Apakah anda yakin untuk MENERIMA transaksi ?",
            icon: "question",
            showCancelButton: true,
            buttonsStyling: false,
            confirmButtonText: "Terima",
            cancelButtonText: "Batal",
            customClass: {
                confirmButton: "btn font-weight-bold btn-success",
                cancelButton: "btn font-weight-bold btn-secondary"
            }
        }).then(function (res) {
            if(res.isConfirmed){
                $('#loader').modal({backdrop: 'static', keyboard: false});
                $.ajax({
                    type:"POST",
                    url: HOST_URL+ '/order/api/update_status',
                    headers: {
                        'x-csrf-token': $('meta[name="csrf-token"]').attr('content'),
                    },
                    data:{
                        'id': id,
                        'status': "ORDER_CONFIRM"
                    },
                    success: function(result){
                        console.log(result);
                        var title = "Opps.. Error!";
                        var type = "error";
                        var html = result.message;
                        if(result.status === "success"){
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
                            if(result.status === "success"){
                                $('#summary_order').empty();
                                $('#summary_order').append(result.view);
                            }
                            $('#loader').modal('hide');
                        })
                    }
                });
            }
        })
    })

    $("#btn_reject_order").on("click",function(){
        Swal.fire({
            input: "text",
            text: "Apakah anda yakin untuk MENOLAK transaksi ?",
            icon: "question",
            showCancelButton: true,
            buttonsStyling: false,
            confirmButtonText: "Tolak",
            cancelButtonText: "Batal",
            inputPlaceholder: "Note Penolakan Transaksi",
            customClass: {
                confirmButton: "btn font-weight-bold btn-danger",
                cancelButton: "btn font-weight-bold btn-secondary"
            },
            inputValidator: (value) => {
                return !value && 'Anda harus memasukan note'
            }
        }).then(function (res) {
            if(res.isConfirmed){
                $('#loader').modal({backdrop: 'static', keyboard: false});
                $.ajax({
                    type:"POST",
                    url: HOST_URL+ '/order/api/update_status',
                    headers: {
                        'x-csrf-token': $('meta[name="csrf-token"]').attr('content'),
                    },
                    data:{
                        'id': id,
                        'status': "ORDER_REJECTED",
                        'note' : res.value
                    },
                    success: function(result){
                        console.log(result);
                        var title = "Opps.. Error!";
                        var type = "error";
                        var html = result.message;
                        if(result.status === "success"){
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
                            if(result.status === "success"){
                                $('#summary_order').empty();
                                $('#summary_order').append(result.view);
                            }
                            $('#loader').modal('hide');
                        })
                    }
                });
            }
        })
    });

    $('#btn_proses').on("click",function(){
        Swal.fire({
            text: "Apakah anda yakin untuk memproses transaksi ?",
            icon: "question",
            showCancelButton: true,
            buttonsStyling: false,
            confirmButtonText: "Proses",
            cancelButtonText: "Batal",
            customClass: {
                confirmButton: "btn font-weight-bold btn-primary",
                cancelButton: "btn font-weight-bold btn-default"
            }
        }).then(function (res) {
            if(res.isConfirmed){
                $('#loader').modal({backdrop: 'static', keyboard: false});
                $.ajax({
                    type:"POST",
                    url: HOST_URL+ '/order/api/update_status',
                    headers: {
                        'x-csrf-token': $('meta[name="csrf-token"]').attr('content'),
                    },
                    data:{
                        'id': id,
                        'status': 'ORDER_PROCESSED'
                    },
                    success: function(result){
                        console.log(result);
                        var title = "Opps.. Error!";
                        var type = "error";
                        var html = result.message;
                        if(result.status === "success"){
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
                            if(result.status === "success"){
                                $('#summary_order').empty();
                                $('#summary_order').append(result.view);
                            }
                            $('#loader').modal('hide');
                        })
                    }
                });
            }
        })
    });

    $(document).on("click","#btn_request_awb",function(){
        Swal.fire({
            text: "Apakah anda ingin mengajukan no resi ?",
            icon: "question",
            showCancelButton: true,
            buttonsStyling: false,
            confirmButtonText: "Proses",
            cancelButtonText: "Batal",
            customClass: {
                confirmButton: "btn font-weight-bold btn-primary",
                cancelButton: "btn font-weight-bold btn-default"
            }
        }).then(function (res) {
            if(res.isConfirmed){
                $('#loader').modal({backdrop: 'static', keyboard: false});
                $.ajax({
                    type:"POST",
                    url: HOST_URL+ '/order/api/request_awb',
                    headers: {
                        'x-csrf-token': $('meta[name="csrf-token"]').attr('content'),
                    },
                    data:{
                        'id': id,
                    },
                    success: function(result){
                        console.log(result);
                        var title = "Opps.. Error!";
                        var type = "error";
                        var html = result.message;
                        if(result.status === "success"){
                            title = 'Berhasil diperbaharui';
                            type = "success";
                            html = result.message;
                        }
                        if(result.status === "fail"){
                            title = 'Pengajuan nomor resi ditolak';
                            type = "info";
                            html = result.message;
                        }
                        Swal.fire({
                            title: title,
                            html: html,
                            icon: type,
                            confirmButtonClass: 'btn btn-primary',
                            buttonsStyling: false,
                        }).then(function(resp){
                            if(result.status === "success"){
                                $('#summary_order').empty();
                                $('#summary_order').append(result.view);
                            }
                            $('#loader').modal('hide');
                        })
                    }
                });
            }
        })
    });

    $(document).on('click','#btn_ship_priv',function(){
        var no_resi = $('#inp_resi').val();
        console.log(no_resi);
        if(no_resi != ""){
            $('#inp_resi').removeClass('is-invalid');
            $('#invalid_inp_resi').addClass('hide');
            Swal.fire({
                text: "Apakah anda yakin akan mengirimkan Nomor Resi "+no_resi+" ?",
                icon: "question",
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: "Kirim",
                cancelButtonText: "Batal",
                customClass: {
                    confirmButton: "btn font-weight-bold btn-primary",
                    cancelButton: "btn font-weight-bold btn-secondary"
                }
            }).then(function (res) {
                if(res.isConfirmed){
                    $('#loader').modal({backdrop: 'static', keyboard: false});
                    $.ajax({
                        type:"POST",
                        url: HOST_URL+ '/order/api/update_status',
                        headers: {
                            'x-csrf-token': $('meta[name="csrf-token"]').attr('content'),
                        },
                        data:{
                            'id': id,
                            'status': 'ORDER_SHIPPED',
                            'no_resi': no_resi
                        },
                        success: function(result){
                            console.log(result);
                            var title = "Opps.. Error!";
                            var type = "error";
                            var html = result.message;
                            if(result.status === "success"){
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
                                if(result.status === "success"){
                                    $('#summary_order').empty();
                                    $('#summary_order').append(result.view);
                                    $('#no_resi_detail').empty();
                                    $('#no_resi_detail').html(result.no_resi);
                                }
                                $('#loader').modal('hide');
                            })
                        }
                    })
                }
            });
        }else{
            $('#inp_resi').addClass('is-invalid');
            $('#invalid_inp_resi').removeClass('hide');
        }
    })

    $(document).on('click','#btn_ship',function(){
        var no_resi = $('#inp_resi').val();
        Swal.fire({
            text: "Apakah anda sudah yakin mengirimkan barang ?",
            icon: "question",
            showCancelButton: true,
            buttonsStyling: false,
            confirmButtonText: "Kirim",
            cancelButtonText: "Batal",
            customClass: {
                confirmButton: "btn font-weight-bold btn-primary",
                cancelButton: "btn font-weight-bold btn-secondary"
            }
        }).then(function (res) {
            if(res.isConfirmed){
                $('#loader').modal({backdrop: 'static', keyboard: false});
                $.ajax({
                    type:"POST",
                    url: HOST_URL+ '/order/api/update_status',
                    headers: {
                        'x-csrf-token': $('meta[name="csrf-token"]').attr('content'),
                    },
                    data:{
                        'id': id,
                        'status': 'ORDER_SHIPPED'
                    },
                    success: function(result){
                        console.log(result);
                        var title = "Opps.. Error!";
                        var type = "error";
                        var html = result.message;
                        if(result.status === "success"){
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
                            if(result.status === "success"){
                                $('#summary_order').empty();
                                $('#summary_order').append(result.view);
                                $('#no_resi_detail').empty();
                                $('#no_resi_detail').html(result.no_resi);
                            }
                            $('#loader').modal('hide');
                        })
                    }
                })
            }
        });
    })

    $('#btn_cancel_approve').on('click',function(){
        Swal.fire({
            input: 'text',
            text: "Apakah anda ingin menerima pengajuan pembatalan transaksi ?",
            icon: "success",
            showCancelButton: true,
            buttonsStyling: false,
            confirmButtonText: "Terima",
            cancelButtonText: "Batal",
            showCloseButton: true,
            inputPlaceholder: "Note pengajuan pembatalan",
            inputValidator: (value) => {
                return !value && 'Anda harus memasukan note'
            },
            customClass: {
                confirmButton: "btn font-weight-bold btn-primary",
                cancelButton: "btn font-weight-bold btn-danger"
            }
        }).then(function (res) {
            if(res.isConfirmed){
                $('#loader').modal({backdrop: 'static', keyboard: false});
                $.ajax({
                    type:"POST",
                    url: HOST_URL+ '/order/api/update_status',
                    headers: {
                        'x-csrf-token': $('meta[name="csrf-token"]').attr('content'),
                    },
                    data:{
                        'id': id,
                        'status': "CANCELLATION_APPROVED",
                        'note':res.value
                    },
                    success: function(result){
                        console.log(result);
                        var title = "Opps.. Error!";
                        var type = "error";
                        var html = result.message;
                        if(result.status === "success"){
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
                            if(result.status === "success"){
                                $('#summary_order').empty();
                                $('#summary_order').append(result.view);
                            }
                            $('#loader').modal('hide');
                        })
                    }
                })
            }
        });
    })

    $('#btn_cancel_reject').on('click',function(){
        Swal.fire({
            input: 'text',
            text: "Apakah anda ingin menolak pengajuan pembatalan transaksi ?",
            icon: "error",
            showCancelButton: true,
            buttonsStyling: false,
            confirmButtonText: "Tolak",
            cancelButtonText: "Batal",
            showCloseButton: true,
            inputPlaceholder: "Note pengajuan pembatalan",
            inputValidator: (value) => {
                return !value && 'Anda harus memasukan note'
            },
            customClass: {
                confirmButton: "btn font-weight-bold btn-primary",
                cancelButton: "btn font-weight-bold btn-danger"
            }
        }).then(function (res) {
            if(res.isConfirmed){
                $('#loader').modal({backdrop: 'static', keyboard: false});
                $.ajax({
                    type:"POST",
                    url: HOST_URL+ '/order/api/update_status',
                    headers: {
                        'x-csrf-token': $('meta[name="csrf-token"]').attr('content'),
                    },
                    data:{
                        'id': id,
                        'status': "CANCELLATION_REJECTED",
                        'note':res.value
                    },
                    success: function(result){
                        console.log(result);
                        var title = "Opps.. Error!";
                        var type = "error";
                        var html = result.message;
                        if(result.status === "success"){
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
                            if(result.status === "success"){
                                $('#summary_order').empty();
                                $('#summary_order').append(result.view);
                            }
                            $('#loader').modal('hide');
                        })
                    }
                })
            }
        });
    })

    $('.btn-nego').on('click',function(){
        var html = $('#loading_modal').html();
        $('#scrollContent').empty();
        $('#scrollContent').append(html);
        $('#negoModal').modal('show');

        var id = $(this).data('index');
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
                }else {
                    for(let i = 0; i < result.length; i++){
                        $('#scrollContent').append(
                            `<div class="d-flex mb-8">` +
                            `<div class="d-flex flex-column flex-grow-1 my-lg-0 my-2 pr-3">` +
                            `<span class="text-primary font-weight-bolder text-hover-primary font-size-lg mb-2">` + result[i].name + `</span>` +
                            `<span class="text-dark-50 font-weight-bold font-size-sm mb-3">` + result[i].date + `</span>` +
                            `<span class="text-muted font-weight-bold font-size-lg mb-3">Harga Nego : ` +
                            `<span class="text-dark-75 font-weight-bolder mb-3">` + result[i].nego_price + `</span></span>` +
                            `<span class="text-muted font-weight-bold font-size-sm">Note : ` + result[i].nego_note + `</span>` +
                            `</div>` +
                            `</div>` +
                            `<hr>`
                        )
                    }
                }
            },
            error: function(result){
                console.log("ERROR");
            }
        })
    })

    // $('#btn_show_bast').on('click',function(){
    //     $('#bastModal').modal('show');
    // })

    $("#btn_accept_bast").on("click",function(){
        Swal.fire({
            text: "Apakah anda yakin untuk MENERIMA BAST transaksi ?",
            icon: "question",
            showCancelButton: true,
            buttonsStyling: false,
            confirmButtonText: "Terima",
            cancelButtonText: "Batal",
            customClass: {
                confirmButton: "btn font-weight-bold btn-success",
                cancelButton: "btn font-weight-bold btn-secondary"
            }
        }).then(function (res) {
            if(res.isConfirmed){
                $('#loader').modal({backdrop: 'static', keyboard: false});
                $.ajax({
                    type:"POST",
                    url: HOST_URL+ '/order/api/update_status',
                    headers: {
                        'x-csrf-token': $('meta[name="csrf-token"]').attr('content'),
                    },
                    data:{
                        'id': id,
                        'status': "BAST_SUBMITTED"
                    },
                    success: function(result){
                        console.log(result);
                        var title = "Opps.. Error!";
                        var type = "error";
                        var html = result.message;
                        if(result.status === "success"){
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
                            if(result.status === "success"){
                                $('#summary_order').empty();
                                $('#summary_order').append(result.view);
                            }
                            $('#loader').modal('hide');
                        })
                    }
                });
            }
        })
    });

    $("#btn_reject_bast").on("click",function(){
        Swal.fire({
            text: "Apakah anda yakin untuk MENOLAK BAST transaksi ?",
            icon: "question",
            showCancelButton: true,
            buttonsStyling: false,
            confirmButtonText: "Tolak",
            cancelButtonText: "Batal",
            customClass: {
                confirmButton: "btn font-weight-bold btn-danger",
                cancelButton: "btn font-weight-bold btn-secondary"
            }
        }).then(function (res) {
            if (res.isConfirmed) {
                $('#loader').modal({backdrop: 'static', keyboard: false});
                $.ajax({
                    type: "POST",
                    url: HOST_URL + '/order/api/update_status',
                    headers: {
                        'x-csrf-token': $('meta[name="csrf-token"]').attr('content'),
                    },
                    data: {
                        'id': id,
                        'status': "BAST_REJECTED"
                    },
                    success: function (result) {
                        console.log(result);
                        var title = "Opps.. Error!";
                        var type = "error";
                        var html = result.message;
                        if (result.status === "success") {
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
                        }).then(function (resp) {
                            if (result.status === "success") {
                                $('#summary_order').empty();
                                $('#summary_order').append(result.view);
                            }
                            $('#loader').modal('hide');
                        })
                    }
                });
            }
        })
    });

    $("#btn_payment_received").on("click",function(){
        Swal.fire({
            text: "Apakah anda yakin sudah MENERIMA PEMBAYARAN ?",
            icon: "question",
            showCancelButton: true,
            buttonsStyling: false,
            confirmButtonText: "Sudah",
            cancelButtonText: "Batal",
            customClass: {
                confirmButton: "btn font-weight-bold btn-success",
                cancelButton: "btn font-weight-bold btn-secondary"
            }
        }).then(function (res) {
            if (res.isConfirmed) {
                $('#loader').modal({backdrop: 'static', keyboard: false});
                $.ajax({
                    type: "POST",
                    url: HOST_URL + '/order/api/update_status',
                    headers: {
                        'x-csrf-token': $('meta[name="csrf-token"]').attr('content'),
                    },
                    data: {
                        'id': id,
                        'status': "COMPLETED"
                    },
                    success: function (result) {
                        console.log(result);
                        var title = "Opps.. Error!";
                        var type = "error";
                        var html = result.message;
                        if (result.status === "success") {
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
                        }).then(function (resp) {
                            if (result.status === "success") {
                                $('#summary_order').empty();
                                $('#summary_order').append(result.view);
                            }
                            $('#loader').modal('hide');
                        })
                    }
                });
            }
        })
    });

    $('#btn_show_label').on('click',function(e){
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

    $('#btn_show_po').on('click',function(e){
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

    $(document).on('click','#btn_show_invoice',function(e){
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

    $(document).on('click','.btn-show-bast',function(e){
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

    $('.btn-show-receive').on('click',function(){
        var url = $(this).data('image');
        $('#receive_img').attr('src',url);
        $('#modal_receive').modal({backdrop: 'static', keyboard: false});
    })

    $(document).on('click','.btn-show-edit',function(e){
        $('#modal_edit').modal({backdrop: 'static', keyboard: false});
    })

    $(document).on('click','#btn_update_approved',function(e) {
        Swal.fire({
            html: "Apakah anda yakin untuk MENERIMA perubahan transaksi",
            icon: "success",
            showCancelButton: true,
            buttonsStyling: false,
            confirmButtonText: "Terima",
            cancelButtonText: "Batal",
            customClass: {
                confirmButton: "btn font-weight-bold btn-success",
                cancelButton: "btn font-weight-bold btn-secondary"
            }
        }).then(function (res) {
            if(res.isConfirmed){
                $('#modal_edit').modal('hide');
                $('#loader').modal({backdrop: 'static', keyboard: false});
                $.ajax({
                    type:"POST",
                    url: HOST_URL+ '/order/api/update_status',
                    headers: {
                        'x-csrf-token': $('meta[name="csrf-token"]').attr('content'),
                    },
                    data:{
                        'id': id,
                        'status': 'InfoUpdateApproved'
                    },
                    success: function(result){
                        console.log(result);
                        var title = "Opps.. Error!";
                        var type = "error";
                        var html = result.message;
                        if(result.status === "success"){
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
                            if(result.status === "success"){
                                $('#summary_order').empty();
                                $('#summary_order').append(result.view);
                                location.reload();
                            }
                            $('#loader').modal('hide');
                        })
                    }
                });
            }
        })
    })

    $(document).on('click','#btn_update_rejected',function(e) {
        Swal.fire({
            input: "text",
            text: "Apakah anda yakin untuk MENOLAK perubahan transaksi",
            icon: "error",
            showCancelButton: true,
            buttonsStyling: false,
            confirmButtonText: "Tolak",
            cancelButtonText: "Batal",
            inputPlaceholder: "Note Penolakan Pembaharuan Transaksi",
            customClass: {
                confirmButton: "btn font-weight-bold btn-danger",
                cancelButton: "btn font-weight-bold btn-secondary"
            },
            inputValidator: (value) => {
                return !value && 'Anda harus memasukan note'
            }
        }).then(function (res) {
            if(res.isConfirmed){
                $('#modal_edit').modal('hide');
                $('#loader').modal({backdrop: 'static', keyboard: false});
                $.ajax({
                    type:"POST",
                    url: HOST_URL+ '/order/api/update_status',
                    headers: {
                        'x-csrf-token': $('meta[name="csrf-token"]').attr('content'),
                    },
                    data:{
                        'id': id,
                        'status': "InfoUpdateRejected",
                        'note' : res.value
                    },
                    success: function(result){
                        console.log(result);
                        var title = "Opps.. Error!";
                        var type = "error";
                        var html = result.message;
                        if(result.status === "success"){
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
                            if(result.status === "success"){
                                $('#summary_order').empty();
                                $('#summary_order').append(result.view);
                            }
                            $('#loader').modal('hide');
                        })
                    }
                });
            }
        })
    })

    $(document).on('click','#btn_payment_complaint',function(e) {
        Swal.fire({
            input: "text",
            text: "Apakah anda ingin mengajukan keluhan mengenai pembayaran",
            icon: "error",
            showCancelButton: true,
            buttonsStyling: false,
            confirmButtonText: "Komplain",
            cancelButtonText: "Batal",
            inputPlaceholder: "Note Keluhan Pembayaran",
            customClass: {
                confirmButton: "btn font-weight-bold btn-danger",
                cancelButton: "btn font-weight-bold btn-secondary"
            },
            inputValidator: (value) => {
                return !value && 'Anda harus memasukan note'
            }
        }).then(function (res) {
            if(res.isConfirmed){
                $('#modal_edit').modal('hide');
                $('#loader').modal({backdrop: 'static', keyboard: false});
                $.ajax({
                    type:"POST",
                    url: HOST_URL+ '/order/api/update_status',
                    headers: {
                        'x-csrf-token': $('meta[name="csrf-token"]').attr('content'),
                    },
                    data:{
                        'id': id,
                        'status': "PAYMENT_COMPLAINED",
                        'note' : res.value
                    },
                    success: function(result){
                        console.log(result);
                        var title = "Opps.. Error!";
                        var type = "error";
                        var html = result.message;
                        if(result.status === "success"){
                            title = 'Berhasil dikirim';
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
                            if(result.status === "success"){
                                $('#summary_order').empty();
                                $('#summary_order').append(result.view);
                            }
                            $('#loader').modal('hide');
                        })
                    }
                });
            }
        })
    })

    var dataListUpload = new Dropzone("#dataListUpload", {
        paramName: "files", // The name that will be used to transfer the file
        addRemoveLinks: true,
        autoProcessQueue: false,
        thumbnailWidth: 100,
        thumbnailHeight: 100,
        maxFilesize: 3, // MB
        acceptedFiles: ".png, .jpeg, .jpg, .pdf",
        dictRemoveFile: " Remove",
        dictCancelUpload: "Cancel",
        url: HOST_URL+"/order/api/upload_tax",
        headers: {
            'x-csrf-token': $('meta[name="csrf-token"]').attr('content'),
        },params: {
            'id': id
        },init: function() {
            this.on("sending", function (file, xhr, formData) {
                console.log('...sending');
            });
            this.on("success", function (file, data) {
                console.log('...success', data);
                $(".button-process").each(function (i, obj) {
                    $(obj).show();
                });

                // console.log('...have i recreate the table ??');

                if (data.status) {
                    Dropzone.forElement('#dataListUpload').removeAllFiles(true);
                    Swal.fire({
                        title: "Success!",
                        text: data.message,
                        type: "success",
                        buttonsStyling: false,
                        confirmButtonClass: "btn btn-success"
                    }).then(function(resp){
                        console.log(resp);
                        $('#summary_order').empty();
                        $('#summary_order').append(data.view);
                    });
                    return true;
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        html: 'Terjadi Error Mohon coba kontak Admin Mitra',
                        type: "error",
                        buttonsStyling: false,
                        confirmButtonClass: "btn btn-error"
                    });
                    return false;
                }
            });
            this.on("error", function (file, errorMessage) {
                console.log('...error', file, errorMessage);
                Dropzone.forElement('#dataListUpload').removeAllFiles(true);
                $(".button-process").each(function (i, obj) {
                    $(obj).show();
                });
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Mohon untuk mencoba mengunggah kembali',
                    type: "error",
                    buttonsStyling: false,
                    confirmButtonClass: "btn btn-error"
                });
            });
            this.on("complete", function (progress) {
                console.log('process is completed');
            });
        }
    });

    $(".button-process-upload").click(function(){

        var images = dataListUpload.files[0]['dataURL'];
        console.log(images);
        // console.log("....");
        if(dataListUpload.files.length<=0){
            Swal.fire({
                title: 'Oops..',
                text: 'Tolong unggah file terlebih dahulu',
                animation: false,
                customClass: 'animated shake',
                confirmButtonClass: 'btn btn-primary',
                buttonsStyling: false,
            });
            $('html, body').animate({scrollTop: '0px'}, 300);
            return;
        }

        $(".button-process").each(function(i,obj){
            $(obj).hide();
        });
        dataListUpload.processQueue();
    })
});


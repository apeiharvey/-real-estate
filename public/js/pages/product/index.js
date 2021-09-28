'use strict';

console.log('..Product LIST Presented..yhaa');

let formatter = new Intl.NumberFormat('ID');
let status = '';
let status_title = '';
let display_pic_item_limit = 2;
let display_price_item_limit = 5;

$(document).on('click', '[data-toggle="lightbox"]', function(event) { // zoom image
    event.preventDefault();
    $(this).ekkoLightbox();
});

function checkProductAvailability(product_id){
    
    $(".button-form-action").each(function(i,obj){
        $(obj).hide();
    });
    $("#button-edit-info").show();
    $("#button-edit-info").html("Loading...");

    $.ajax({
        url: HOST_URL +'/product/api/check_item_order/'+product_id,
        headers: {
            'x-csrf-token': $('meta[name="csrf-token"]').attr('content'),
        },
        type: "POST",
        data:{},
        success:function(data){
            if(data.status){
                if(data.is_free){
                    confirmDelete(product_id);
                }else{
                    $(".button-form-action").each(function(i,obj){
                        $(obj).show();
                    });
                    $("#button-edit-info").hide();
                    $("#button-edit-info").html('');

                    Swal.fire({
                        title: "Oops!",
                        text: data.message,
                        timer: 3000,
                        onOpen: function() {
                            Swal.showLoading()
                        }
                    }).then(function(result) {
                        if (result.dismiss === "timer") {
                            console.log("alert ditutup oleh timer")
                        }
                    })
                }
            }else{
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    html: data.message,
                    type: "error",
                    buttonsStyling: false,
                    confirmButtonClass: "btn btn-error"
                });
                return false;
            }
        },
        error:function(xhr, status, error){
            $(".button-form-action").each(function(i,obj){
                $(obj).show();
            });
            $("#button-edit-info").hide();
            $("#button-edit-info").html('');

            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: xhr.responseText,
                type: "error",
                buttonsStyling: false,
                confirmButtonClass: "btn btn-error"
            });
        }
    });
};

let status_attribute = {
    5: {
        'title':'hapus',
        'title_verb':'menghapus', 
        'info':'Ini tidak dapat dikembalikan'
    },
    1: {
        'title':'tayangkan',
        'title_verb':'ajukan penayangan', 
        'info':''
    },
    2: {
        'title':'jangan tayangkan',
        'title_verb':'lepas penayangan', 
        'info':''
    }
};

function confirmStatusChange(product_id, status_id){
    if(status_id == 1){
        status_id = 2;
    }else if(status_id == 2){
        status_id = 1;
    }

    Swal.fire({
        title: "Apakah Anda yakin untuk "+status_attribute[status_id]['title_verb']+" produk ini?",
        text: status_attribute[status_id]['info'],
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya, "+status_attribute[status_id]['title'],
        cancelButtonText: "Batal",
        reverseButtons: true
    }).then(function(result) {
        if (result.value) {

            $.ajax({
                url: HOST_URL +'/product/api/update_status_item/'+product_id,
                headers: {
                    'x-csrf-token': $('meta[name="csrf-token"]').attr('content'),
                },
                type: "POST",
                data:{
                    status_id: status_id
                },
                success:function(data){
                    if(data.status){
                        Swal.fire(
                            "Berhasil!",
                            +data.message,
                            "success"
                        )

                        $('#kt_datatable').DataTable().clear().destroy();
                        KTDatatablesDataSourceAjaxClient.init();
                    }else{
                        Swal.fire(
                            "Gagal..",
                            +data.message,
                            "error"
                        )
                    }
                },
                error:function(xhr, status, error){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: xhr.responseText,
                        type: "error",
                        buttonsStyling: false,
                        confirmButtonClass: "btn btn-error"
                    });
                }
            });
        } else if (result.dismiss === "cancel") { // result.dismiss can be "cancel", "overlay", "close", and "timer"
            Swal.fire(
                "Batal",
                "Produk masih seperti semula",
                "error"
            )
        }


        $(".button-form-action").each(function(i,obj){
            $(obj).show();
        });
        $("#button-edit-info").hide();
        $("#button-edit-info").html('');
    });
}

var KTDatatablesDataSourceAjaxClient = function() {

    var initTable1 = function() {
        var table = $('#kt_datatable');

        // begin first table
        table.DataTable({
            responsive: true,
            ajax: {
                url: HOST_URL + '/product/api/get_list',
                type: 'POST',
                headers: {
                    'x-csrf-token': $('meta[name="csrf-token"]').attr('content'),
                },
                data: {
                    status: status
                },
                // success: function (result) {
                //     console.log('result', result);
                // }
            },
            columns: [
                {data: 'updated_at'},
                {data: 'images'},
                {data: 'name'},
                {data: 'sum_stock'},
                {data: 'category_key'},
                {data: 'listing_price'},
                // {data: 'price'},
                {data: 'item_status_text'},
                {data: 'id', responsivePriority: -1},
            ],
            columnDefs: [
                {
                    targets: -1,
                    title: 'Aksi',
                    orderable: false,
                    render: function(data, type, full, meta) {
                        
                        let template = '\
							<a href="'+HOST_URL + '/product/detail/'+data+'" target="_blank" class="btn btn-sm btn-clean btn-icon" title="Edit detail produk ini">\
								<i class="la la-edit"></i>\
							</a>';
                        if(full.item_status_id == 5){ // for current status == delete
                            template += '\
                                <a href="#" onclick="confirmStatusChange('+data+',2)" class="btn btn-sm btn-clean btn-icon" title="Ubah status produk ini">\
                                    <i class="la la-flag-o"></i>\
                                </a>\
                            ';
                        }else{
                            template += '\
                                <a href="#" onclick="confirmStatusChange('+data+','+full.item_status_id+')" class="btn btn-sm btn-clean btn-icon" title="Ubah status produk ini">\
                                    <i class="la la-flag-o"></i>\
                                </a>\
                                <a href="#" onclick="confirmStatusChange('+data+',5)" class="btn btn-sm btn-clean btn-icon" title="Hapus produk ini">\
                                    <i class="la la-trash"></i>\
                                </a>\
                            ';
                        }
                        return template;
                    },
                },
                {
                    targets: 0,
                    title: '#',
                    render: function(data, type, full, meta) {
                        return '';
                    }
                },
                {
                    width: '70px',
                    targets: 1,
                    render: function(data, type, full, meta) {
                        let temp_style = '';
                        if(full.item_status_id == 5){ temp_style = 'filter: grayscale(80%);';}
                        if(data) {
                            let display =  '<div class="symbol-group symbol-hover">';
                            let display_pic_item_count = 0;
                            for(let i=0;i<data.length;i++){
                                display_pic_item_count++;
                                display +=      '<a className="symbol symbol-circle" href="'+IMG_URL+data[i]['thumbnail_url']+'" data-toggle="lightbox" data-gallery="example-gallery">\
                                                   <img alt="Pic" src="'+IMG_URL+data[i]['thumbnail_url']+'" style="'+temp_style+'" width="50px" height="50px"/>\
                                                 </a>';
                                if(display_pic_item_count == display_pic_item_limit){
                                    break;
                                }
                            }
                            if(data.length>display_pic_item_limit) {
                                display +=      '<div class="symbol symbol-circle symbol-light-primary">\
                                                    <span class="symbol-label font-weight-bold">' + (data.length - display_pic_item_limit) + '++</span>\
                                                 </div>';
                            }
                            display +=      '</div>';
                            return display;
                            // return  '<div class="d-flex justify-content-between mr-4">\
                            //             <div class="symbol symbol-70 flex-shrink-0 bg-dark">\
                            //                 <div class="symbol-label" style="background-image: url('+data[0]['thumbnail_url']+')"></div>\
                            //             </div>\
                            //         </div>\
                            //         <label class="label label-inline" data-action="change" data-toggle="tooltip" title="" data-original-title="image count">\
                            //                     '+(data.length-1)+' ++ \
                            //         </label>';
                        }else{
                            return '<a href="#" class="h-70px w-70px btn btn-light-primary d-flex flex-column flex-center font-weight-bolder p-0">\
                                        <span class="svg-icon svg-icon-lg m-0">\<\
                                            <!--begin::Svg Icon | path:assets/media/svg/icons/Design/Image.svg-->\
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">\
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">\
                                                    <polygon points="0 0 24 0 24 24 0 24"></polygon>\
                                                     <path d="M6,5 L18,5 C19.6568542,5 21,6.34314575 21,8 L21,17 C21,18.6568542 19.6568542,20 18,20 L6,20 C4.34314575,20 3,18.6568542 3,17 L3,8 C3,6.34314575 4.34314575,5 6,5 Z M5,17 L14,17 L9.5,11 L5,17 Z M16,14 C17.6568542,14 19,12.6568542 19,11 C19,9.34314575 17.6568542,8 16,8 C14.3431458,8 13,9.34314575 13,11 C13,12.6568542 14.3431458,14 16,14 Z" fill="#000000"></path>\
                                                </g>\
                                            </svg>\
                                            <!--end::Svg Icon-->\
                                        </span>Upload\
                                    </a>';
                        }
                    },
                },
                {
                    width: '200px',
                    targets: 2,
                    render: function(data, type, full, meta) {
                        let temp_class = 'wrap-text-md';
                        if(full.item_status_id == 5){ temp_class += ' text-muted';}else{ temp_class += ' text-dark';}
                        if(data){
                            return '<a href="'+HOST_URL + '/product/detail/'+full.id+'" target="_blank" class="d-block '+temp_class+'" title="'+data+'">'+data+'</a>';
                        }else{
                            return '<a class="'+temp_class+'">-</a>';
                        }
                    },
                },
                {
                    width: '70px',
                    targets: 3,
                    render: function(data, type, full, meta) {
                        let temp_class = '';
                        if(full.item_status_id == 5){ temp_class = 'text-muted';}
                        if(data){
                            return '<span class="font-weight-bolder d-block font-size-lg '+(temp_class?temp_class:'text-primary')+'">'+(data)+'</span>';
                        }else{
                            return '<span class="'+temp_class+'">-</span>';
                        }
                    },
                },
                {
                    width: '80px',
                    targets: 4,
                    render: function(data, type, full, meta) {
                        let temp_class = '';
                        if(full.item_status_id == 5){ temp_class = 'text-muted';}
                        if(data){
                            return '<span class="'+temp_class+'">'+data.toLowerCase()+'</span>';
                        }else{
                            return '<span class="'+temp_class+'">-</span>';
                        }
                    },
                },
                {
                    width: '150px',
                    targets: 5,
                    render: function(data, type, full, meta) {
                        let temp_class = '';
                        if(full.item_status_id == 5){ temp_class = 'text-muted';}
                        if(data){
                            return '<span class="label label-pill label-inline '+temp_class+'">Rp. '+formatter.format(data)+'</span>';
                        }else{
                            return '<span class="'+temp_class+'">-</span>';
                        }
                    },
                },
                {
                    width: '150px',
                    targets: 6,
                    render: function(data, type, full, meta) {
                        let temp_class = '';
                        if(full.item_status_id == 5){ temp_class = 'text-muted';}
                        if(data){
                            return  '<span class="'+temp_class+'">'+data.toLowerCase()+'</span>'+
                                    '<br>'+(full.need_approval?'<small class="text-warning">menunggu persetujuan admin</small>':'');
                        }else{
                            return  '<span class="'+temp_class+'">-</span>';
                        }
                    },
                },
            ],
            layout: {
                theme: 'default',
                scroll: false,
                height: null,
                footer: false,
                spinner: {
                    overlayColor: '#000000',
                    opacity: 0,
                    type: 'loader',
                    state: 'primary',
                    message: true,
                },
            },
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


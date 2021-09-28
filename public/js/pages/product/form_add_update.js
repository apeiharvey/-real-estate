'use strict';

console.log('..Product UNIVERSAL (FORM) Presented..');

$(document).on('click', '[data-toggle="lightbox"]', function(event) { // zoom image
    event.preventDefault();
    $(this).ekkoLightbox();
});

// begin::Select2
    $('#kt_select2_item_type').select2({
        placeholder: "silahkah pilih",
    });
    $('#kt_select2_category_1').select2({
        placeholder: "silahkah pilih",
    });
    $('#kt_select2_class').select2({
        placeholder: "silahkah pilih",
    });
    $('#kt_select2_uom').select2({
        placeholder: "silahkah pilih",
    });
// end::Select2

// begin::Upload Image
    Dropzone.autoDiscover = false;
    var dataListUpload;

    $(function() {
        $("#dataListUpload").sortable({
            items: '.dz-preview',
            cursor: 'move',
            opacity: 0.5,
            containment: '#dataListUpload',
            distance: 20,
            tolerance: 'pointer',
        }).disableSelection();
    });

    var dataListUpload = new Dropzone("div#dataListUpload", {
        paramName: "files", // The name that will be used to transfer the file
        addRemoveLinks: true,
        uploadMultiple: true,
        autoProcessQueue: false,
        thumbnailWidth: 100,
        thumbnailHeight: 100,
        parallelUploads: 50,
        maxFilesize: 3, // MB
        acceptedFiles: ".png, .jpeg, .jpg, .gif",
        dictRemoveFile: " Remove",
        dictCancelUpload: "Cancel",
        url: HOST_URL+"/product/api/upload_images",
        headers: {
            'x-csrf-token': $('meta[name="csrf-token"]').attr('content'),
        },
    });

    dataListUpload.on("sending", function(file, xhr, formData) {
        formData.append('section', $("#data-section").val());
        $('.dz-preview .dz-filename').each(function(i) {
            formData.append('filenames['+i+']',$(this).find('span').text());
        });
        $("#link_url .add_link_urls").each(function(i){
            formData.append('url_link['+i+']',$(this).find(".add_data_url").val())
        });

    });

    dataListUpload.on("success", function(file, message){
        var response = JSON.parse(message);
        if($.trim(response.result_status).toUpperCase()==="SUCCESS"){
            Swal.fire({
                title: "Yeah.. it works!",
                html: response.result_message,
                type: "success",
                confirmButtonClass: 'btn btn-primary',
                buttonsStyling: false,
            });
            dataListView.ajax.reload();
        }else{
            Swal.fire({
                title: "Opps.. Error!",
                html: response.result_message,
                type: "error",
                confirmButtonClass: 'btn btn-primary',
                buttonsStyling: false,
            });
        }
    });

    dataListUpload.on("error", function (file, errorMessage) {
        toastr.error(errorMessage, 'Upload Failed!', { "progressBar": true })
    });

    dataListUpload.on("complete", function(file) {
        dataListUpload.removeFile(file);
        $("#data-section").val(0);
    });

    dataListUpload.on("addedfile", function(file) {
        var link_url_temp = $("#link_url_temp").html();
        var link_url_copied = $(link_url_temp).clone();
        $(link_url_copied).find(".add_link_url_index").text(dataListUpload.files.length)
        $("#link_url").append(link_url_copied);

        if (this.files.length) {
            var _i, _len;
            for (_i = 0, _len = this.files.length; _i < _len - 1; _i++) // -1 to exclude current file
            {
                if(this.files[_i].name === file.name)
                {
                    this.removeFile(file);
                    Swal.fire({
                        title: 'An image with the same name already exists',
                        text: 'Please upload images with a different name!',
                        animation: false,
                        customClass: 'animated shake',
                        confirmButtonClass: 'btn btn-primary',
                        buttonsStyling: false,
                    })
                }
            }
        }
    });

    dataListUpload.on("removedfile", function(file){
        $("#link_url .add_link_urls:last-child").remove();
    });
// end::Upload Image

// begin::Datatables
var KTDatatableChildRemoteDataTable = function() {

    var datatable_fx = function() {

        var datatable = $('#kt_datatable').KTDatatable({
            processing: true,
            serverSide: true,
            data: {
                type: 'remote',
                source: {
                    read: {
                        url: HOST_URL + '/product/api/get_list_book?search='+$("[name='name_to_search']").val(),
                        type: 'POST',
                        headers: {
                            'x-csrf-token': $('meta[name="csrf-token"]').attr('content'),
                        },
                    },
                },
                pageSize: 20, // display x records per page
                serverPaging: true,
                serverFiltering: true,
                serverSorting: true,
            },
            // layout definition
            layout: {
                scroll: false,
                footer: false,
            },
            // column sorting
            sortable: true,
            pagination: true,
            detail: {
                title: 'Load sub table',
                content: subTableInit,
            },
            search: {
                input: $('#kt_datatable_search_query'),
                key: 'generalSearch'
            },
            // columns definition
            columns: [
                {
                    field: 'id',
                    title: '',
                    sortable: false,
                    width: 30,
                    textAlign: 'center',
                }, {
                    field: 'title',
                    title: 'Judul',
                    sortable: 'asc',
                    width: 350,
                    template: function(row, i) {
                        return '<a onclick="selectBookParam('+i+')" class="font-size-sm">' + row.title + '</a>\
                                <textarea name="book_detail['+i+']" hidden>'+JSON.stringify(row)+'</textarea>';
                    }
                }, {
                    field: 'author',
                    title: 'Penulis',
                    sortable: 'asc',
                    width: 200,
                    template: function(row) {
                        return '<span class="text-muted font-size-sm">' + row.author + '</span>';
                    }
                }, {
                    field: 'descriptions',
                    title: 'Deskripsi',
                    sortable: 'asc',
                    width: 200,
                    template: function(row) {
                        return '<span class="font-size-sm">' + row.descriptions + '</span>';
                    }
                }
            ]
        });

        $('#kt_datatable_search_status').on('change', function() {
            datatable.search($(this).val().toLowerCase(), 'Status');
        });

        $('#kt_datatable_search_type').on('change', function() {
            datatable.search($(this).val().toLowerCase(), 'Type');
        });

        $('#kt_datatable_search_status, #kt_datatable_search_type').selectpicker();


        function subTableInit(e) {
            $('<div/>').attr('id', 'child_data_ajax_' + e.data.RecordID).appendTo(e.detailCell).KTDatatable({
                data: {
                    type: 'local',
                    source: e.data.book_prices,
                    pageSize: 5,
                },

                // layout definition
            layout: {
                    scroll: false,
                    footer: false,

                    // enable/disable datatable spinner.
                    spinner: {
                        type: 1,
                        theme: 'default',
                    },
                },

                sortable: true,

                // columns definition
                columns: [
                    {
                        field: 'zone',
                        title: 'Zona',
                        sortable: true,
                        width: 100,
                    }, {
                        field: 'price',
                        title: 'Harga',
                        width: 300
                    }],
            });
        }
    };

    return {
        init: function() {
            datatable_fx();
        },
        reload: function() {
            $('#kt_datatable').KTDatatable().destroy();
            datatable_fx();
        },
    };
}();
// end::Datatables

// begin::functions
    // #-----------------------------------price
    let tierprice_perzone = {
        1:{'counter':0},
        2:{'counter':0},
        3:{'counter':0},
        4:{'counter':0},
        5:{'counter':0}
    };

    function RemoveTierPrice(zone,count){
        $('#pricelist_zone'+zone+'_'+count).remove();
        if($('input[name="price[wholesaler]['+zone+'][price][]"]').length == false){
            $('.display_grosir_zone'+zone).hide();
        }
    }

    function addNewTierPrice(zone, data=null){
        console.log('trying to add new tier price of ZONE '+zone+' on COUNT '+tierprice_perzone[zone]['counter']);
        if($('.display_grosir_zone'+zone).is(':hidden')){
            $('.display_grosir_zone'+zone).show();
        }

        tierprice_perzone[zone]['counter'] += 1;
        let template =
            '<div id="pricelist_zone'+zone+'_'+tierprice_perzone[zone]['counter']+'" class="row wholesaler-item">\
                <div class="col-lg-5">\
                    <div class="input-group">\
                        <input type="number" class="form-control form-control-lg form-control-solid check-min-quantity" min="1"\
                         name="price[wholesaler]['+zone+'][min_quantity][]" onblur="checkMinQuantity(this, '+zone+')"\
                         value="'+(data && data.min_quantity? data.min_quantity:"")+'" required/>\
                        <div class="input-group-append">\
                            <span class="input-group-text text-uom">pcs</span>\
                        </div>\
                    </div>\
                </div>\
                <div class="col-lg-5">\
                    <div class="form-group">\
                        <div class="input-group">\
                            <div class="input-group-prepend">\
                                <span class="input-group-text">Rp.</span>\
                            </div>\
                            <input class="form-control form-control-lg form-control-solid currency_mask_onappend" type="text" name="price[wholesaler]['+zone+'][price][]"\
                             value="'+(data && data.price? data.price:"")+'" required/>\
                        </div>\
                    </div>\
                </div>\
                <div class="col-lg-2 mb-2">\
                    <br><a onclick="RemoveTierPrice('+zone+','+tierprice_perzone[zone]['counter']+')"><i class="fas fa-times fa-sm text-danger"></a></i>\
                </div>\
            </div>';
        $('#pricelist_zone'+zone+'_more').append(template);
    }

    $('input[name="is_every_zone_same_price"]').change(function() {
        console.log('is_every_zone_same_price >> ', $(this).is(":checked"));
        displayEveryZoneSamePrice(this);
    })

    function checkMinQuantity(el,zone) {
        let current_val = $(el).val();
        let prev_val = $(el).prev().children('input[name="price[wholesaler]['+zone+'][min_quantity]"]').val();

        console.log('checkMinQuantity', current_val, prev_val);

        if(current_val < prev_val){
            alert('Minimal pembelian yang didefinisikan terakhir harus lebih besar dari minimal pembelian sebelumnya');
            $(el).val('');
        }
    }

    function displayEveryZoneSamePrice(el,recreate=0){
        if($(el).is(":checked")) {
            $('#pricelist_zone1_title').html('ZONA 1-5');
            $('.is_every_zone_same_price__affected_div_class').each(function(i,obj){
                $(obj).hide();
            });
            $('.is_every_zone_same_price__affected_input_class').each(function(i,obj){
                $(obj).removeAttr('required');
            });
            $('.attribute_perzone_subtitle').each(function(i,obj){
                $(obj).hide();
            });
        }else{
            $('#pricelist_zone1_title').html('ZONA 1');
            $('.is_every_zone_same_price__affected_div_class').each(function(i,obj){
                $(obj).show();
            });
            $('.is_every_zone_same_price__affected_input_class').each(function(i,obj){
                $(obj).attr('required');
            });
            $('.attribute_perzone_subtitle').each(function(i,obj){
                $(obj).show();
            });
        }

        if(recreate){
            recreateDisplayPrice();
        }
    }

    $('.currency_mask').mask('000.000.000.000.000', {
        reverse: true
    });

    $(document).on("click",".button-new-tier-price",function(){  // to detect new appended dom
    }).on("keyup",".currency_mask_onappend",function(){
        console.log('test');
        $(this).mask('000.000.000.000.000', {
            reverse: true
        });
    })

    function resetPrice(){
        for(let i=1; i<=5;i++){
            $('[name="price[retail]['+i+'][price]"]').val('');
            $('.wholesaler-item').each(function(i,obj){
                $(obj).remove();
            });
            $('.display_grosir_zone'+i).hide();
        }
    }
    // #-----------------------------------uom
    function changeUoM(){
        let uom = $('select[name="sales_uom"]').val();
        console.log('change to', uom);
        $('.text-uom').each(function(i,obj){
            $(obj).html(uom);
        });
    }
    // #-----------------------------------category
    function changeCategory(level){
        let parent_id = $('#kt_select2_category_'+level).val();
        let allow_engender = $('#kt_select2_category_'+level).data('allow_engender');
        $.ajax({
            url: HOST_URL +'/product/api/get_category',
            headers: {
                'x-csrf-token': $('meta[name="csrf-token"]').attr('content'),
            },
            type: "POST",
            data:{
                parent_id: parent_id
            },
            success:function(data){
                console.log('changeCategory',data);
                if(data.status){
                    if(data.detail.length > 0){
                        if(allow_engender){
                            addNewLevelCategory(data.detail,level);
                        }
                    }else{
                        destroyChildCategory(level);
                        $('.kt_select2_category_info').remove();
                        let template = '<span class="row text-success float-right pt-2 kt_select2_category_info"><b>kategori komplit.<b></span>';
                        $('#category_detail_more').append(template);
                    }
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        html: data.detail,
                        type: "error",
                        buttonsStyling: false,
                        confirmButtonClass: "btn btn-error"
                    });
                    return false;
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
    }

    function recreateDisplayCategory_child(level,parent_id,selected_id,loading_param=null){
        $.ajax({
            url: HOST_URL +'/product/api/get_category',
            headers: {
                'x-csrf-token': $('meta[name="csrf-token"]').attr('content'),
            },
            type: "POST",
            data:{
                parent_id: parent_id
            },
            success:function(data){
                // console.log('recreateDisplayCategory_child',data);
                if(data.status){
                    if(data.detail.length > 0){
                        addNewLevelCategory(data.detail,level,selected_id,0);
                    }else{
                        destroyChildCategory(level);
                        $('.kt_select2_category_info').remove();
                        let template = '<span class="row text-success float-right pt-2 kt_select2_category_info"><b>kategori komplit.<b></span>';
                        $('#category_detail_more').append(template);
                    }

                    if(loading_param){
                        $('#'+loading_param.id).hide();
                    }
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        html: data.detail,
                        type: "error",
                        buttonsStyling: false,
                        confirmButtonClass: "btn btn-error"
                    });
                    return false;
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
    }

    function destroyChildCategory(level){
        let limit = $(".select_category").length;
        for (let i = parseInt(level)+1; i <= limit; i++) {
            // console.log(i, 'removing', '#kt_select2_category_'+i);
            $('#kt_select2_category_'+i).select2('destroy');
            $('#kt_select2_category_'+i).remove();
        }
    }

    function addNewLevelCategory(data,level,selected_id=null,allow_engender=1){
        destroyChildCategory(level);
        let current_level = parseInt(level)+1;
        let options = ''; let category_selection_id = 'kt_select2_category_'+current_level;
        for(let i=0;i<data.length;i++){
            options += '<option value="'+data[i].id+'">'+data[i].category+'</option>';
        }
        let template =
            '<select class="form-control select2 row select_category" id="'+category_selection_id+'" onchange="changeCategory('+current_level+')"\
                     name="category_id[]" data-level="'+current_level+'" data-allow_engender='+allow_engender+' required>\
                <option label="Label"></option>'+options+'\
            </select>';
        $('#category_detail_more').append(template);
        $('#'+category_selection_id).select2({
            placeholder: "silahkah pilih anak kategori",
        });
        $('.kt_select2_category_info').remove();

        if(selected_id){
            $('#'+category_selection_id).val(selected_id).trigger('change');
        }
    }
    // #-----------------------------------product origin
    $('.product_origin').change(function() {
        $('.product_origin').not(this).prop('checked', false);
    })
    // #-----------------------------------type
    $('.product_type').change(function() {
        changeProductType($(this).val());
    })

    function changeProductType(type_id){
        if(type_id == 1){ // book
            $('#name_wrap').hide();
            $('#name_to_search_wrap').show();
        }else{ //other than book
            $('#name_wrap').show();
            $('#name_to_search_wrap').hide();

            if($('[name="item_type_id"]').data('pre') == 1){
                $('#accordionBook').hide();
                $('[name="puskurbuk_id"]').val('');
                $('[name="puskurbuk_isbn"]').val('');
                $('[name="puskurbuk_nuib"]').val('');
                $('[name="name_to_search"]').val('');
                $('[name="name"]').val('');
                $('[name="name"]').removeAttr('readonly');
                $('[name="description"]').val('');
                $('[name="description"]').removeAttr('readonly');
                $('[name="is_every_zone_same_price"]').prop('checked', false);
                $('[name="is_every_zone_same_price"]').removeAttr('readonly');
                for(let i=1; i<=5;i++){
                    $('[name="price[retail]['+i+'][price]"]').val('');
                    $('[name="price[retail]['+i+'][price]"]').removeAttr('readonly');
                }
            }
        }
    }
    // #-----------------------------------book
    function selectBookParam(index){
        let row = JSON.parse($('[name="book_detail['+index+']"]').val());
        let max = 0;
        if(row){
            $('#collapseBook').collapse('hide');
            resetPrice();
            $('[name="puskurbuk_id"]').val(row.id);
            $('[name="puskurbuk_isbn"]').val(row.isbn);
            $('[name="puskurbuk_nuib"]').val(row.nuib);
            $('[name="name_to_search"]').val(row.title);
            $('[name="name"]').val(row.title);
            $('[name="name"]').prop('readonly', true);
            $('[name="description"]').val(row.descriptions);
            $('[name="description"]').prop('readonly', true);
            $('[name="is_every_zone_same_price"]').prop('checked', false);
            $('[name="is_every_zone_same_price"]').prop('readonly', true);
            for(let i=1; i<=row.book_prices.length;i++){
                max = (parseInt(row.book_prices[i-1].price) > max ? parseInt(row.book_prices[i-1].price) : max);
                $('[name="price[retail]['+i+'][price]"]').val(row.book_prices[i-1].price);
                $('[name="price[retail]['+i+'][price]"]').prop('readonly', true);
            }
            $('[name="listing_price"]').val(max);
        }else{
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                html: 'Detail buku tidak dapat diambil',
                type: "error",
                buttonsStyling: false,
                confirmButtonClass: "btn btn-error"
            });
        }
    }

    function getBookList(){
        $('#accordionBook').show();
        $('#collapseBook').collapse('show');

        // console.log($.fn.DataTable.fnIsDataTable('#kt_datatable'));
        if ($('#kt_datatable').children().length) {
            KTDatatableChildRemoteDataTable.reload();
        }else{
            KTDatatableChildRemoteDataTable.init();
        }
    }
    // #-----------------------------------display media
    $('input[name="video_link"]').change(function() {
        let video_link = $(this).val();
        if(!video_link){
            $('#preview-video').hide();
        }else{
            $('#preview-video').show();
            $('#preview-video').attr('href',video_link);
        }
    })
    // #-----------------------------------packet
    $('textarea[name="packet_description"]').attr("placeholder", "rincian komponen dalam paket, contoh:\n\n1. Komponen Car Lifting Qty 1 @15000000&#13\n2. Komponen mesin kompresor qty 2 @4000000\n3. Komponen Hydrolic oil qty 4 @3500000");
    $('input[name="is_packet"]').change(function() {
        let value = $(this).val();
        if(value == 1){
            $('.packet_description_wrap').show();
            $('input[name="is_packet"]').attr('required');
        }else{
            $('.packet_description_wrap').hide();
            $('input[name="is_packet"]').removeAttr('required');
        }
    })

// end::functions




'use strict';

console.log('..Product UPDATE (FORM) Presented..');
recreateDisplayCategory($('input[name="selected_category_id"]')); // 1. recreate the multilevel category
displayEveryZoneSamePrice($('input[name="is_every_zone_same_price"]'),1); // 2. determine 'one for all' or not // 3. recreate the multi price

let image_to_drop_ids = [];
let image_to_drop_timers;
let image_to_main_id = '';

// begin::Submit Form
    $(".button-edit").click(function(){
        // console.log("....");
        // console.log(dataListUpload.files);
        let product_id = $(this).data('id');

        // if(dataListUpload.files.length<=0){
        //     Swal.fire({
        //         title: 'Oops..',
        //         text: 'Tolong upload foto produk minimal 1',
        //         animation: false,
        //         customClass: 'animated shake',
        //         confirmButtonClass: 'btn btn-primary',
        //         buttonsStyling: false,
        //     });
        //     $('html, body').animate({scrollTop: '0px'}, 300);
        //     return;
        // }

        let images = [];
        for(let i=0;i<dataListUpload.files.length;i++){
            images[i] = dataListUpload.files[i]['dataURL'];
            // console.log('repeat-',i);
        }
        // console.log(images);

        if($("#editForm")[0].checkValidity()) {
            $(".button-form-action").each(function(i,obj){
                $(obj).hide();
            });
            $("#button-edit-info").html("Loading...");

            $("#editForm").ajaxSubmit({
                url: HOST_URL +'/product/api/update_item/'+product_id,
                headers: {
                    'x-csrf-token': $('meta[name="csrf-token"]').attr('content'),
                },
                type: "POST",
                data:{ 
                    images: images,
                    image_to_drop_ids: image_to_drop_ids,
                    image_to_main_id: image_to_main_id
                },
                success:function(data){
                    $(".button-form-action").each(function(i,obj){
                        $(obj).show();
                    });
                    $("#button-edit-info").html('Terbitkan');

                    if(data.status){
                        $('#editForm')[0].reset();
                        Swal.fire({
                            title:"Success!",
                            text: data.message,
                            type: "success",
                            buttonsStyling: false,
                            confirmButtonClass: "btn btn-success"
                        });

                        window.location = HOST_URL + '/product';
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
                    $(".button-form-action").each(function(i,obj){
                        $(obj).show();
                    });
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
        }else{
            $("#editForm")[0].reportValidity();
        }
    })
// end::Submit Form

// begin::Stock-Maintenance Form
    $(".button-adjust-stock").click(function(){
        let nature = $('select[name="stock_nature"]').val();
        let amount = $('input[name="stock_amount"]').val();
        let product_id = $(this).data('id');
        // console.log(nature, amount, product_id); 
        // return 1;

        if(!amount){
            alert('Jumlah stok yang diatur harus didefinisikan')
        }else{
            $(".button-form-action").each(function(i,obj){
                $(obj).hide();
            });
            $("#button-adjust-stock-info").html("Loading...");

            $.ajax({
                url: HOST_URL +'/product/api/adjust_stock/'+product_id,
                headers: {
                    'x-csrf-token': $('meta[name="csrf-token"]').attr('content'),
                },
                type: "POST",
                data:{ 
                    nature: nature,
                    amount: amount,
                },
                success:function(data){
                    // console.log(data);
                    $(".button-form-action").each(function(i,obj){
                        $(obj).show();
                    });
                    $("#button-adjust-stock-info").html('Konfirmasi Perubahan');

                    if(data.status){
                        $('select[name="stock_nature"]').val('+');
                        $('input[name="stock_amount"]').val('');
                        $('input[name="stock"]').val(data.detail.sum);
                        Swal.fire({
                            title:"Success!",
                            text: data.message,
                            type: "success",
                            buttonsStyling: false,
                            confirmButtonClass: "btn btn-success"
                        });
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
                    $(".button-form-action").each(function(i,obj){
                        $(obj).show();
                    });
                    $("#button-adjust-stock-info").html('');
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
    });
// end::Stock-Maintenance Form

// begin::functions
    function recreateDisplayCategory(el){
        let child_id = $(el).val();
        console.log('child_id', child_id);
        if(!child_id){ // category not defined
            $('#kt_select2_category_1').data('allow_engender', 1); // allow engender for further change
            $('#kt_select2_category_loading').hide();
        }else if(!$.isNumeric(child_id)){ // not a valid number
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                html: 'kategori tidak valid, mohon perbaiki dengan input ulang',
                type: "error",
                buttonsStyling: false,
                confirmButtonClass: "btn btn-error"
            });
            $('#kt_select2_category_loading').hide();
        }else{
            $.ajax({
                url: HOST_URL +'/product/api/get_category',
                headers: {
                    'x-csrf-token': $('meta[name="csrf-token"]').attr('content'),
                },
                type: "POST",
                data:{
                    child_id: child_id,
                },
                success:function(data){
                    // console.log('recreateDisplayCategory',data);
                    if(data.status){
                        let a = 1;
                        let loading_param = null;
                        for(let i=(data.detail.length-1);i>=0;i--){
                            if(i == (data.detail.length-1)){
                                if(i==0){
                                    $('#kt_select2_category_loading').hide();
                                }
                                $('#kt_select2_category_'+a).val(data.detail[i].id).trigger('change'); // safe when engender == 0
                            }else{
                                // console.log(a,data.detail[i+1].id,data.detail[i].id);
                                if(i==0){
                                    loading_param = {id:'kt_select2_category_loading'};
                                }
                                recreateDisplayCategory_child(a,data.detail[i+1].id,data.detail[i].id,loading_param);
                            }
                            $('#kt_select2_category_'+a).data('allow_engender', 1); // allow engender for further change
                            a++;
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
    }

    function recreateDisplayPrice(){
        let product_id = $('#btn-form-submit').data('id');
        $.ajax({
            url: HOST_URL +'/product/api/get_price',
            headers: {
                'x-csrf-token': $('meta[name="csrf-token"]').attr('content'),
            },
            type: "POST",
            data:{
                product_id: product_id
            },
            success:function(data){
                console.log('recreateDisplayPrice',data);
                if(data.status){
                    let qty_type, zone;
                    for(let i=0;i<data.detail.length;i++){
                        qty_type   = data.detail[i]['quantity_type'];
                        zone       = (data.detail[i]['zone_id']).replace(/\D/g,'');
                        // console.log('QTY--ZONE', qty_type, zone);

                        if(qty_type == 'RETAIL'){
                            $('input[name="price[retail]['+zone+'][price]"]').val(data.detail[i]['price']);
                        }else if(qty_type == 'WHOLESALER'){
                            addNewTierPrice(zone,{price: data.detail[i]['price'], min_quantity: data.detail[i]['min_quantity']});
                        }

                        if(i == (data.detail.length-1)){
                            $('#kt_modal_price_loading').hide();
                            $('#kt_modal_price_loading_wrap').removeClass('overlay overlay-block');
                        }
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
    
    function removeDisplayImage(num) {
        console.log('removeDisplayImage...'+num);
        $('#product_image'+num).hide();
        image_to_drop_ids.push($('#product_image'+num).data('image_id'));
        $('#product_image'+num+'_info').show();
        handlerImageDropTimer('product_image'+num,5,0);
    }

    function removeDisplayImage_resurrect(num) {
        console.log('removeDisplayImage_resurrect...'+num);
        $('#product_image'+num).show();
        image_to_drop_ids = jQuery.grep(image_to_drop_ids, function(value) {
            return value != $('#product_image'+num).data('image_id');
        });
        $('#product_image'+num+'_info').hide();
        handlerImageDropTimer('product_image'+num,0,0,1);
    }

    function handlerImageDropTimer(id,timer_count_seconds,timer_count_minutes,resurrect=0) {
        $('#'+id+'_info_counter').html((timer_count_minutes>9?timer_count_minutes:'0'+timer_count_minutes)+':'+(timer_count_seconds>9?timer_count_seconds:'0'+timer_count_seconds));
        if (timer_count_seconds > 0) {
            setTimeout(() => {
                timer_count_seconds--;
                handlerImageDropTimer(id,timer_count_seconds,timer_count_minutes);
            }, 1000);
        }else if(timer_count_minutes > 0){
            setTimeout(() => {
                timer_count_minutes--;
                timer_count_seconds = 49;
                handlerImageDropTimer(id,timer_count_seconds,timer_count_minutes);
            }, 2000);
        }else if(!resurrect){
            $('#'+id+'_wrap').hide(); // hidden the whole component
        }
    }

    function setToMainImage(num) {
        console.log('setToMainImage...'+num);
        image_to_main_id = $('#product_image'+num).data('image_id');
        $('[id^="product_toptitle_image"]').each(function(i,obj){
            $(obj).html('');
        });
        $('#product_toptitle_image'+num).html('<b>Gambar utama:</b>');
        // console.log('main product now = '+image_to_main_id);
    }

    $('[name="item_type_id"]').data('pre', $(this).val());
// end::functions
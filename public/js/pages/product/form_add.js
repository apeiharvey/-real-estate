'use strict';

console.log('..Product ADDITION (FORM) Presented..');

// begin::Submit Form
    $(".button-add").click(function(){
        // console.log("....");
        // console.log(dataListUpload.files);
        if(!$('[name="name"]').val() && ($('[name="item_type_id"] :selected').val() == 1)) {
            Swal.fire({
                title: 'Oops..',
                text: 'Mohon pilih buku dari list, atau jika tidak ada yang sesuai silahkan ajukan terlebih dahulu ke KEMENDIKBUD',
                animation: false,
                customClass: 'animated shake',
                confirmButtonClass: 'btn btn-primary',
                buttonsStyling: false,
            });
            $('html, body').animate({scrollTop: '0px'}, 300);
            return;
        }

        if(dataListUpload.files.length<=0){
            Swal.fire({
                title: 'Oops..',
                text: 'Tolong unggah foto produk minimal 1',
                animation: false,
                customClass: 'animated shake',
                confirmButtonClass: 'btn btn-primary',
                buttonsStyling: false,
            });
            $('html, body').animate({scrollTop: '0px'}, 300);
            return;
        }

        let images = [];
        for(let i=0;i<dataListUpload.files.length;i++){
            images[i] = dataListUpload.files[i]['dataURL'];
            // console.log('repeat-',i);
        }
        // console.log(images);

        if($("#addForm")[0].checkValidity()) {
            $(".button-add").each(function(i,obj){
                $(obj).hide();
            });
            $("#button-add-info").html("Loading...");


            $("#addForm").ajaxSubmit({
                url: HOST_URL +'/product/api/store_item',
                headers: {
                    'x-csrf-token': $('meta[name="csrf-token"]').attr('content'),
                },
                type: "POST",
                data:{
                    images: images
                },
                success:function(data){
                    $(".button-add").each(function(i,obj){
                        $(obj).show();
                    });
                    $("#button-add-info").html('Terbitkan');

                    if(data.status){
                        $('#addForm')[0].reset();
                        $('[id^=kt_select2_category_]').each(function(i,obj){
                            if(obj == $('[id=kt_select2_category_1]')){
                                $(obj).val('').trigger('change');
                            }else{
                                $(obj).select2('destroy');
                                $(obj).remove();
                            }
                        });
                        $('.kt_select2_category_info').remove();
                        $('#kt_select2_item_type').val('').trigger('change');
                        $('#kt_select2_class').val('').trigger('change');
                        $('#accordionBook').hide();
                        $('#name_wrap').show();
                        $('#name_to_search_wrap').hide();
                        Dropzone.forElement('#dataListUpload').removeAllFiles(true)

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

                    $(".button-add").each(function(i,obj){
                        $(obj).show();
                    });
                    $("#button-add-info").html('');
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
            $("#addForm")[0].reportValidity();
        }
    })
// end::Submit Form

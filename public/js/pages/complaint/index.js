'use strict';

console.log('..Complaint LIST Presented..');

displayRowDetail_Blank();
reloadComplainList();
if($('[name="complain_hash_get_url"]').val() || $('[name="order_detail_get_url"]').val()){
    displayRowDetail(null,1); // indirect
}

const template_status = {};
template_status['request_admin_help'] = '<a href="#" class="btn btn-transparent-warning font-weight-bold" id="button-process-info-wrap"><b>Penyedia</b> mengajukan penyelesaian oleh <b>Admin</b></a>';
template_status['close_continue'] = '<a href="#" class="btn btn-transparent-success font-weight-bold" id="button-process-info-wrap">Komplain Selesai</a>';

$(document).on('click', '[data-toggle="lightbox"]', function(event) { // zoom image
    event.preventDefault();
    $(this).ekkoLightbox();
});


// begin::Upload Image
Dropzone.autoDiscover = false;
var idDropzone = '#kt_inbox_compose_attachments';
var previewNode = $(idDropzone + " .dropzone-item");
previewNode.id = "";
var previewTemplate = previewNode.parent('.dropzone-items').html();
previewNode.remove();

var dataListUpload = new Dropzone('#kt_inbox_compose_attachments', {
    paramName: "image", // The name that will be used to transfer the file
    url: HOST_URL +'/complaint/api/sent_message',
    headers: {
        'x-csrf-token': $('meta[name="csrf-token"]').attr('content'),
    },
    autoProcessQueue: false,
    parallelUploads: 20,
    maxFilesize: 3, // MB
    acceptedFiles: ".png, .jpeg, .jpg, .gif",
    previewTemplate: previewTemplate,
    previewsContainer: idDropzone + " .dropzone-items", // container
    clickable: idDropzone + "_select", // trigger
    accept: function(file, done) {
        console.log("image uploaded");
        done();
    },
    init: function() {
        this.on("sending", function(file, xhr, formData) {
            document.querySelector(idDropzone + " .progress-bar").style.opacity = "1";
            $(".button-action").html("Loading...");
            $(".button-action").prop('disabled', true);
            $("#addForm").find("input, textarea").each(function(){
                formData.append($(this).attr("name"), $(this).val());
            });

            if($('[name="complain_hash_get_url"]').val() || $('[name="order_detail_get_url"]').val()){ // indirect
                formData.append('complain_hash',$('input[name="complain_hash_get_url"]').val());
                formData.append('order_detail_hash',$('input[name="order_detail_get_url"]').val());
            }else{
                formData.append('complain_hash',$('input[name="complain_hash"]').val());
            }
        });

        this.on("addedfile", function(file) {
            if (this.files[1]!=null){
                this.removeFile(this.files[0]);
            }
            $(document).find(idDropzone + ' .dropzone-item').css('display', '');
        });

        this.on("success", function(file, formData) {
            if(formData.status){
                displayNewRow_VendorReply();
            }else{
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: formData.message,
                    type: "error",
                    buttonsStyling: false,
                    confirmButtonClass: "btn btn-error"
                });
            }
            $(".button-action").html("Kirim");
            $(".button-action").prop('disabled', false);
        });

        this.on("error", function(file, errorMessage) {
            $(".button-action").html("Kirim");
            $(".button-action").prop('disabled', false);
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: errorMessage,
                type: "error",
                buttonsStyling: false,
                confirmButtonClass: "btn btn-error"
            });
        });

        this.on("complete", function(progress) {
            var thisProgressBar = idDropzone + " .dz-complete";
            setTimeout(function() {
                $(thisProgressBar + " .progress-bar, " + thisProgressBar + " .progress").css('opacity', '0');
            }, 300)
            // this.removeFile(file);
        });
    }
});
// end::Upload Image

function scrollToBottom(){ // scroll
    var element = document.getElementById('complaint_row_detail_more_loading_container');
    var messagesEl = KTUtil.find(element, '.messages');
    var scrollEl = KTUtil.find(element, '.scroll');
    scrollEl.scrollTop = parseInt(KTUtil.css(messagesEl, 'height'));
}

function reloadComplainList(){
    let status = ($('input[name="search_status"]:checked').data('status')).split(',');

    $('#complaint_rows_more_loading').show();
    $('#complaint_rows_more_loading_container').addClass('overlay-block');
    $.ajax({
        url: HOST_URL +'/complaint/api/get_list',
        headers: {
            'x-csrf-token': $('meta[name="csrf-token"]').attr('content'),
        },
        type: "POST",
        data:{
            status: status,
            search: $('input[name="search"]').val()
        },
        success:function(data){
            console.log('reloadComplainList',data);
            if(data.status){
                addRows_Complaint(data.detail);
            }else{
                $('#complaint_rows_more_loading').hide();
                $('#complaint_rows_more_loading_container').removeClass('overlay-block');
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
            $('#complaint_rows_more_loading').hide();
            $('#complaint_rows_more_loading_container').removeClass('overlay-block');
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

function destroyRows_Complaint(){
    $('#complaint_rows_more').html('');
}

function addRows_Complaint(data){
    destroyRows_Complaint();
    if(data.length){
        for(let i=0;i<data.length;i++){
            // <img alt="profile-picture" src="assets/media/users/300_15.jpg"> // for pp image
            let template_date = '';

            if(data[i].complaint_detail){
                let date = moment(data[i].created_at);
                if(data[i].complaint_detail[0]){
                    date = moment(data[i].complaint_detail[0].created_at);
                }

                if(date.isSame(YESTERDAY, 'd')){
                    template_date += 'Kemarin ';
                }else if(!date.isSame(TODAY, 'd')){
                    template_date += date.format('MMMM DD YYYY, ');
                }
                template_date += date.format('h:mm A');

            }

            let template =
                '<div class="d-flex align-items-center justify-content-between mb-5">\
                    <div class="d-flex align-items-center">\
                        <div class="symbol symbol-circle symbol-50 mr-3">\
                            <span class="font-size-h3 symbol-label font-weight-boldest">\
                            '+(data[i].customer?data[i].customer.name.split(/\s/).reduce((response,word)=> response+=word.slice(0,1),''):'...')+'\
                            </span>\
                        </div>\
                        <div class="d-flex flex-column">\
                            <a href="#" class="text-dark-75 text-hover-primary font-weight-bold font-size-lg" onclick="displayRowDetail(this)" data-complain_hash="'+data[i].complain_hash+'">\
                            '+(data[i].customer?data[i].customer.name:'...')+'<br>\
                            <span class="text-success">'+(data[i].order_detail?data[i].order_detail.order_no:'...')+'</span>\
                            </a>\
                            <span class="text-muted font-weight-bold font-size-sm"><br>\
                            '+(data[i].complaint_detail[0] && data[i].complaint_detail[0].complain_message?(data[i].complaint_detail[0].complain_message.length > 50?(data[i].complaint_detail[0].complain_message.substring(0, 50))+'...':data[i].complaint_detail[0].complain_message):'-')+'\
                            </span>\
                        </div>\
                    </div>\
                    <div class="d-flex flex-column align-items-end">\
                        <span class="text-muted font-weight-bold font-size-sm">'+template_date+'</span><br>\
                        '+(data[i].is_read_by_vendor == false ?'<span class="label label-sm label-warning" id="unread_'+data[i].complain_hash+'"></span>':'')+'\
                    </div>\
                </div>';
            $('#complaint_rows_more').append(template);
        }
    }else{
        $('#complaint_rows_more').append('<center class="text-muted"><small>Tidak ada komplain</small></center>');
    }
    $('#complaint_rows_more_loading').hide();
    $('#complaint_rows_more_loading_container').removeClass('overlay-block');
}

function treatMsgRight(str){
    if(str){
        // if contain link
        str = str.replace(/(www\..+?)(\s|$)/g, function(text, link) {
            return '<a href="http://'+ link +'" target="_blank">'+ link +'</a>';
        });
    }else{
        str = '';
    }

    return str;
}

function amILink(str){
    // if contain link
    var elm;
    if(!elm){
        elm = document.createElement('input');
        elm.setAttribute('type', 'url');
    }
    elm.value = str;
    return elm.validity.valid;
}

function setAsRead(complain_hash){
    $.ajax({
        url: HOST_URL +'/complaint/api/set_as_read',
        headers: {
            'x-csrf-token': $('meta[name="csrf-token"]').attr('content'),
        },
        type: "POST",
        data:{
            complain_hash: complain_hash
        },
        success:function(data){
            if(data.status){
                console.log('set as read');
                $('#unread_'+complain_hash).remove();
            }else{
                console.log('set as read failed',data.message);
            }
        },
        error:function(xhr, status, error){
            console.log('set as read got error',xhr.responseText);
        }
    });
}

function displayRowDetail_Blank(){
    $('#complaint_detail_cust_name').html('');
    $('#complaint_row_detail_more_loading').hide();
    $('#complaint_row_detail_more_loading_container').removeClass('overlay-block');
    $(".add-form-el").each(function(i,obj){
        $(obj).attr('disabled',true);
    });
    $('.on-not-blank').each(function(i,obj){
        $(obj).hide();
    });

    $('#complaint_row_detail_card').addClass('card-custom wave wave-animate wave-success');
    $('#complaint_row_detail_more').html('<center class="text-muted"><small>Pilih pesan komplain untuk menampilkan detail pesan</small></center>');
}

function displayRowDetail(el,indirect=0){
    $('#complaint_row_detail_more_loading').show();
    $('#complaint_row_detail_more_loading_container').addClass('overlay-block');
    $(".add-form-el").each(function(i,obj){
        $(obj).removeAttr('disabled');
    });
    $('.on-not-blank').each(function(i,obj){
        $(obj).show();
    });

    $('#complaint_row_detail_card').removeClass('card-custom wave wave-animate wave-success');


    let complain_hash, order_detail_hash, message;
    if(indirect){
        complain_hash = $('[name="complain_hash_get_url"]').val();
        order_detail_hash = $('[name="order_detail_hash_get_url"]').val();
        message = '(URL unik)';
    }else{
        complain_hash = $(el).data('complain_hash');
        order_detail_hash = null;
        message = null;
    }

    $.ajax({
        url: HOST_URL +'/complaint/api/get_list_message',
        headers: {
            'x-csrf-token': $('meta[name="csrf-token"]').attr('content'),
        },
        type: "POST",
        data:{
            complain_hash: complain_hash,
            order_detail_hash: order_detail_hash,
            message: message
        },
        success:function(data){
            // console.log('displayRowDetail',data.detail);
            if(data.status){
                let template_customer_name  = data.detail.parent.customer.name;
                let template_vendor_name    = data.detail.parent.vendor.name;
                let template_customer_pp    =  '<span class="font-size-h3 symbol-label font-weight-boldest">\
                                                '+(data.detail.parent.customer?data.detail.parent.customer.name.split(/\s/).reduce((response,word)=> response+=word.slice(0,1),''):'...')+'\
                                                </span>';
                let template_vendor_pp      = '<img alt="profile-picture" src="'+IMG_URL+data.detail.parent.vendor.avatar_img+'">';
                let template_admin_name     = 'Admin';
                let template_admin_pp       = '<span class="font-size-h3 symbol-label font-weight-boldest bg-warning">A</span>';

                $('#complaint_row_detail_more').html('');
                $('#complaint_detail_cust_name').html(data.detail.parent.customer.school.school_name+'<br>'+template_customer_name);
                $('input[name="vendor_name"]').val(template_vendor_name);
                $('input[name="vendor_pp_src"]').val(IMG_URL+data.detail.parent.vendor.avatar_img);

                if(data.detail.child && data.detail.child.length > 0){

                    let date;
                    let template = '';  let template_date = ''; let template_img = '';
                    let last_speaker = ''; let now_speaker = ''; let img_src = '';

                    for(let i=0;i<data.detail.child.length;i++){
                        template_date = '';
                        date = moment(data.detail.child[i].created_at);
                        if(date.isSame(YESTERDAY, 'd')){
                            template_date += 'Kemarin ';
                        }else if(!date.isSame(TODAY, 'd')){
                            template_date += date.format('MMMM DD YYYY, ');
                        }
                        template_date += date.format('h:mm A');

                        if(data.detail.child[i].image){
                            img_src = (amILink(data.detail.child[i].image)?data.detail.child[i].image:IMG_URL+data.detail.child[i].image);
                            template_img =     '<a href="'+img_src+'" data-toggle="lightbox" data-gallery="example-gallery">\
                                                    <img alt="upload-picture" src="'+img_src+'" class="mb-10" style="max-width:300px">\
                                                </a><br>';
                        }else{
                            template_img = '';
                        }

                        if(data.detail.child[i].from_member){
                            now_speaker = 'MEMBER';
                            template = '<div class="d-flex flex-column mb-5 align-items-start">';
                            if(last_speaker != now_speaker){
                                template += '   <div class="d-flex align-items-center">\
                                                    <div class="symbol symbol-circle symbol-40 mr-3">'+template_customer_pp+'</div>\
                                                    <div>\
                                                        <a href="#" class="text-dark-75 text-hover-primary font-weight-bold font-size-h6">'+template_customer_name+'</a>\
                                                    </div>\
                                                </div>';
                            }
                            template += '    <div class="mt-2 rounded p-5 bg-light-success text-dark-50 font-weight-bold font-size-lg text-left max-w-400px treat-msg">';
                            template +=        template_img+treatMsgRight(data.detail.child[i].complain_message)+'\
                                            </div>\
                                            <span class="text-muted font-size-sm">'+template_date+'</span>\
                                        </div>';
                        }else if(data.detail.child[i].from_vendor){
                            now_speaker = 'VENDOR';
                            template = '<div class="d-flex flex-column mb-5 align-items-end">';
                            if(last_speaker != now_speaker){
                                template += '   <div class="d-flex align-items-center">\
                                                    <div>\
                                                        <a href="#" class="text-dark-75 text-hover-primary font-weight-bold font-size-h6">'+template_vendor_name+'</a>\
                                                    </div>\
                                                    <div class="symbol symbol-circle symbol-40 ml-3">'+template_vendor_pp+'</div>\
                                                </div>';
                            }
                            template += '    <div class="mt-2 rounded p-5 bg-light-primary text-dark-50 font-weight-bold font-size-lg text-right max-w-400px treat-msg">';
                            template +=        template_img+treatMsgRight(data.detail.child[i].complain_message)+'\
                                            </div>\
                                            <span class="text-muted font-size-sm">'+template_date+'</span>\
                                        </div>';
                        }else if(data.detail.child[i].from_admin){
                            now_speaker = 'ADMIN';
                            template = '<div class="d-flex flex-column mb-5 align-items-start">';
                            if(last_speaker != now_speaker){
                                template += '   <div class="d-flex align-items-center">\
                                                    <div class="symbol symbol-circle symbol-40 mr-3">'+template_admin_pp+'</div>\
                                                    <div>\
                                                        <a href="#" class="text-dark-75 text-hover-primary font-weight-bold font-size-h6">'+template_admin_name+'</a>\
                                                    </div>\
                                                </div>';
                            }
                            template += '    <div class="mt-2 rounded p-5 bg-light-warning text-dark-50 font-weight-bold font-size-lg text-right max-w-400px treat-msg">';
                            template +=        template_img+treatMsgRight(data.detail.child[i].complain_message)+'\
                                            </div>\
                                            <span class="text-muted font-size-sm">'+template_date+'</span>\
                                        </div>';
                        }

                        $('#complaint_row_detail_more').append(template);

                        last_speaker = now_speaker;

                    }
                }else{}
                $('#complaint_row_detail_more_loading').hide();
                $('#complaint_row_detail_more_loading_container').removeClass('overlay-block');
                $('input[name="complain_hash"]').val(complain_hash);
                setAsRead(complain_hash);

                if($('input[name="search_status"]:checked').data('id') == 'search_status_done'){
                    $('#button-process-info-wrap').show();
                    $('#button-process-info-wrap').html('');
                    $('#button-process-info-wrap').append(template_status['close_continue']);
                    $('#button-process-action-wrap').hide();
                }else if(data.detail.parent.request_admin_help){
                    $('#button-process-info-wrap').show();
                    $('#button-process-info-wrap').html('');
                    $('#button-process-info-wrap').append(template_status['request_admin_help']);
                    $('#button-process-action-wrap').show();
                }else{
                    $('#button-process-info-wrap').hide();
                    $('#button-process-info-wrap').html('');
                    $('#button-process-action-wrap').show();
                }
            }else{
                $('#complaint_row_detail_more_loading').hide();
                $('#complaint_row_detail_more_loading_container').removeClass('overlay-block');
                $('input[name="complain_hash"]').val('');

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

            scrollToBottom();
            console.log('into the bottom');
        },
        error:function(xhr, status, error){
            $('#complaint_row_detail_more_loading').hide();
            $('#complaint_row_detail_more_loading_container').removeClass('overlay-block');
            console.log('whuuutt [2]');
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

function displayNewRow_VendorReply(){
    let message = $('[name="message"]').val();
    let template = '<div class="d-flex flex-column mb-5 align-items-end">\
                        <div class="d-flex align-items-center">\
                            <div>\
                                <span class="text-muted font-size-sm">'+(moment().format('h:mm A'))+'</span>\
                                <a href="#" class="text-dark-75 text-hover-primary font-weight-bold font-size-h6">'+$('input[name="vendor_name"]').val()+'</a>\
                            </div>\
                            <div class="symbol symbol-circle symbol-40 ml-3">\
                                <img alt="profile-picture" src="'+$('input[name="vendor_pp_src"]').val()+'">\
                            </div>\
                        </div>\
                        <div class="mt-2 rounded p-5 bg-light-primary text-dark-50 font-weight-bold font-size-lg text-right max-w-400px treat-msg">';
   if(dataListUpload.files.length){
    //    console.log(dataListUpload.files);
        template +=     '<a href="'+dataListUpload.files[0].dataURL+'" data-toggle="lightbox" data-gallery="example-gallery">\
                            <img alt="upload-picture" src="'+dataListUpload.files[0].dataURL+'" class="mb-10" style="max-width:370px">\
                        </a>';
    }
    template +=              treatMsgRight(message)+'\
                        </div>\
                    </div>';
    $('#complaint_row_detail_more').append(template);

    scrollToBottom();

    $('#addForm')[0].reset();
    Dropzone.forElement('#kt_inbox_compose_attachments').removeAllFiles(true);
}

// begin::Submit Form
$(".button-send").click(function(){

    if(dataListUpload.files.length<=0){
        let complain_hash = $('input[name="complain_hash"]').val();
        console.log('send w/ hash', complain_hash);

        if($("#addForm")[0].checkValidity()) {

            $(".button-action").html("Loading...");
            $(".button-action").prop('disabled', true);


            $("#addForm").ajaxSubmit({
                url: HOST_URL +'/complaint/api/sent_message',
                headers: {
                    'x-csrf-token': $('meta[name="csrf-token"]').attr('content'),
                },
                type: "POST",
                data:{
                    complain_hash: complain_hash
                },
                success:function(data){
                    $(".button-action").html("Kirim");
                    $(".button-action").prop('disabled', false);

                    if(data.status){
                        displayNewRow_VendorReply();
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
                    $(".button-action").html("Kirim");
                    $(".button-action").prop('disabled', false);
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
    }else{
        dataListUpload.processQueue();
    }
})

$(".button-process-complaint").click(function(){
    let complain_hash = $('input[name="complain_hash"]').val();
    let status = $(this).data('status');
    let reason, text;

    if(status == 'close_return'){
        reason = prompt("Silahkan masukkan alasan 'pesanan transaksi ditolak rampung dan dikembalikan' :");
        if (reason == null || reason == "") {
            text = "Aksi komplain dibatalkan";
            return;
        } else {
            text = "Aksi diproses";
        }
    }

    $.ajax({
        url: HOST_URL +'/complaint/api/confirm_action',
        headers: {
            'x-csrf-token': $('meta[name="csrf-token"]').attr('content'),
        },
        type: "POST",
        data:{
            status: status,
            complain_hash: complain_hash,
            reason: reason
        },
        success:function(data){
            $(".button-action").html("Kirim");
            $(".button-action").prop('disabled', false);

            if(data.status){
                Swal.fire({
                    title:"Success!",
                    text: data.message,
                    type: "success",
                    buttonsStyling: false,
                    confirmButtonClass: "btn btn-success"
                });

                if(status == 'request_admin_help'){
                    $('#button-process-info-wrap').show();
                    $('#button-process-info-wrap').html('');
                    $('#button-process-info-wrap').append(template_status['request_admin_help']);
                    $('#button-process-action-wrap').show();
                }else{
                    displayRowDetail_Blank()
                    reloadComplainList();
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
            $(".button-action").html("Kirim");
            $(".button-action").prop('disabled', false);
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
})
// end::Submit Form

$('.download-script').click(function(){
    let complain_hash = $('input[name="complain_hash"]').val();
    window.open(HOST_URL + '/complaint/download/' + complain_hash);
});

$("[name='search_status']").click(function(){
    reloadComplainList();
})

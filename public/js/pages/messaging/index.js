'use strict';

console.log('..Messaging LIST Presented..');

displayRowDetail_Blank();
reloadMessagingList();
if($('[name="room_hash_get_url"]').val()){
    displayRowDetail(null,1); // indirect
}

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
    url: HOST_URL +'/messaging/api/sent_message',
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

            formData.append('ref_type',$('input[name="search_type"]:checked').data('type'));
            formData.append('ref_id_hash',$('input[name="ref_id_hash"]').val());
            formData.append('room_hash',$('input[name="room_hash"]').val());
        });

        this.on("addedfile", function(file) {
            if (this.files[1]!=null){
                this.removeFile(this.files[0]);
            }
            $(document).find(idDropzone + ' .dropzone-item').css('display', '');
        });

        this.on("success", function(file, formData) {
            displayNewRow_VendorReply();
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

function reloadMessagingList(){
    let type = $('input[name="search_type"]:checked').data('type');

    // $('.button-process-complaint').each(function(i,obj){
    //     if($(obj).data('status') == 'close_continue'){
    //         if($('input[name="search_type"]:checked').data('id') != 'search_status_ongoing'){
    //             $(obj).hide();
    //         }else{
    //             $(obj).show();
    //         }
    //     }
    // })

    $('#complaint_rows_more_loading').show();
    $('#complaint_rows_more_loading_container').addClass('overlay-block');
    $.ajax({
        url: HOST_URL +'/messaging/api/get_list',
        headers: {
            'x-csrf-token': $('meta[name="csrf-token"]').attr('content'),
        },
        type: "POST",
        data:{
            type: type,
            search: $('input[name="search"]').val()
        },
        success:function(data){
            console.log('reloadMessagingList',data);
            if(data.status){
                addRows_Messaging(type, data.detail);
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

function destroyRows_Messaging(){
    $('#complaint_rows_more').html('');
}

function addRows_Messaging(type, data){

    destroyRows_Messaging();
    if(data.length){
        if(type == 'transaction'){
            for(let i=0;i<data.length;i++){
                let template_date = '';

                if(data[i].messaging_rooms && data[i].messaging_rooms.last_message_created_at){
                    // console.log(i, data[i].messaging_rooms.last_message);
                    let date;
                    if(typeof data[i].messaging_rooms.last_message_created_at === 'object' && data[i].messaging_rooms.last_message_created_at !== null){
                        date = moment(parseInt(data[i].messaging_rooms.last_message_created_at.$date.$numberLong));
                    }else{
                        date = moment(data[i].messaging_rooms.last_message_created_at);
                    }

                    if(data[i].messaging_rooms.last_message_created_at){
                        if(date.isSame(YESTERDAY, 'd')){
                            template_date += 'Kemarin ';
                        }else if(!date.isSame(TODAY, 'd')){
                            template_date += date.format('MMMM DD YYYY, ');
                        }
                        template_date += date.format('h:mm A');
                    }

                }

                let template = '';
                template =
                    '<div class="d-flex align-items-center justify-content-between mb-5">\
                        <div class="d-flex align-items-center">\
                            <div class="symbol symbol-circle symbol-50 mr-3">\
                                <span class="font-size-h3 symbol-label font-weight-boldest">\
                                '+(data[i].customer?data[i].customer.name.split(/\s/).reduce((response,word)=> response+=word.slice(0,1),''):'-')+'\
                                </span>\
                            </div>\
                            <div class="d-flex flex-column">\
                                <a  href="#" class="text-dark-75 text-hover-primary font-weight-bold font-size-lg" \
                                    onclick="displayRowDetail(this)" data-room_hash="'+(data[i].messaging_rooms?data[i].messaging_rooms.room_id:'')+'" \
                                    data-ref_id_hash="'+data[i].id+'" data-ref_type="transaction">\
                                '+(data[i].customer?data[i].customer.name:'-')+'<br>\
                                <span class="text-primary">'+(data[i].order_no?data[i].order_no:'-')+'</span>\
                                </a>\
                                <span class="text-muted font-weight-bold font-size-sm"><br>\
                                '+(data[i].messaging_rooms && data[i].messaging_rooms.last_message?(data[i].messaging_rooms.last_message.length > 50?(data[i].messaging_rooms.last_message.substring(0, 50))+'...':data[i].messaging_rooms.last_message):'-')+'\
                                </span>\
                            </div>\
                        </div>\
                        <div class="d-flex flex-column align-items-end">\
                            <span class="text-muted font-weight-bold font-size-sm">'+template_date+'</span><br>\
                            '+(data[i].messaging_rooms?data[i].messaging_rooms.is_read_by_vendor == false ?'<span class="label label-sm label-warning" id="unread_'+data[i].messaging_rooms.room_id+'"></span>':'':'')+'\
                        </div>\
                    </div>';
                $('#complaint_rows_more').append(template);
            }
        }else{
            for(let i=0;i<data.length;i++){
                let template_date = '';

                if(data[i].last_message_created_at){
                    let date;
                    if(typeof data[i].last_message_created_at === 'object' && data[i].last_message_created_at !== null){
                        date = moment(parseInt(data[i].last_message_created_at.$date.$numberLong));
                    }else{
                        date = moment(data[i].last_message_created_at);
                    }

                    if(date.isSame(YESTERDAY, 'd')){
                        template_date += 'Kemarin ';
                    }else if(!date.isSame(TODAY, 'd')){
                        template_date += date.format('MMMM DD YYYY, ');
                    }
                    template_date += date.format('h:mm A');
                }

                let template = ''; let template_info = '';
                if(!data[i].customer){
                    template_info = "title='OOPS! Data pelanggan tidak valid'";
                }
                template =
                    '<div class="d-flex align-items-center justify-content-between mb-5" '+template_info+'>\
                        <div class="d-flex align-items-center">\
                            <div class="symbol symbol-circle symbol-50 mr-3">\
                                <span class="font-size-h3 symbol-label font-weight-boldest">\
                                '+(data[i].customer?data[i].customer.name.split(/\s/).reduce((response,word)=> response+=word.slice(0,1),''):'-')+'\
                                </span>\
                            </div>\
                            <div class="d-flex flex-column">\
                                <a  href="#" class="text-dark-75 text-hover-primary font-weight-bold font-size-lg" \
                                    onclick="displayRowDetail(this)" data-room_hash="'+(data[i].room_id??'')+'"\
                                    data-ref_id_hash="'+(data[i].customer?data[i].customer.user_id:'')+'" data-ref_type="customer">\
                                '+(data[i].customer?data[i].customer.name:'-')+'<br>\
                                </a>\
                                <span class="text-muted font-weight-bold font-size-sm">\
                                '+(data[i].last_message?(data[i].last_message.length > 50?(data[i].last_message.substring(0, 50))+'...':data[i].last_message):'-')+'\
                                </span>\
                            </div>\
                        </div>\
                        <div class="d-flex flex-column align-items-end">\
                            <span class="text-muted font-weight-bold font-size-sm">'+template_date+'</span><br>\
                            '+(data[i].is_read_by_vendor == false ?'<span class="label label-sm label-warning" id="unread_'+(data[i].room_id??'')+'"></span>':'')+'\
                        </div>\
                    </div>';
                $('#complaint_rows_more').append(template);
            }
        }
    }else{
        $('#complaint_rows_more').append('<center class="text-muted"><small>Tidak ada pesan</small></center>');
    }
    $('#complaint_rows_more_loading').hide();
    $('#complaint_rows_more_loading_container').removeClass('overlay-block');
}

function treatMsgRight(str){
    // if contain link
    str = str.replace(/(www\..+?)(\s|$)/g, function(text, link) {
        return '<a href="http://'+ link +'" target="_blank">'+ link +'</a>';
    });

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

function setAsRead(room){
    $.ajax({
        url: HOST_URL +'/messaging/api/set_as_read',
        headers: {
            'x-csrf-token': $('meta[name="csrf-token"]').attr('content'),
        },
        type: "POST",
        data:{
            room: room
        },
        success:function(data){
            if(data.status){
                console.log('set as read');
                $('#unread_'+room).remove();
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

    $('#complaint_row_detail_card').addClass('card-custom wave wave-animate wave-primary');
    $('#complaint_row_detail_more').html('<center class="text-muted"><small>Pilih pesan untuk menampilkan detail percakapan</small></center>');
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

    $('#complaint_row_detail_card').removeClass('card-custom wave wave-animate wave-primary');

    let ref_type, ref_id_hash, room_hash;
    if(indirect){
        ref_type = null;
        ref_id_hash = null;
        room_hash = $('[name="room_hash_get_url"]').val();
    }else{
        ref_type = $(el).data('ref_type');
        ref_id_hash = $(el).data('ref_id_hash');
        room_hash = $(el).data('room_hash');
    }

    $.ajax({
        url: HOST_URL +'/messaging/api/get_list_message',
        headers: {
            'x-csrf-token': $('meta[name="csrf-token"]').attr('content'),
        },
        type: "POST",
        data:{
            ref_type: ref_type,
            ref_id_hash: ref_id_hash,
            room_hash: room_hash
        },
        success:function(data){
            console.log('displayRowDetail',ref_type,ref_id_hash,data.detail);
            if(!data.detail.parent){
                $('#complaint_row_detail_more_loading').hide();
                $('#complaint_row_detail_more_loading_container').removeClass('overlay-block');
                $('input[name="ref_id_hash"]').val(ref_id_hash);
                $('input[name="room_hash"]').val(room_hash);

                $('#complaint_row_detail_more').html('<center class="text-muted"><small>Masih belum ada percakapan. <br>Mulai?</small></center>');

            }else if(data.status){
                let template_customer_name  = data.detail.parent.customer.name;
                let template_vendor_name    = data.detail.parent.vendor.name;
                let template_customer_pp    =  '<span class="font-size-h3 symbol-label font-weight-boldest">\
                                                '+(data.detail.parent.customer?data.detail.parent.customer.name.split(/\s/).reduce((response,word)=> response+=word.slice(0,1),''):'-')+'\
                                                </span>';
                let template_vendor_pp      = '<img alt="profile-picture" src="'+IMG_URL+data.detail.parent.vendor.avatar_img+'">';

                $('#complaint_row_detail_more').html('');
                $('#complaint_detail_cust_name').html(data.detail.parent.customer.school.school_name+'<br>'+template_customer_name);
                $('input[name="vendor_name"]').val(template_vendor_name);
                $('input[name="vendor_pp_src"]').val(IMG_URL+data.detail.parent.vendor.avatar_img);

                if(data.detail.child && data.detail.child.length > 0){

                    let date;
                    let template = ''; let template_date = ''; let template_img = '';
                    let last_speaker = ''; let same_person = 0; let img_src = '';

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
                                                    <img alt="upload-picture" src="'+img_src+'" class="mb-10" style="max-width:370px">\
                                                </a><br>';
                        }else{
                            template_img = '';
                        }

                        same_person = (last_speaker == data.detail.child[i].from_type) ? 1 : 0;

                        if(data.detail.child[i].from_type == 'MEMBER'){
                            template = '<div class="d-flex flex-column mb-5 align-items-start">';
                            if(!same_person){
                                template += '    <div class="d-flex align-items-center">\
                                                    <div class="symbol symbol-circle symbol-40 mr-3">'+template_customer_pp+'</div>\
                                                    <div>\
                                                        <a href="#" class="text-dark-75 text-hover-primary font-weight-bold font-size-h6">'+template_customer_name+'</a>\
                                                    </div>\
                                                </div>';
                            }
                            template += '    <div class="mt-2 rounded p-5 bg-light-success text-dark-50 font-weight-bold font-size-lg text-left max-w-400px treat-msg">';
                            template +=         template_img+treatMsgRight(data.detail.child[i].message)+'\
                                            </div>\
                                            <span class="text-muted font-size-sm mt-1 float-right">'+template_date+'</span>\
                                        </div>';
                        }else if(data.detail.child[i].from_type == 'VENDOR'){
                            template = '<div class="d-flex flex-column mb-5 align-items-end">';
                            if(!same_person){
                                template += '    <div class="d-flex align-items-center">\
                                                    <div>\
                                                        <a href="#" class="text-dark-75 text-hover-primary font-weight-bold font-size-h6">'+template_vendor_name+'</a>\
                                                    </div>\
                                                    <div class="symbol symbol-circle symbol-40 ml-3">'+template_vendor_pp+'</div>\
                                                </div>';
                            }
                            template += '    <div class="mt-2 rounded p-5 bg-light-primary text-dark-50 font-weight-bold font-size-lg text-right max-w-400px treat-msg">';
                            template +=        template_img+treatMsgRight(data.detail.child[i].message)+'\
                                            </div>\
                                            <span class="text-muted font-size-sm mt-1 float-right">'+template_date+'</span>\
                                        </div>';
                        }

                        $('#complaint_row_detail_more').append(template);

                        last_speaker = data.detail.child[i].from_type;
                    }

                }else{}
                $('#complaint_row_detail_more_loading').hide();
                $('#complaint_row_detail_more_loading_container').removeClass('overlay-block');
                $('input[name="ref_id_hash"]').val(ref_id_hash);
                $('input[name="room_hash"]').val(room_hash);
                setAsRead(room_hash);

                // scroll
                var element = document.getElementById('complaint_row_detail_more_loading_container');
                var messagesEl = KTUtil.find(element, '.messages');
                var scrollEl = KTUtil.find(element, '.scroll');
                scrollEl.scrollTop = parseInt(KTUtil.css(messagesEl, 'height'));

            }else{
                $('#complaint_row_detail_more_loading').hide();
                $('#complaint_row_detail_more_loading_container').removeClass('overlay-block');
                $('input[name="ref_id_hash"]').val('');
                $('input[name="room_hash"]').val('');

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

            scrollToBottom();
            // console.log('into the bottom');
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
                        <span class="text-muted font-size-sm mt-1 float-right">'+(moment().format('h:mm A'))+'</span>\
                    </div>';
    $('#complaint_row_detail_more').append(template);

    scrollToBottom();

    $('#addForm')[0].reset();
    Dropzone.forElement('#kt_inbox_compose_attachments').removeAllFiles(true);
}

function sendMessage(){
    if(dataListUpload.files.length<=0){
        let ref_type = $('input[name="search_type"]:checked').data('type');
        let ref_id_hash = $('input[name="ref_id_hash"]').val();
        let room_hash = $('input[name="room_hash"]').val();
        console.log('send w/ hash', ref_id_hash);

        if($("#addForm")[0].checkValidity()) {

            $(".button-action").html("Loading...");
            $(".button-action").prop('disabled', true);


            $("#addForm").ajaxSubmit({
                url: HOST_URL +'/messaging/api/sent_message',
                headers: {
                    'x-csrf-token': $('meta[name="csrf-token"]').attr('content'),
                },
                type: "POST",
                data:{
                    ref_type: ref_type,
                    ref_id_hash: ref_id_hash,
                    room_hash: room_hash
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
}

// begin::Submit Form
$(".button-send").click(function(){
    sendMessage();
})

$(".button-process-complaint").click(function(){
    let ref_id_hash = $('input[name="ref_id_hash"]').val();
    let status = $(this).data('status');

    $.ajax({
        url: HOST_URL +'/messaging/api/confirm_action',
        headers: {
            'x-csrf-token': $('meta[name="csrf-token"]').attr('content'),
        },
        type: "POST",
        data:{
            status: status,
            ref_id_hash: ref_id_hash
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
                displayRowDetail_Blank()
                reloadMessagingList();
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

$('.download-script').click(function(){
    let room_hash = $('input[name="room_hash"]').val();
    window.open(HOST_URL + '/messaging/download/' + room_hash);
});

$('[name="search_type"]').click(function(){
    reloadMessagingList();
})
// end::Submit Form

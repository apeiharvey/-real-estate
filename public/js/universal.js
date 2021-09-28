var initial = 0;
var REFERENCE = moment();
var TODAY = REFERENCE.clone().startOf('day');
var YESTERDAY = REFERENCE.clone().subtract(1, 'days').startOf('day');

function createDate_String(date){
    let template_date = '';
    if(date.isSame(YESTERDAY, 'd')){
        template_date += 'Kemarin ';
    }else if(!date.isSame(TODAY, 'd')){
        template_date += date.format('MMMM DD YYYY, ');
    }
    template_date += '<br>'+date.format('h:mm A');
    return template_date;
}

$(".display-notif").click(function(){
    console.log('....load notif');
    if(initial == 0){
        initial = 1;
        let user = $(this).data('user_id');
        let vendor = $(this).data('vendor_id');

        $.ajax({
            url: HOST_URL +'/notification/api/get',
            headers: {
                'x-csrf-token': $('meta[name="csrf-token"]').attr('content'),
            },
            type: "POST",
            data:{
                user: user,
                vendor: vendor
            },
            success:function(data){
                console.log(data.detail);
                if(data.status){
                    console.log('notif retrieved [v]');
                    let template = '', template_arr = {0:'',1:'',2:'',3:'',4:''}, template_date = '', empty_flag = 1, date;
                    let order_index = {
                        'CREATED':'PESANAN BARU',
                        'ORDER_CONFIRM':'PESANAN DIKONFIRMASI',
                        'ORDER_REJECTED':'PESANAN DITOLAK',
                        'BUYER_REJECT':'PESANAN DITOLAK PELANGGAN',
                        'BUYER_APPROVED':'PESANAN DISETUJUI',
                        'ORDER_PROCESSED':'PESANAN DIPROSES',
                        'ORDER_SHIPPED':'PESANAN DIKIRIM',
                        'ORDER_DELIVERED':'PESANAN TELAH DIKIRIM',
                        'DELIVERED':'PESANAN TELAH DIKIRIM',
                        'ORDER_RECEIVED':'PESANAN DITERIMA',
                        'BAST_CREATED':'MENUNGGU KONFIRMASI BAST',
                        'BAST_SUBMITTED':'MENUNGGU PEMBAYARAN',
                        'BAST_REJECTED':'BAST DITOLAK',
                        'PAYMENT_PROCESSED':'PEMBAYARAN DIPROSES',
                        'PAYMENT_CONFIRMED':'PEMBAYARAN TELAH DIKONFIRMASI',
                        'PAYMENT_RECEIVED':'PEMBAYARAN DITERIMA',
                        'COMPLETED':'SELESAI',
                        'TESTIMONY_SUBMITTED':'PESANAN TELAH DIULAS',
                        'AGREEMENT_UPDATED':'PERSETUJUAN DIPERBAHARUI',
                        'CANCELED':'DIBATALKAN',
                        'EXPIRED':'KADALUARSA'
                    };
                    // --------------------------------------------------------ORDER::start
                    if(data.detail.order.length){
                        empty_flag = 0;
                        (data.detail.order).forEach(function(order_val, order_idx){
                            // hijau
                            if(jQuery.inArray(order_val.status, ['CREATED', 'ORDER_CONFIRM']) !== -1){ 
                                template_arr[0] += '<div class="d-flex align-items-center bg-light-success rounded p-1 mb-5">\
                                                        <span class="svg-icon svg-icon-success mr-5">\
                                                            <span class="svg-icon svg-icon-lg">\
                                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">\
                                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">\
                                                                        <rect x="0" y="0" width="24" height="24" />\
                                                                        <path d="M18.1446364,11.84388 L17.4471627,16.0287218 C17.4463569,16.0335568 17.4455155,16.0383857 17.4446387,16.0432083 C17.345843,16.5865846 16.8252597,16.9469884 16.2818833,16.8481927 L4.91303792,14.7811299 C4.53842737,14.7130189 4.23500006,14.4380834 4.13039941,14.0719812 L2.30560137,7.68518803 C2.28007524,7.59584656 2.26712532,7.50338343 2.26712532,7.4104669 C2.26712532,6.85818215 2.71484057,6.4104669 3.26712532,6.4104669 L16.9929851,6.4104669 L17.606173,3.78251876 C17.7307772,3.24850086 18.2068633,2.87071314 18.7552257,2.87071314 L20.8200821,2.87071314 C21.4717328,2.87071314 22,3.39898039 22,4.05063106 C22,4.70228173 21.4717328,5.23054898 20.8200821,5.23054898 L19.6915238,5.23054898 L18.1446364,11.84388 Z" fill="#000000" opacity="0.3"/>\
                                                                        <path d="M6.5,21 C5.67157288,21 5,20.3284271 5,19.5 C5,18.6715729 5.67157288,18 6.5,18 C7.32842712,18 8,18.6715729 8,19.5 C8,20.3284271 7.32842712,21 6.5,21 Z M15.5,21 C14.6715729,21 14,20.3284271 14,19.5 C14,18.6715729 14.6715729,18 15.5,18 C16.3284271,18 17,18.6715729 17,19.5 C17,20.3284271 16.3284271,21 15.5,21 Z" fill="#000000"/>\
                                                                    </g>\
                                                                </svg>\
                                                            </span>\
                                                        </span>\
                                                        <div class="d-flex flex-column flex-grow-1 mr-2">\
                                                            <a href="#" class="font-weight-normal text-dark-75 text-hover-primary font-size-md mb-1">'+order_index[order_val.status].toLowerCase()+'</a>\
                                                        </div>\
                                                        <span class="font-weight-bolder text-success py-1 font-size-lg">'+order_val.count+'</span>\
                                                    </div>';
                            }
                            // kuning
                            else if(jQuery.inArray(order_val.status, ['CANCELLATION_PROPOSED','CANCELLATION_APPROVED','CANCELLATION_REJECTED']) !== -1){
                                template_arr[1] += '<div class="d-flex align-items-center bg-light-warning rounded p-1 mb-5">\
                                                        <span class="svg-icon svg-icon-warning mr-5">\
                                                            <span class="svg-icon svg-icon-lg">\
                                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">\
                                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">\</g>\
                                                                </svg>\
                                                            </span>\
                                                        </span>\
                                                        <div class="d-flex flex-column flex-grow-1 mr-2">\
                                                            <a href="#" class="font-weight-normal text-dark-75 text-hover-primary font-size-md mb-1">'+order_index[order_val.status].toLowerCase()+'</a>\
                                                        </div>\
                                                        <span class="font-weight-bolder text-warning py-1 font-size-lg">'+order_val.count+'</span>\
                                                    </div>';
                            }
                            // ungu
                            else if(jQuery.inArray(order_val.status, ['ORDER_RECEIVED','BAST_CREATED','BAST_SUBMITTED','PAYMENT_PROCESSED','PAYMENT_CONFIRMED', 'TESTIMONY_SUBMITTED']) !== -1){
                                template_arr[2] += ' <div class="d-flex align-items-center bg-light-info rounded p-1 mb-5">\
                                                        <span class="svg-icon svg-icon-info mr-5">\
                                                            <span class="svg-icon svg-icon-lg">\
                                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">\
                                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">\
                                                                        <rect x="0" y="0" width="24" height="24" />\
                                                                        <path d="M4,7 L20,7 L20,19.5 C20,20.3284271 19.3284271,21 18.5,21 L5.5,21 C4.67157288,21 4,20.3284271 4,19.5 L4,7 Z M10,10 C9.44771525,10 9,10.4477153 9,11 C9,11.5522847 9.44771525,12 10,12 L14,12 C14.5522847,12 15,11.5522847 15,11 C15,10.4477153 14.5522847,10 14,10 L10,10 Z" fill="#000000"/>\
                                                                        <rect fill="#000000" opacity="0.3" x="2" y="3" width="20" height="4" rx="1"/>\
                                                                    </g>\
                                                                </svg>\
                                                            </span>\
                                                        </span>\
                                                        <div class="d-flex flex-column flex-grow-1 mr-2">\
                                                            <a href="#" class="font-weight-normal text-dark-75 text-hover-primary font-size-md mb-1">'+order_index[order_val.status].toLowerCase()+'</a>\
                                                        </div>\
                                                        <span class="font-weight-bolder text-info py-1 font-size-lg">'+order_val.count+'</span>\
                                                    </div>';
                            }
                            // dark
                            else if(jQuery.inArray(order_val.status, ['BUYER_APPROVED','ORDER_DELIVERED','PAYMENT_RECEIVED','DELIVERED','ORDER_SHIPPED','ORDER_PROCESSED']) !== -1){
                                template_arr[3] += ' <div class="d-flex align-items-center bg-dark rounded p-1 mb-5">\
                                                        <span class="svg-icon svg-icon-white mr-5">\
                                                            <span class="svg-icon svg-icon-lg">\
                                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">\
                                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">\
                                                                        <rect x="0" y="0" width="24" height="24" />\
                                                                        <path d="M8,3 L8,3.5 C8,4.32842712 8.67157288,5 9.5,5 L14.5,5 C15.3284271,5 16,4.32842712 16,3.5 L16,3 L18,3 C19.1045695,3 20,3.8954305 20,5 L20,21 C20,22.1045695 19.1045695,23 18,23 L6,23 C4.8954305,23 4,22.1045695 4,21 L4,5 C4,3.8954305 4.8954305,3 6,3 L8,3 Z" fill="#000000" opacity="0.3"/>\
                                                                        <path d="M10.875,15.75 C10.6354167,15.75 10.3958333,15.6541667 10.2041667,15.4625 L8.2875,13.5458333 C7.90416667,13.1625 7.90416667,12.5875 8.2875,12.2041667 C8.67083333,11.8208333 9.29375,11.8208333 9.62916667,12.2041667 L10.875,13.45 L14.0375,10.2875 C14.4208333,9.90416667 14.9958333,9.90416667 15.3791667,10.2875 C15.7625,10.6708333 15.7625,11.2458333 15.3791667,11.6291667 L11.5458333,15.4625 C11.3541667,15.6541667 11.1145833,15.75 10.875,15.75 Z" fill="#000000"/>\
                                                                        <path d="M11,2 C11,1.44771525 11.4477153,1 12,1 C12.5522847,1 13,1.44771525 13,2 L14.5,2 C14.7761424,2 15,2.22385763 15,2.5 L15,3.5 C15,3.77614237 14.7761424,4 14.5,4 L9.5,4 C9.22385763,4 9,3.77614237 9,3.5 L9,2.5 C9,2.22385763 9.22385763,2 9.5,2 L11,2 Z" fill="#000000"/>\
                                                                    </g>\
                                                                </svg>\
                                                            </span>\
                                                        </span>\
                                                        <div class="d-flex flex-column flex-grow-1 mr-2">\
                                                            <a href="#" class="font-weight-normal text-white-75 text-hover-primary font-size-md mb-1">'+order_index[order_val.status].toLowerCase()+'</a>\
                                                        </div>\
                                                        <span class="font-weight-bolder text-white py-1 font-size-lg">'+order_val.count+'</span>\
                                                    </div>';
                            }
                            // merah
                            else if(jQuery.inArray(order_val.status, ['EXPIRED', 'CANCELED', 'BAST_REJECTED', 'BUYER_REJECT', 'ORDER_REJECTED']) !== -1){
                                template_arr[4] += '<div class="d-flex align-items-center bg-light-danger rounded p-1 mb-5">\
                                                        <span class="svg-icon svg-icon-danger mr-5">\
                                                            <span class="svg-icon svg-icon-lg">\
                                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">\
                                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">\
                                                                        <rect x="0" y="0" width="24" height="24" />\
                                                                        <path d="M5,3 L6,3 C6.55228475,3 7,3.44771525 7,4 L7,20 C7,20.5522847 6.55228475,21 6,21 L5,21 C4.44771525,21 4,20.5522847 4,20 L4,4 C4,3.44771525 4.44771525,3 5,3 Z M10,3 L11,3 C11.5522847,3 12,3.44771525 12,4 L12,20 C12,20.5522847 11.5522847,21 11,21 L10,21 C9.44771525,21 9,20.5522847 9,20 L9,4 C9,3.44771525 9.44771525,3 10,3 Z" fill="#000000" />\
                                                                        <rect fill="#000000" opacity="0.3" transform="translate(17.825568, 11.945519) rotate(-19.000000) translate(-17.825568, -11.945519)" x="16.3255682" y="2.94551858" width="3" height="18" rx="1" />\
                                                                    </g>\
                                                                </svg>\
                                                            </span>\
                                                        </span>\
                                                        <div class="d-flex flex-column flex-grow-1 mr-2">\
                                                            <a href="#" class="font-weight-normal text-dark-75 text-hover-primary font-size-md mb-1">'+order_index[order_val.status].toLowerCase()+'</a>\
                                                        </div>\
                                                        <span class="font-weight-bolder text-danger py-1 font-size-lg">'+order_val.count+'</span>\
                                                    </div>';
                            }
                            else{
                                console.log(order_val.status);
                            }
                        })
                    }
                    for(let i=0;i<5;i++){
                        $('#quick_panel_order_items').append(template_arr[i]);
                    }
                    if(empty_flag){
                        $('#quick_panel_order_items').append('<center class="text-muted"><small>Tidak ada status order yang perlu ditinjau</small></center>');
                    }
                    // --------------------------------------------------------ORDER::end
                    // --------------------------------------------------------COMPLAINT::start
                    let complain_index = ['open', 'resolved'];
                    complain_index.forEach(function(compin_val, compin_idx){
                        if(data.detail.complaint[compin_val].length){
                            empty_flag = 0;
                            $('#quick_panel_complaint_'+compin_val+'_title').show();
                            template = '';
                            console.log(data.detail.complaint[compin_val]);
                            for(let i=0;i<(data.detail.complaint[compin_val].length>5?5:data.detail.complaint[compin_val].length);i++){ // limit to up to 5
                                if(data.detail.complaint[compin_val][i].complaint_detail[0]){
                                    date = moment(data.detail.complaint[compin_val][i].complaint_detail[0].created_at);
                                }else{
                                    date = moment(data.detail.complaint[compin_val][i].created_at);
                                }
                                template_date = createDate_String(date);
                                template +='<div class="d-flex align-items-center justify-content-between mb-5">\
                                                <div class="d-flex align-items-center">\
                                                    <div class="symbol symbol-circle symbol-50 mr-3">\
                                                        <span class="font-size-h3 symbol-label">\
                                                        '+(data.detail.complaint[compin_val][i].customer?data.detail.complaint[compin_val][i].customer.name.split(/\s/).reduce((response,word)=> response+=word.slice(0,1),''):'...')+'\
                                                        </span>\
                                                    </div>\
                                                    <div class="d-flex flex-column flex-grow-1 mr-2">\
                                                        <a href="'+HOST_URL +'/complaint?ch='+data.detail.complaint[compin_val][i].complain_hash+'" class="text-dark-75 text-hover-primary font-size-lg mb-1">'
                                                            +(data.detail.complaint[compin_val][i].customer?data.detail.complaint[compin_val][i].customer.name:'...')
                                                            +'<br><span class="text-success">'+(data.detail.complaint[compin_val][i].order_detail?data.detail.complaint[compin_val][i].order_detail.order_no:'...')+'</span>\
                                                        </a>\
                                                        <span class="text-muted">'
                                                            +(data.detail.complaint[compin_val][i].complaint_detail[0] && data.detail.complaint[compin_val][i].complaint_detail[0].complain_message?(data.detail.complaint[compin_val][i].complaint_detail[0].complain_message.length > 50?(data.detail.complaint[compin_val][i].complaint_detail[0].complain_message.substring(0, 50))+'...':data.detail.complaint[compin_val][i].complaint_detail[0].complain_message):'-')+'\
                                                        </span>\
                                                    </div>\
                                                </div>\
                                                <div class="d-flex flex-column align-items-end">\
                                                    <span class="btn btn-sm btn-light my-lg-0 my-1 py-1 text-dark-50">'+template_date+'</span>\
                                                </div>\
                                            </div>';
                            }
                            if(data.detail.complaint[compin_val].length > 5){
                                template +='<br><b><a href="'+HOST_URL +'/complaint">tampilkan lebih banyak >></a></b>'; 
                            }
                            $('#quick_panel_complaint_'+compin_val+'_items').append(template);
                        }
                    });
                    if(empty_flag){
                        $('#quick_panel_complaint_'+complain_index[0]+'_items').append('<center class="text-muted"><small>Tidak ada pesan komplain baru</small></center>');
                    }
                    // --------------------------------------------------------COMPLAINT::end
                    // --------------------------------------------------------MESSAGING::start
                    let messaging_index = ['transaction', 'customer']; empty_flag = 1;
                    messaging_index.forEach(function(messin_val, messin_idx){
                        if(data.detail.messaging[messin_val].length){
                            empty_flag = 0;
                            $('#quick_panel_messaging_'+messin_val+'_title').show();
                            template = '';
                            for(let i=0;i<(data.detail.messaging[messin_val].length>5?5:data.detail.messaging[messin_val].length);i++){ // limit to up to 5
                                if(data.detail.messaging[messin_val][i].last_message_created_at){
                                    if(typeof data.detail.messaging[messin_val][i].last_message_created_at === 'object' && data.detail.messaging[messin_val][i].last_message_created_at !== null){
                                        date = moment(parseInt(data.detail.messaging[messin_val][i].last_message_created_at.$date.$numberLong));
                                    }else{
                                        date = moment(data.detail.messaging[messin_val][i].last_message_created_at);
                                    }
                                }else{
                                    date = moment(data.detail.messaging[messin_val][i].created_at);
                                }
                                template_date = createDate_String(date);
                                template +='<div class="d-flex align-items-center justify-content-between mb-5">\
                                                <div class="d-flex align-items-center">\
                                                    <div class="symbol symbol-circle symbol-50 mr-3">\
                                                        <span class="font-size-h3 symbol-label">\
                                                        '+(data.detail.messaging[messin_val][i].customer?data.detail.messaging[messin_val][i].customer.name.split(/\s/).reduce((response,word)=> response+=word.slice(0,1),''):'...')+'\
                                                        </span>\
                                                    </div>\
                                                    <div class="d-flex flex-column flex-grow-1 mr-2">\
                                                        <a href="'+HOST_URL +'/messaging?rh='+data.detail.messaging[messin_val][i].room_id+'" class="text-dark-75 text-hover-primary font-size-lg mb-1">'
                                                            +(data.detail.messaging[messin_val][i].customer?data.detail.messaging[messin_val][i].customer.name:'...')
                                                            +'<br><span class="text-success">'+(data.detail.messaging[messin_val][i].order_detail?data.detail.messaging[messin_val][i].order_detail.order_no:'...')+'</span>\
                                                        </a>\
                                                        <span class="text-muted">'
                                                            +(data.detail.messaging[messin_val][i].last_message??'-')+'\
                                                        </span>\
                                                    </div>\
                                                </div>\
                                                <div class="d-flex flex-column align-items-end">\
                                                    <span class="btn btn-sm btn-light my-lg-0 my-1 py-1 text-dark-50">'+template_date+'</span>\
                                                </div>\
                                            </div>';
                            }
                            if(data.detail.messaging[messin_val].length > 5){
                                template +='<br><b><a href="'+HOST_URL +'/messaging">tampilkan lebih banyak >></a></b>'; 
                            }
                            $('#quick_panel_messaging_'+messin_val+'_items').append(template);
                        }
                    });
                    if(empty_flag){
                        $('#quick_panel_messaging_'+messaging_index[0]+'_items').append('<center class="text-muted"><small>Tidak ada pesan baru</small></center>');
                    }
                    // --------------------------------------------------------MESSAGING::end
                    $('#kt_quick_panel_notif_loading').hide();
                    $('#kt_quick_panel_notif_loading_container').removeClass('overlay-block');
                    $('#kt_quick_panel_notifications_btn').trigger('click');;
                }else{
                    console.log('get notif failed [x]',data.message);
                }
            },
            error:function(xhr, status, error){
                console.log('get notif got error [x]',xhr.responseText);
            }
        });
    }
})
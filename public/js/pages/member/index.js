'use strict';

var KTDatatablesDataSourceAjaxClient = function() {
    var initTable1 = function() {
        var table = $('#kt_datatable');
        table.DataTable();
    }

    return {
        //main function to initiate the module
        init: function() {
            initTable1();
        },

    };
}();

jQuery(document).ready(function() {
    $(document).on("click",".action-delete", function(){
        let hash = $(this).data("hash");
        let title = $(this).data("title");
        Swal.fire({
            title: 'Are you sure to remove '+title+'?',
            text: "You won't be able to revert this!",
            showCancelButton: true,
            confirmButtonText: 'Yes, reject it!',
            confirmButtonClass: 'btn btn-danger',
            cancelButtonClass: 'btn btn-outline-primary ml-1',
            buttonsStyling: false,
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url:'/member/' + hash,
                    headers: {
                        'x-csrf-token': $('meta[name="csrf-token"]').attr('content'),
                    },
                    type:"DELETE",
                    success:(function(message){

                        try{
                            let response = JSON.parse(message);

                            if($.trim(response.result_status).toUpperCase()==="SUCCESS"){
                                Swal.fire(
                                    {
                                        type: "success",
                                        title: 'Deleted!',
                                        html: response.result_message,
                                        confirmButtonClass: 'btn btn-primary',
                                    }
                                ).then(function () {
                                    location.reload();
                                });
                            }else{
                                Swal.fire({
                                    title: "Opps.. Error!",
                                    html: response.result_message,
                                    type: "error",
                                    confirmButtonClass: 'btn btn-primary',
                                    buttonsStyling: false,
                                });
                            }
                        }catch (e) {
                            Swal.fire({
                                title: "Opps.. Error!",
                                html: message,
                                type: "error",
                                confirmButtonClass: 'btn btn-primary',
                                buttonsStyling: false,
                            });
                        }
                    }),
                    error:function(xhr,status,error) {
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
    })
    KTDatatablesDataSourceAjaxClient.init();
});

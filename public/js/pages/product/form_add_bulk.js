$('.download-upload-template').click(function(e) {
    document.getElementById('download-upload-template-link').click();
});

$('.guide-filling-template').click(function(e) {
    document.getElementById('guide-filling-template-link').click();
});

// begin::Upload Spreadsheet
    Dropzone.autoDiscover = false;
    var dataListUpload = new Dropzone('#dataListUpload', {
        paramName: "file", 
        url: HOST_URL + '/product/api/upload_bulk',
        headers: {
            'x-csrf-token': $('meta[name="csrf-token"]').attr('content'),
        },
        maxFiles: 1,
        maxFilesize: 5, // MB
        autoProcessQueue: false,
		parallelUploads: 20,
        thumbnailWidth: 300,
        thumbnailHeight: 100,
        addRemoveLinks: true,
        uploadMultiple: false,
        acceptedFiles: ".csv",
		dictRemoveFile: " Remove",
        dictCancelUpload: "Cancel",
        accept: function(file, done) {
			console.log("file uploaded");
            done();
        },
		init: function() {
			this.on("sending", function(file, xhr, formData) { 
				console.log('...sending');
    		});
			this.on("success", function(file, data){
				console.log('...success', data);
				$(".button-process").each(function(i,obj){
					$(obj).show();
				});    
		
				KTDatatableChildRemoteDataTable.reload();
				// console.log('...have i recreate the table ??');

				if(data.status){
					Dropzone.forElement('#dataListUpload').removeAllFiles(true);
					Swal.fire({
						title:"Success!",
						text: data.message,
						type: "success",
						buttonsStyling: false,
						confirmButtonClass: "btn btn-success"
					});
					return true;
				}else{
					Swal.fire({
						icon: 'warning',
						title: 'Oops...',
						html: data.message,
						type: "warning",
						buttonsStyling: false,
						confirmButtonClass: "btn btn-error"
					});
					return false;
				}
    		});
			this.on("error", function (file, errorMessage) {
				console.log('...error',file,errorMessage);
				$(".button-process").each(function(i,obj){
					$(obj).show();
				});
				Swal.fire({
					icon: 'error',
					title: 'Yaah...',
					text: errorMessage,
					type: "error",
					buttonsStyling: false,
					confirmButtonClass: "btn btn-error"
				});
			});
			this.on("complete", function(progress) {
				console.log('process is completed');
			});
		}
	});
// end::Upload Spreadsheet

// begin::Submit Form
    $(".button-process-upload").click(function(){
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

    
    $(".button-process-cancel").click(function(){
        // console.log("....");
        if(dataListUpload.files.length<=0){
            Swal.fire({
                title: 'Oops..',
                text: 'Tidak ada file yang diunggah',
                animation: false,
                customClass: 'animated shake',
                confirmButtonClass: 'btn btn-primary',
                buttonsStyling: false,
            });
            $('html, body').animate({scrollTop: '0px'}, 300);
            return;
        }

        Dropzone.forElement('#dataListUpload').removeAllFiles(true);
    })
// end::Submit Form

// begin::Datatables
	var KTDatatableChildRemoteDataTable = function() {

		var datatable_fx = function() {

			var datatable = $('#kt_datatable').KTDatatable({
				data: {
					type: 'remote',
					source: {
						read: {
							url: HOST_URL + '/product/api/get_bulk_history',
							type: 'POST',
							headers: {
								'x-csrf-token': $('meta[name="csrf-token"]').attr('content'),
							},
							data: {},
						},
					},
					pageSize: 10,
					serverPaging: true,
					serverFiltering: true,
					serverSorting: true,
				},
				layout: {
					scroll: false,
					footer: false,
				},
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
				columns: [
					{
						field: 'id',
						title: '',
						sortable: false,
						width: 30,
						textAlign: 'center',
					}, {
						field: 'original_file_name',
						title: 'Nama File',
						sortable: 'asc',
						width: 200,
						template: function(row) { // callback function support for column rendering
							return '<a href="'+IMG_URL+row.file_location+'" target="_blank" download="siplah_'+row.original_file_name+'">' + row.original_file_name + '</a>';
						}
					}, {
						field: 'created_at',
						title: 'Waktu Unggah',
						sortable: 'asc',
						width: 200,
						template: function(row) {
							return moment(row.created_at).format('YYYY-MM-DD HH:mm:ss')
						}
					}, {
						field: 'Status',
						title: 'Status',
						sortable: 'asc',
						template: function(row) { // callback function support for column rendering
							var status = {
								'error': {'title': 'ERROR', 'class': 'label-light-danger'},
								'new': {'title': 'SEDANG DIPROSES', 'class': 'label-light-warning'},
								'done': {'title': 'SELESAI', 'class': ' label-light-success'},
							};
							return '<span class="label ' + (status[row.status]?status[row.status].class:'') + 
									' label-inline font-weight-bold label-lg">' + (status[row.status]?status[row.status].title:'') + '</span>';
						}
					}, {
						field: 'success_count',
						title: 'Jumlah Berhasil',
						sortable: 'asc',
						width: 100,
					}, {
						field: 'fail_count',
						title: 'Jumlah Gagal',
						sortable: 'asc',
						width: 100,
					},
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
						type: 'remote',
						source: {
							read: {
								url: HOST_URL + '/product/api/get_bulk_history_detail',
								type: 'POST',
								headers: {
									'x-csrf-token': $('meta[name="csrf-token"]').attr('content'),
								},
								data: {
									upload_id: e.data.id
								},
							},
						},
						pageSize: 10,
						serverPaging: true,
						serverFiltering: false,
						serverSorting: true,
					},
				layout: {
						scroll: false,
						footer: false,
						spinner: {
							type: 1,
							theme: 'default',
						},
					},
					sortable: true,
					columns: [
						// {
						// 	field: 'id',
						// 	title: 'ID',
						// 	sortable: false,
						// 	width: 30,
						// }, 
						{
							field: 'sku',
							title: 'SKU',
							width: 100
						}, {
							field: 'err_message',
							title: 'Catatan',
							width: 400,
							template: function(row) {
								if(row.err_message){
									return 	'<span class="text-danger">'+'gagal'+'</span>:\
											 <span class="text-muted ml-5" id="error-message-'+row.upload_id+'-'+row.id+'" data-content="'+row.err_message+'" data-state="min">'
											 	+(row.err_message.length>50?row.err_message.substring(0, 20)+'....':row.err_message)+'\
											</span>'
												+(row.err_message.length>50?('<a class="text-primary ml-5" id="error-read-more-'+row.upload_id+'-'+row.id+'" onclick="readMore_error('+row.upload_id+','+row.id+')">lihat lebih lengkap</a>'):'');
								}else{
									return '-';
								}
							}
						}],
				});
			}
		};

		return { // public functions
			init: function() { // init demo
				datatable_fx();
			},
			reload: function() {
				$('#kt_datatable').KTDatatable().reload();
			}
		};
	}();

	jQuery(document).ready(function() {
		KTDatatableChildRemoteDataTable.init();
	});
// end::Datatables

function readMore_error(upload_id, item_id){
	let state = $('#error-message-'+upload_id+'-'+item_id).data('state');
	let content = $('#error-message-'+upload_id+'-'+item_id).data('content');
	let str_action = 'persingkat';
	if(state == 'min'){
		state = 'max';
	}else if(state == 'max'){
		str_action = 'lihat lebih lengkap';
		content = content.substring(0, 20)+'....';
		state = 'min';
	} 
	$('#error-read-more-'+upload_id+'-'+item_id).html(str_action);
	$('#error-message-'+upload_id+'-'+item_id).html(content);
	$('#error-message-'+upload_id+'-'+item_id).data('state',state);

}

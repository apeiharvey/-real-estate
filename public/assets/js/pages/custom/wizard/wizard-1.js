"use strict";

// Class definition
var KTWizard1 = function () {
	// Base elements
	var _wizardEl;
	var _formEl;
	var _wizardObj;
	var _validations = [];
	var _enabled =  !document.getElementById("profile_avatar_edit");

	// Private functions
	var _initValidation = function () {
		// Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
		// Step 1
        var _step1 = FormValidation.formValidation(
            _formEl,
            {
                fields: {
                    company_name: {
                        validators: {
                            notEmpty: {
                                message: 'Nama Perusahaan tidak boleh kosong'
                            },
                            stringLength: {
                                max: 150,
                                message: 'Maksimal karakter 150'
                            }
                        }
                    },
                    business_type: {
                        validators: {
                            notEmpty: {
                                message: 'Jenis Usaha dibutuhkan'
                            },
                            stringLength: {
                                max: 10,
                                message: 'Maksimal karakter 10'
                            }
                        }
                    },
                    business_class: {
                        validators: {
                            notEmpty: {
                                message: 'Kelas Usaha dibutuhkan'
                            },
                            stringLength: {
                                max: 10,
                                message: 'Maksimal karakter 10'
                            }
                        }
                    },
                    legal_status: {
                        validators: {
                            notEmpty: {
                                message: 'Status dibutuhkan'
                            },
                            stringLength: {
                                max: 10,
                                message: 'Maksimal karakter 10'
                            }
                        }
                    },
                    npwp: {
                        validators: {
                            notEmpty: {
                                enabled: false,
                                message: 'NPWP tidak boleh kosong'
                            },
                            digits:{
                                message: 'NPWP hanya boleh angka'
                            },
                            stringLength: {
                                max: 15,
                                min: 15,
                                message: 'Harus 15 karakter'
                            }
                        }
                    },
                    siup_nib: {
                        validators: {
                            notEmpty: {
                                enabled: false,
                                message: 'SIUP/NIB tidak boleh kosong'
                            },
                            stringLength: {
                                max: 13,
                                min: 13,
                                message: 'Harus 13 karakter'
                            }
                        }
                    },
                    tdp: {
                        validators: {
                            notEmpty: {
                                enabled: false,
                                message: 'TDP tidak boleh kosong'
                            },
                            stringLength: {
                                max: 30,
                                message: 'Maksimal karakter 30'
                            }
                        }
                    },
                    address: {
                        validators: {
                            notEmpty: {
                                message: 'Address tidak boleh kosong'
                            },
                            stringLength: {
                                min: 10,
                                max: 250,
                                message: 'Minimal karakter 10 dan maksimal karakter 250'
                            }
                        }
                    },
                    geolocation: {
                        validators: {
                            notEmpty: {
                                message: 'Geolokasi belum dipilih'
                            }
                        }
                    },
                    region: {
                        validators: {
                            notEmpty: {
                                message: 'Wilayah dibutuhkan'
                            }
                        }
                    },
                    districts: {
                        validators: {
                            notEmpty: {
                                message: 'Kecamatan dibutuhkan'
                            }
                        }
                    },
                    'zip-code': {
                        validators: {
                            notEmpty: {
                                message: 'Kode Pos tidak boleh kosong'
                            },
                            digits:{
                                message: 'Kode Pos hanya boleh angka'
                            },
                            stringLength: {
                                max: 5,
                                min: 5,
                                message: 'Harus 5 digit angka'
                            }
                        }
                    },
                    profile_avatar: {
                        validators: {
                            notEmpty: {
                                enabled: _enabled,
                                message: 'Logo Usaha dibutuhkan'
                            },
                            file:{
                                extension: 'jpeg,jpg,png',
                                type: 'image/jpeg,image/png',
                                maxFiles: 1,
                                maxSize:3 * 1024 * 1024,
                                message: 'Logo Usaha hanya boleh jpeg, jpg, png dan ukuran file maksimum 3Mb',
                            }
                        }
                    },
                    email: {
                        validators: {
                            notEmpty: {
                                message: 'Email tidak boleh kosong'
                            },
                            emailAddress: {
                                message: 'Email tidak valid'
                            },
                        }
                    }
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    // Bootstrap Framework Integration
                    bootstrap: new FormValidation.plugins.Bootstrap({
                        //eleInvalidClass: '',
                        eleValidClass: '',
                    })
                }
            }
        );
		_validations.push(_step1);

		// Step 2
		_validations.push(FormValidation.formValidation(
			_formEl,
			{
				fields: {
					'pic-fullname': {
						validators: {
							notEmpty: {
								message: 'Nama Lengkap tidak boleh kosong'
							},
                            stringLength: {
                                max: 100,
                                message: 'Maksimal karakter 100'
                            }
						}
					},
					'pic-nik': {
						validators: {
							notEmpty: {
								message: 'Nama lengkap tidak boleh kosong'
							},
							digits: {
								message: 'NIK hanya boleh angka'
							},
                            stringLength: {
                                max: 30,
                                message: 'Maksimal karakter 30'
                            }
						}
					},
					'pic-title': {
						validators: {
							notEmpty: {
								message: 'Jabatan tidak boleh kosong'
							},
                            stringLength: {
                                max: 30,
                                message: 'Maksimal karakter 30'
                            }
						}
					},
                    'pic-email': {
						validators: {
                            notEmpty: {
                                message: 'Email tidak boleh kosong'
                            },
                            emailAddress: {
                                message: 'Email tidak valid'
                            },
                            stringLength: {
                                max: 50,
                                message: 'Maksimal karakter 50'
                            }
						}
					},
					'pic-phone': {
						validators: {
                            notEmpty: {
                                message: 'Nomor Ponsel tidak boleh kosong'
                            },
                            phone: {
                                country: 'IN',
								message: 'Nomor Ponsel tidak benar'
							},
                            stringLength: {
                                max: 50,
                                message: 'Maksimal karakter 50'
                            }
						}
					},
                    'pic-office-phone': {
                        validators: {
                            notEmpty: {
                                message: 'Nomor Telpon Kantor tidak boleh kosong'
                            },
                            phone: {
                                country: 'IN',
                                message: 'Nomor Telpon Kantor tidak benar'
                            },
                            stringLength: {
                                max: 50,
                                message: 'Maksimal karakter 50'
                            }
                        }
                    }
				},
				plugins: {
					trigger: new FormValidation.plugins.Trigger(),
					// Bootstrap Framework Integration
					bootstrap: new FormValidation.plugins.Bootstrap({
						//eleInvalidClass: '',
						eleValidClass: '',
					})
				}
			}
		));

		// Step 3
		_validations.push(FormValidation.formValidation(
			_formEl,
			{
				fields: {
					'bank-name': {
						validators: {
							notEmpty: {
								message: 'Nama Bank tidak boleh kosong'
							},
                            stringLength: {
                                max: 100,
                                message: 'Maksimal karakter 100'
                            }
						}
					},
					'bank-owner-name': {
						validators: {
							notEmpty: {
								message: 'Nama Pemilik Bank tidak boleh kosong'
							},
                            stringLength: {
                                max: 100,
                                message: 'Maksimal karakter 100'
                            }
						}
					},
					'bank-number': {
						validators: {
							notEmpty: {
								message: 'Nomor Rekening tidak boleh kosong'
							},
                            digits:{
                                message: 'Nomor Rekening hanya boleh angka'
                            },
                            stringLength: {
                                max: 30,
                                message: 'Maksimal karakter 30'
                            }
						}
					},
                    'bank-branch': {
                        validators: {
                            notEmpty: {
                                message: 'Kantor Cabang Bank tidak boleh kosong'
                            },
                            stringLength: {
                                max: 100,
                                message: 'Maksimal karakter 100'
                            }
                        }
                    }
				},
				plugins: {
					trigger: new FormValidation.plugins.Trigger(),
					// Bootstrap Framework Integration
					bootstrap: new FormValidation.plugins.Bootstrap({
						//eleInvalidClass: '',
						eleValidClass: '',
					})
				}
			}
		));

		// Step 4
        var _step4 = FormValidation.formValidation(
            _formEl,
            {
                fields: {
                    'ktp-file': {
                        validators: {
                            notEmpty: {
                                enabled: _enabled,
                                message: 'KTP dibutuhkan'
                            },
                            file:{
                                extension: 'jpeg,jpg,png,pdf',
                                type: 'image/jpeg,image/png,application/pdf',
                                maxFiles: 1,
                                maxSize:3 * 1024 * 1024,
                                message: 'KTP hanya boleh jpeg, jpg, png, pdf dan ukuran file maksimum 3Mb',
                            }
                        }
                    },
                    'siup-nib-file': {
                        validators: {
                            notEmpty: {
                                enabled: _enabled,
                                message: 'SIUP/NIB dibutuhkan'
                            },
                            file:{
                                extension: 'jpeg,jpg,png,pdf',
                                type: 'image/jpeg,image/png,application/pdf',
                                maxFiles: 1,
                                maxSize:3 * 1024 * 1024,
                                message: 'SIUP/NIB hanya boleh jpeg, jpg, png, pdf dan ukuran file maksimum 3Mb',
                            }
                        }
                    },
                    'npwp-file': {
                        validators: {
                            notEmpty: {
                                enabled: _enabled,
                                message: 'NPWP dibutuhkan'
                            },
                            file:{
                                extension: 'jpeg,jpg,png,pdf',
                                type: 'image/jpeg,image/png,application/pdf',
                                maxFiles: 1,
                                maxSize:3 * 1024 * 1024,
                                message: 'NPWP hanya boleh jpeg, jpg, png, pdf dan ukuran file maksimum 3Mb',
                            }
                        }
                    },
                    'tdp-file': {
                        validators: {
                            notEmpty: {
                                enabled: _enabled,
                                message: 'TDP dibutuhkan'
                            },
                            file:{
                                extension: 'jpeg,jpg,png,pdf',
                                type: 'image/jpeg,image/png,application/pdf',
                                maxFiles: 1,
                                maxSize:3 * 1024 * 1024,
                                message: 'TDP hanya boleh jpeg, jpg, png, pdf dan ukuran file maksimum 3Mb',
                            }
                        }
                    },
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    // Bootstrap Framework Integration
                    bootstrap: new FormValidation.plugins.Bootstrap({
                        //eleInvalidClass: '',
                        eleValidClass: '',
                    })
                }
            }
        )
		_validations.push(_step4);

        $(document).on("change","#legal_status_selection",function(){
            if($(this).val()==="PKP"){
                _step1.enableValidator('npwp').enableValidator('siup_nib').enableValidator('tdp');
                if(_enabled){
                    _step4.enableValidator('siup-nib-file').enableValidator('npwp-file').enableValidator('tdp-file');
                }
            }else{
                _step1.disableValidator('npwp').disableValidator('siup_nib').disableValidator('tdp');
                _step4.disableValidator('siup-nib-file').disableValidator('npwp-file').disableValidator('tdp-file');
            }
        });
	}

	var _initWizard = function () {
		// Initialize form wizard
		_wizardObj = new KTWizard(_wizardEl, {
			startStep: 1, // initial active step number
			clickableSteps: false  // allow step clicking
		});

		// Validation before going to next page
		_wizardObj.on('change', function (wizard) {
			if (wizard.getStep() > wizard.getNewStep()) {
				return; // Skip if stepped back
			}

			// Validate form before change wizard step
			var validator = _validations[wizard.getStep() - 1]; // get validator for currnt step

			if (validator) {
				validator.validate().then(function (status) {
					if (status == 'Valid') {
						wizard.goTo(wizard.getNewStep());

						KTUtil.scrollTop();
					} else {
						Swal.fire({
							text: "Maaf, sepertinya ada beberapa kesalahan yang terdeteksi, mohon diperbaiki dan harap dicoba lagi.",
							icon: "error",
							buttonsStyling: false,
							confirmButtonText: "Ok, got it!",
							customClass: {
								confirmButton: "btn font-weight-bold btn-light"
							}
						}).then(function () {
							KTUtil.scrollTop();
						});
					}
				});
			}

			return false;  // Do not change wizard step, further action will be handled by he validator
		});

		// Change event
		_wizardObj.on('changed', function (wizard) {
			KTUtil.scrollTop();
		});

		// Submit event
		_wizardObj.on('submit', function (wizard) {
			Swal.fire({
				text: "Semua data sudah OK! Harap konfirmasi sebelum disubmit.",
				icon: "success",
				showCancelButton: true,
				buttonsStyling: false,
				confirmButtonText: "Ya, submit!",
				cancelButtonText: "Tidak, batalkan",
				customClass: {
					confirmButton: "btn font-weight-bold btn-primary",
					cancelButton: "btn font-weight-bold btn-default"
				}
			}).then(function (result) {
				if (result.value) {
				    $("#action-loading").show();
					$("#kt_form").ajaxSubmit({
                        url:'submit',
                        headers: {
                            'x-csrf-token': $('meta[name="csrf-token"]').attr('content'),
                        },
                        enctype: 'multipart/form-data',
                        type: 'post',
                        success: function (response) {
                            try{
                                let data = JSON.parse(response);
                                if($.trim(data.status).toUpperCase()==="SUCCESS"){
                                    Swal.fire(
                                        {
                                            type: "success",
                                            title: data.title,
                                            html: data.message,
                                            confirmButtonClass: 'btn btn-primary',
                                        }
                                    ).then(function (result) {
                                        window.location.href = '/vendor'
                                    });
                                }else{
                                    Swal.fire({
                                        title: "Opps.. Error!",
                                        html: data.status,
                                        type: "error",
                                        confirmButtonClass: 'btn btn-primary',
                                        buttonsStyling: false,
                                    });
                                }
                            }catch (e) {
                                Swal.fire({
                                    title: "Opps.. Error!",
                                    html: response,
                                    type: "error",
                                    confirmButtonClass: 'btn btn-primary',
                                    buttonsStyling: false,
                                });
                            }
                            $("#action-loading").hide();
                        },
                        error: function (xhr, status, error) {
                            let response = JSON.parse(xhr.responseText);
                            let responseText =''; let responseText_child = '';
                            if('errors' in response){
                                responseText += '<div class="clearfix"><br><br>';
                                for (var key in response['errors']) {
                                    responseText_child = '';
                                    for (var key2 in response['errors'][key]) {
                                        responseText_child += '<li>'+response['errors'][key][key2]+'</li>';
                                    }
                                    responseText += '<b>'+key+'<ul></b>'+responseText_child+'</ul>';
                                }
                                responseText += '</div>';
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                html: responseText,
                                type: "error",
                                buttonsStyling: false,
                                confirmButtonClass: "btn btn-error"
                            });
                            $("#action-loading").hide();
                        }
                    });

				} else if (result.dismiss === 'cancel') {
					Swal.fire({
						text: "Formulir Anda belum disubmit!.",
						icon: "error",
						buttonsStyling: false,
						confirmButtonText: "OK, saya mengerti!",
						customClass: {
							confirmButton: "btn font-weight-bold btn-primary",
						}
					});
				}
			});
		});
	}

	return {
		// public functions
		init: function () {
			_wizardEl = KTUtil.getById('kt_wizard');
			_formEl = KTUtil.getById('kt_form');

			_initValidation();
			_initWizard();
		}
	};
}();

jQuery(document).ready(function () {
	KTWizard1.init();
	$("#action-loading").hide();
});

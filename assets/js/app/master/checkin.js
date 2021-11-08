/**
 * Javascript Checkin
 *
 * @author Andy Aliansah <andyaliansah97@gmail.com>
 *
 */
window.MASTER = (function ($) {

	var renderCheckbox = function (data, type, full, meta) {
		return '<input class="check-sub-master" type="checkbox" value="' + full['id'] + '">';
	}

	var renderEdit = function (data, type, full, meta) {
		var url = APP.siteUrl + 'adm/checkin/edit/' + full['id'];

		var button = '';
		button = '<a class="master-edit" href="' + url + '" data-toggle="tooltip" data-placement="right" title="Click For Edit">' + data + '</a>';
		
		if(full['total_paid'] != 0){
			button = data;
		}
		return button;
	}

	var renderDuplicate = function (data, type, full, meta) {
		return '<a href="#" class="master-duplicate text-warning" data-id="' + full['id'] + '"  data-toggle="tooltip" data-placement="right" title="Tekan untuk Duplikasi"><i class="far fa-clone"></i></i></a>';
	}

	var renderCheckOut = function (data, type, full, meta) {
		var button = '';
		button = '<a href="#" class="master-checkout text-danger ' +full['bgcolor']+'" data-id="' + full['id'] + '"  data-toggle="tooltip" data-placement="right" title="Tekan untuk proses Check-Out"><i class="fas fa-sign-out-alt"></i></i></a>';
		
		if(!full['room']){
			button = '<i class="fas fa-exclamation-circle text-warning" data-toggle="tooltip" data-placement="right" title="Data belum lengkap"></i>';
		}

		return button;
	}

	return {

		elDatatable: null,
		elVue: '#vue-master',
		elForm: '.master-form',
		elEdit: '.master-edit',
		elTable: '#master-table',
		elBtnDuplicate: '.master-duplicate',
		elBtnCheckout: '.master-checkout',
		elModal: '.master-modal',
		elBtnDelete: '.master-delete',
		elModalClose: '.master-cancel',
		elSubCheckbox: '.check-sub-master',
		elParentCheckbox: '.check-all-master',
		elModalContent: '.master-modal-content',
		urlDeleteData: window.APP.siteUrl + 'adm/checkin/delete',
		urlRequestData: window.APP.siteUrl + 'adm/checkin/get_data',

		urlBahasa: window.APP.baseUrl + 'assets/js/vendor/indonesia.json',

		init: function () {
			var parentThis = this;
		},

		// Master
		handleVueMaster: function () {
			var parentThis = this;

			// Vue Js
			new Vue({
				el: parentThis.elVue,
				delimiters: ['<%', '%>'],
				// data: {
				// 	asuuu: ''
				// },
				methods: {
					// 
				},
				mounted: function () {
					parentThis.handleDataTable();
					// const queryString = window.location.pathname.split("/");;
					// console.log(queryString[6]);
					// console.log(vue.asuuu);
				}
			});

		},

		// Checkin : handleDataTable
		handleDataTable: function () {
			var parentThis = this;

			// Datatable
			parentThis.elDatatable = $(parentThis.elTable).DataTable({
				ajax: {
					url: parentThis.urlRequestData
				},
				columns: [{
						data: 'no',
						width:'20',
						className: 'fit-width',
						render: renderCheckbox
					},
					{
						render: renderDuplicate,
						className: 'fit-width text-center',
					},
					{
						data: 'no',
						width:'20',
						className: 'fit-width text-center',
						render: renderCheckOut,
					},
					{
						data: 'number',
						className: 'fit-width',
						render: renderEdit,
					},
					{
						data: 'guest_name',
						className: 'fit-width',
					},
					{
						data: 'room',
						className: 'fit-width',
					},
					{
						data: 'date_in',
						className: 'fit-width',
					},
					{
						data: 'date_out',
						className: 'fit-width',
					},
					{
						data: 'room_type',
						className: 'fit-width',
					},
					{
						data: 'guest_group_name',
						className: 'fit-width',
					},
					{
						data: 'guest_phone',
						className: 'fit-width',
					},
					{
						data: 'date',
						className: 'fit-width',
					},
					
				],

				order: [],
				deferRender: true,
				scrollX: true,
				"columnDefs": [{
					"targets": [0, 1, 2],
					"orderable": false,
				}],
				"language": {
					"url": parentThis.urlBahasa,
					"sEmptyTable": "Tidads"
				},

				initComplete: function () {

					// handle form
					window.FORM.handleEditModal(
						parentThis.elForm,
						parentThis.elEdit,
						parentThis.elModal,
						parentThis.elModalContent,
						parentThis.elModalClose,
						parentThis.elDatatable
					);
					
					var b_id = $('#b_id').val();
					if(b_id != ''){
						document.getElementById("master-edit-process").click();
					}
					
					parentThis.handleDelete();
					parentThis.handleDuplicate();
					parentThis.handleCheckout();
					window.INPUT.handleCheckboxAll(parentThis.elParentCheckbox, parentThis.elSubCheckbox);
				}

			});
		},

		// Checkin : handleDelete
		handleDelete: function () {
			var parentThis = this;

			$(parentThis.elBtnDelete).click(function () {

				var Items = $(parentThis.elTable).find('input[class="check-sub-master"]:checked');

				var types = [];
				for (var i = 0; i < Items.length; i++) {
					types.push($(Items[i]).val());
				}

				if (!types.length) {

					toastr.warning('Silahkan pilih data yang akan dihapus terlebih dahulu!')

					return false;

				} else {

					Swal.fire({
						title: 'Anda yakin?',
						text: "Ingin menghapus data ini?",
						type: 'warning',
						showCancelButton: true,
						confirmButtonColor: '#3085d6',
						cancelButtonColor: '#d33',
						confirmButtonText: 'Ya, Hapus!',
						cancelButtonText: 'Batal'
					}).then((result) => {
						if (result.value) {
							$.ajax({
								type: "POST",
								dataType: 'json',
								url: parentThis.urlDeleteData,
								data: {
									id: types,
								},
								success: function (response) {
									window.FORM.showNotification(response.message, response.status);
									parentThis.elDatatable.ajax.reload();
								}
							});
						}
					})


				}
			});
		},

		handleDuplicate: function () {
			var parentThis = this;
			
			$(parentThis.elTable).on("click", parentThis.elBtnDuplicate, function () {
				var id = $(this).attr('data-id');
				
				Swal.fire({
					title: "Duplikasi",
					text: "Input jumlah duplikasi:",
					input: 'text',
					showCancelButton: true        
				}).then((result) => {
					if (result.value) {
					  $.ajax({
						type: "POST",
						dataType: 'json',
						url: window.APP.siteUrl + 'adm/checkin/duplicate',
						data: {
							id: id,
							row: result.value
						},
						success: function (response) {
							
							window.FORM.showNotification(response.message, response.status);
							parentThis.elDatatable.ajax.reload();

						}
					});
					}
				});

				
			});
		},

		handleCheckout: function () {
			var parentThis = this;

			$(parentThis.elTable).on("click", parentThis.elBtnCheckout, function () {
				var id = $(this).attr('data-id');
				
				$.ajax({
					type: "POST",
					dataType: 'json',
					url: window.APP.siteUrl + 'adm/checkin/checkout_process',
					data: {
						id: id
					},
					success: function (response) {
						
						if(response.status == 'success'){
							toastr.success(response.message);
							parentThis.elDatatable.ajax.reload()
						}else{
							toastr.error(response.message);
						}
						// window.FORM.showNotification(response.message, response.status);
						// parentThis.elDatatable.ajax.reload();

					}
				});
			});
		},

	}

})(jQuery);
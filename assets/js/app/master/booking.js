/**
 * Javascript Booking
 *
 * @author Andy Aliansah <andyaliansah97@gmail.com>
 *
 */
window.MASTER = (function ($) {

	var renderCheckbox = function (data, type, full, meta) {
		return '<input class="check-sub-master" type="checkbox" value="' + full['id'] + '">';
	}

	var renderEdit = function (data, type, full, meta) {
		var url = APP.siteUrl + 'adm/booking/edit/' + full['id'];
		return '<a class="master-edit" href="' + url + '" data-toggle="tooltip" data-placement="right" title="Click For Edit">' + data + '</a>';
	}

	var renderCheckin = function (data, type, full, meta) {
		var url = APP.siteUrl + 'adm/checkin/process/' + full['id'];
		var button = '';
		var today = new Date();
		var dd = String(today.getDate()).padStart(2, '0');
		var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
		var yyyy = today.getFullYear();

		today = yyyy + '-' + mm + '-' + dd;

		button = '<a href="' + url + '" class="master-checkin text-success" data-toggle="tooltip" data-placement="right" title="Tekan untuk Check-In"><i class="fas fa-sign-in-alt"></i></a>';

		if(full['status'] != 0){
			button = '<i class="fas fa-check text-success" data-toggle="tooltip" data-placement="right" title="Telah Check-In"></i>';
		}

		if(full['date_in_verify'] > today){
			button = '<i class="fas fa-hourglass-half text-warning" data-toggle="tooltip" data-placement="right" title="Proses Check-In belum dapat dilakukan"></i>';
		}

		if(full['date_out_verify'] < today && full['status'] == 0){
			button = '<i class="fas fa-times text-danger" data-toggle="tooltip" data-placement="right" title="Terlewat"></i>';
		}

		if(!full['room']){
			button = '<i class="fas fa-exclamation-circle text-warning" data-toggle="tooltip" data-placement="right" title="Data belum lengkap"></i>';
		}

		return button;
	}

	var renderDuplicate = function (data, type, full, meta) {
		return '<a href="#" class="master-duplicate text-warning" data-id="' + full['id'] + '"  data-toggle="tooltip" data-placement="right" title="Tekan untuk Duplikasi"><i class="far fa-clone"></i></i></a>';
	}

	return {

		elDatatable: null,
		elVue: '#vue-master',
		elForm: '.master-form',
		elEdit: '.master-edit',
		elTable: '#master-table',
		elBtnDuplicate: '.master-duplicate',
		elModal: '.master-modal',
		elBtnDelete: '.master-delete',
		elModalClose: '.master-cancel',
		elSubCheckbox: '.check-sub-master',
		elParentCheckbox: '.check-all-master',
		elModalContent: '.master-modal-content',
		urlDeleteData: window.APP.siteUrl + 'adm/booking/delete',
		urlRequestData: window.APP.siteUrl + 'adm/booking/get_data',

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
				methods: {
					addRowType: function () {
						var vue = this;
					}
				},
				mounted: function () {
					parentThis.handleDataTable();
				}
			});

		},

		// Booking : handleDataTable
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
						render: renderCheckin,
						className: 'fit-width text-center',
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
					}
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

					// document.getElementById("master-edit").click();
					
					parentThis.handleDelete();
					parentThis.handleDuplicate();
					window.INPUT.handleCheckboxAll(parentThis.elParentCheckbox, parentThis.elSubCheckbox);
				}

			});
		},

		// Booking : handleDelete
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
						url: window.APP.siteUrl + 'adm/booking/duplicate',
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

	}

})(jQuery);
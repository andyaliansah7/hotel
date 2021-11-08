/**
 * Javascript Payments
 *
 * @author Andy Aliansah <andyaliansah97@gmail.com>
 *
 */
window.MASTER = (function ($) {

	var renderCheckbox = function (data, type, full, meta) {
		return '<input class="check-sub-master" type="checkbox" value="' + full['id'] + '">';
	}

	var renderEdit = function (data, type, full, meta) {
		var url = APP.siteUrl + 'adm/payments/edit/' + full['id'];
		return '<a class="master-edit" href="' + url + '" data-toggle="tooltip" data-placement="right" title="Click For Edit">' + data + '</a>';
	}

	var renderPrint = function(data, type, full, meta) {
    	var url = APP.siteUrl + 'adm/consumption_services/print_out/' + full['id'];
        return '<a href="#" data-toggle="tooltip" data-placement="right" title="Tekan untuk Print"><i class="fa fa-print btn btn-success btn-sm"></i></a>';
    }

	return {

		elDatatable: null,
		elVue: '#vue-master',
		elForm: '.master-form',
		elEdit: '.master-edit',
		elTable: '#master-table',
		elModal: '.master-modal',
		elBtnDelete: '.master-delete',
		elModalClose: '.master-cancel',
		elSubCheckbox: '.check-sub-master',
		elParentCheckbox: '.check-all-master',
		elModalContent: '.master-modal-content',
		urlDeleteData: window.APP.siteUrl + 'adm/payments/delete',
		urlRequestData: window.APP.siteUrl + 'adm/payments/get_data',

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
					// 
				},
				mounted: function () {
					parentThis.handleDataTable();
				}
			});

		},

		// Payments : handleDataTable
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
						data: 'payment_number',
						className: 'fit-width',
						render: renderEdit,
					},
					{
						data: 'payment_date',
						className: 'fit-width',
					},
					{
						data: 'checkin_number',
						className: 'fit-width',
					},
					{
						data: 'guest_name',
						className: 'fit-width',
					},
					{
						data: 'room_type',
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
						data: 'total_room',
						className: 'fit-width',
					},
					{
						data: 'discount',
						className: 'fit-width',
					},
					{
						data: 'deposit',
						className: 'fit-width',
					},
					{
						data: 'total_consumption',
						className: 'fit-width',
					},
					{
						data: 'total_service',
						className: 'fit-width',
					},
					// {
					// 	data: 'tax',
					// 	className: 'fit-width',
					// },
					{
						data: 'total',
						className: 'fit-width',
					},
					{
						data: 'total_paid_1',
						className: 'fit-width',
					},
					{
						data: 'payment_method_1',
						className: 'fit-width',
					},
					{
						data: 'total_paid_2',
						className: 'fit-width',
					},
					{
						data: 'payment_method_2',
						className: 'fit-width',
					},
					{
						data: 'total_paid_3',
						className: 'fit-width',
					},
					{
						data: 'payment_method_3',
						className: 'fit-width',
					},
					{
						render: renderPrint,
						className: 'fit-width',
					}
				],

				order: [],
				deferRender: true,
				scrollX: true,
				"columnDefs": [{
					"targets": [0,21],
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
					
					parentThis.handleDelete();
					window.INPUT.handleCheckboxAll(parentThis.elParentCheckbox, parentThis.elSubCheckbox);
				}

			});
		},

		// Payments : handleDelete
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

	}

})(jQuery);
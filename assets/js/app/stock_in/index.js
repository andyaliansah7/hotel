/**
 * Javascript Transaksi
 *
 * @author Andy Aliansah <andyaliansah97@gmail.com>
 *
 */
window.MASTER = (function ($) {

	var renderCheckbox = function (data, type, full, meta) {
		return '<input class="check-sub-master" type="checkbox" value="' + full['id'] + '">';
	}

	var renderEdit = function (data, type, full, meta) {
		var url = APP.siteUrl + 'adm/stock_in/edit/' + full['id'];
		return '<a class="master-edit" href="' + url + '" data-toggle="tooltip" data-placement="right" title="Tekan untuk Edit">' + data + '</a>';
	}

	var renderPrint = function(data, type, full, meta) {
    	var url = APP.siteUrl + 'adm/stock_in/print_out/' + full['id'];
        return '<a class="master-edit" href="#" data-toggle="tooltip" data-placement="right" title="Tekan untuk Print"><i class="fa fa-print btn btn-success btn-sm"></i></a>';
    }

	return {

		elDatatable: null,
		elVue: '#vue-master',
		elForm: '.master-form',
		elEdit: '.master-edit',
		elTable: '#master-table',
		elBtnDelete: '.master-delete',
		elSubCheckbox: '.check-sub-master',
		elParentCheckbox: '.check-all-master',
		urlDeleteData: window.APP.siteUrl + 'adm/stock_in/delete',
		urlRequestData: window.APP.siteUrl + 'adm/stock_in/get_data_header',

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

		// Transaksi : handleDataTable
		handleDataTable: function () {
			var parentThis = this;

			// Datatable
			// $('#master-table thead tr:eq(6) th:eq(7)').html("This is a really long column title!");

			parentThis.elDatatable = $(parentThis.elTable).DataTable({
				ajax: {
					url: parentThis.urlRequestData
				},
				columns: [{
						data: 'no',
						width: '20',
						className: 'fit-width',
						render: renderCheckbox
					},
					{
						data: 'number',
						render: renderEdit
					},
					{
						data: 'date',
					},
					{
						data: 'desc',
					}
				],

				order: [],
				deferRender: true,
				scrollX: true,
				"columnDefs": [{
					"targets": [0],
					"orderable": false,
				}],
				"language": {
					"url": parentThis.urlBahasa,
					"sEmptyTable": "Tidads"
				},

				initComplete: function () {
					parentThis.handleDelete();
					window.INPUT.handleCheckboxAll(parentThis.elParentCheckbox, parentThis.elSubCheckbox);
				}

			});
		},

		// Transaksi : handleDelete
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
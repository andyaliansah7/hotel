/**
 * Javascript Rooms
 *
 * @author Andy Aliansah <andyaliansah97@gmail.com>
 *
 */
window.MASTER = (function ($) {

	var renderCheckbox = function (data, type, full, meta) {
		return '<input class="check-sub-master" type="checkbox" value="' + full['id'] + '">';
	}

	var renderEdit = function (data, type, full, meta) {
		var url = APP.siteUrl + 'adm/rooms/edit/' + full['id'];
		return '<a class="master-edit" href="' + url + '" data-toggle="tooltip" data-placement="right" title="Click For Edit">' + data + '</a>';
	}

	var renderActive = function (data, type, full, meta) {
		var url = APP.siteUrl + 'adm/rooms/room_active/' + full['id'];
		return '<a href="#" class="master-active btn btn-sm btn-outline-success ' +full['bgcolor']+'"style="width:110%" data-id="' + full['id'] + '" data-status="' + full['room_active'] + '" data-toggle="tooltip" data-placement="right"><i class="' + full['bgicon'] + '"></i></a>';
	}

	return {

		elDatatable: null,
		elVue: '#vue-master',
		elForm: '.master-form',
		elEdit: '.master-edit',
		elTable: '#master-table',
		elModal: '.master-modal',
		elBtnDelete: '.master-delete',
		elBtnActive: '.master-active',
		elModalClose: '.master-cancel',
		elSubCheckbox: '.check-sub-master',
		elParentCheckbox: '.check-all-master',
		elModalContent: '.master-modal-content',
		urlActiveRoom: window.APP.siteUrl + 'adm/rooms/room_active',
		urlDeleteData: window.APP.siteUrl + 'adm/rooms/delete',
		urlRequestData: window.APP.siteUrl + 'adm/rooms/get_data',

		urlBahasa: window.APP.baseUrl + 'assets/js/vendor/indonesia.json',

		C: function () {
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

		// Rooms : handleDataTable
		handleDataTable: function () {
			var parentThis = this;

			// Datatable
			parentThis.elDatatable = $(parentThis.elTable).DataTable({
				ajax: {
					url: parentThis.urlRequestData
				},
				columns: [{
						data: 'no',
						width: '20',
						render: renderCheckbox
					},
					{
						className: 'fit-width',
						render: renderActive,
					},
					{
						data: 'number',
						render: renderEdit,
					},
					{
						data: 'room_type'
					},
					{
						data: 'status'
					}
				],

				order: [],
				deferRender: true,
				scrollX: true,
				"columnDefs": [{
					"targets": [0, 1],
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
					parentThis.handleActive();
					window.INPUT.handleCheckboxAll(parentThis.elParentCheckbox, parentThis.elSubCheckbox);
				}

			});
		},

		// Rooms : handleDelete
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

		handleActive: function () {
			var parentThis = this;

			$(parentThis.elTable).on("click", parentThis.elBtnActive, function () {
				var id = $(this).attr('data-id');
				var data = $(this).attr('data-status');
				
				$.ajax({
					type: "POST",
					dataType: 'json',
					url: parentThis.urlActiveRoom,
					data: {
						id: id,
						data: data
					},
					success: function (response) {
						if(response.status == 'success'){
							toastr.success(response.message)
						}else{
							toastr.warning(response.message)
						}
						
						// window.FORM.showNotification(response.message, response.status);
						parentThis.elDatatable.ajax.reload();
					}
				});
			});
		},

	}

})(jQuery);
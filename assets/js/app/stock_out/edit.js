/**
 * Javascript Edit
 * @author Andy Aliansah <andyaliansah97@gmail.com>
 */
window.FORM_EDIT = (function ($) {
	return {

		// vue
		initVue: null,
		elVue: "#vue-edit",
		elTable: "#edit-table",

		// form
		elForm: ".edit-form",
		elModal: ".edit-modal",
		elModalC: ".edit-modal-content",
		elInputMask: ".inputmasknumber",

		// checkbox
		elParentCheckbox: ".check-all",
		elSubCheckbox: ".check-sub",

		// url
        urlPrintOut: window.APP.siteUrl + 'adm/stock_out/print_out/',
        urlIndex: window.APP.siteUrl + 'adm/stock_out/',
        urlList: window.APP.siteUrl + 'adm/stock_out/get_embed',
        urlRequestData: window.APP.siteUrl + 'adm/stock_out/get_data_detail',

		// initial
		init: function () {
			var that = this;

			that.handleVue();
		},

		// vue
		handleVue: function () {
			var that = this;

			that.initVue = new Vue({
				el: that.elVue,
				delimiters: ['<%', '%>'],
				data: {
					detailData: []
				},
				methods: {
					detailAdd: function () {
						// show modal
						$(that.elModal).modal("show");
						$.ajax({
							url: that.urlList,
							success: function (response) {
								$(that.elModalC).html(response);
							}
						});
					},

					detailDelete: function () {
						var vue = this;
						var rows = [];
						var arr = vue.detailData;
						// var tr = document.getElementById("table-edit").getElementsByTagName("tr").length;

						// mencari checkbox yg diselect
						var items = $(that.elTable).find('input[class="check-sub"]:checked');
						for (var i = 0; i < items.length; i++) {
							rows.push($(items[i]).val());
						}

						// jika tidak ada yg dipilih
						// munculkan notif
						// sebaliknya menghapus data
						if (!rows.length) {
							toastr.warning('Silahkan pilih data yang akan dihapus terlebih dahulu!')
						} else {
							// delete array dari data yg dipilih
							var i;
							for (i = 0; i < arr.length; i++) {
								delete arr[rows[i]];
								// this.detailsSatuan.splice(i, 1);
							}


							that.arrayClean(arr, undefined);
							// update vue data
							setTimeout(function () {
								vue.$set(vue, 'detailData', arr);
								var ii = $('input[type="checkbox"]');
								ii.prop('checked', false);

							}, 100);
						}
					},

                    handleData: function () {
                    	var vue = this;
                    	var id = $("#id").val();

                    	$.ajax({
                    		url: that.urlRequestData,
                    		type: 'post',
                    		data: {
                    			'id': id
                    		},
							dataType: "json",
							beforeSend: function () {
								$(that.elVue).block({
									message: '<h4>Please Wait..</h4>'
								});
							},
                    		success: function (response) {
								vue.$set(vue, 'detailData', response);
								$(that.elVue).unblock();
                    		}
                    	});
					},

					handleOnBehalf: function () {
						var vue = this;

						$(".room-select").change(function (e) {
							var id = $(e.target).val();
							$.ajax({
								type: "POST",
								url: window.APP.siteUrl + 'adm/stock_out/get_checkin',
								dataType: "JSON",
								data: {
									id: id
								},
								success: function (data) {
									$("#on_behalf_id").val(data['id']);
									$("#on_behalf_name").val(data['name']);
								},
							});			
						});
					},

					handlePrice: function(row, field_name, value) {
						var vue = this;
						
						setTimeout(function () {
							window.INPUT.handleMaskCurrency();
						}, 100);
						
						vue.$set(row, field_name, value);
						
						vue.handleCount(row, field_name, value);
					},
					
					handleCount: function (row) {
						var vue = this;

						var total = 0;

						console.log(row.price.replace(/\,/g,''));
						// total = (Number(row.quantity) * Number(row.price.replace(/[^\w\s]/gi, '')));
						total = (row.quantity * row.price.replace(/\,/g,''));
						vue.$set(row, 'total', total.toLocaleString("en-US"));
					},

					handleQty: function (row) {
						var vue = this;

						var total = 0;
						if(row.quantity > row.stock){
							toastr.error('Stok tidak mencukupi!')
							vue.$set(row, 'quantity', row.stock);
						}
						
					},

					countTotal: function () {
						var vue = this;

						var totalAmmount = 0;
						var items = vue.detailData;

						for (var i in items) {
							totalAmmount += parseFloat(items[i].total.replace(/\,/g,''));
						}

						return totalAmmount.toLocaleString("en-US");
					},
                     
				},
				mounted: function () {
                    var vue = this;
					var id = $("#id").val();
					
                    if(id != 'new'){
						vue.handleData();
					}
					vue.handleOnBehalf();
					
					setTimeout(function () {
						that.handleForm();
						window.INPUT.handleCheckboxAll(that.elParentCheckbox, that.elSubCheckbox);
					}, 500);
				}
			});

		},
        
		// form
		handleForm: function () {
			var that = this;

			window.DATETIME.initDatePicker();

			$(that.elForm).validate();

			$(that.elForm).ajaxForm({
				beforeSend: function () {
					$(that.elVue).block();

					if (that.initVue.detailData.length == 0) {
						toastr.warning('Silahkan isi data terlebih dahulu!')
						$(that.elVue).unblock();
						return false;
					}
				},
				data: {
					vuedata: that.initVue.detailData
				},
				dataType: "json",
				success: function (response) {
					$(that.elVue).unblock();

					window.FORM.showNotification(response.message, response.status);

					if (response.status == "success") {
						setTimeout(function () {
							// window.location.href = that.urlPrintOut +response.id
							window.location.href = that.urlIndex
						}, 1500);
					}

				}
			});

        },
        
		/**
		 * Mencari key dan value dari sebuah array yg dihasilkan
		 */
		objectFindByKey: function (array, key, value) {
			var newArr = [];
			for (var i = 0; i < array.length; i++) {
				if (array[i][key] === value) {
					newArr.push(array[i]);
				}

			}
			return newArr;
		},

		arrayClean: function (array, deleteValue) {
			for (var i = 0; i < array.length; i++) {
				if (array[i] == deleteValue) {
					array.splice(i, 1);
					i--;
				}
			}
			return array;
		},
	}
})(jQuery);
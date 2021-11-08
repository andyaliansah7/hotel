/**
 * Javascript Embed
 * @author Andy Aliansah <andyaliansah97@gmail.com>
 */

window.EMBED = (function ($) {
	return {

		initVue: null,
		elVue: '#embed-vue',
		elTable: "#embed-table",
		elClose: '.embed-close',

		// checkbox
		elParentCheckbox: ".check-all",
		elSubCheckbox: ".check-sub",

		urlGetData: window.APP.siteUrl + 'adm/stock_out/get_data_embed',

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
					listData: [],
					a:'',
				},
				methods: {

					getDataEmbed: function (Event) {
                        var vue = this;
                        var value = $("#search").val();
						
						$.ajax({
                            url: that.urlGetData,
                            type: 'post',
                            data: {
                                'search': value
                            },
							dataType: "json",
							success: function (response) {
								// vue.$set(vue, 'listData', response.data);
								
								var items = response.data;
								var listData = {};

								for (i = 0; i < items.length; i++) {

									var btncolor = 'btn-default';
									var btnicon = '';


									var item = items[i];
									if (vue.checkValue_isExists(item.id, vue.a) == 'Exist') {
										btncolor = 'btn-success';
										btnicon = 'fa fa-check';
									}
									listData[i] = {
										'no'      : item.no,
										'id'      : item.id,
										'code'    : item.code,
										'name'    : item.name,
										'type'    : item.type,
										'price'   : item.price,
										'stock'   : item.stock,
										'quantity': '1',
										'total'   : item.price,
										'btncolor': btncolor,
										'btnicon' : btnicon
									};
								}
								vue.listData = listData;
							}
						});
					},

					getSelectedData: function (row, idx) {
						var vue = this;
						var editInit = window.FORM_EDIT;
						var editVueInit = editInit.initVue;
						
						if (vue.checkValue_isExists(row.id, vue.a) == 'Exist') {
							// toastr.warning('Data sudah tersedia!')
							index = editVueInit.detailData.map(function (item) {
								return item.id
							}).indexOf(row.id);

							editVueInit.detailData.splice(index, 1);
							vue.$set(vue.listData[idx], 'btncolor', 'btn-default');
							vue.$set(vue.listData[idx], 'btnicon', '');
						}else{
							editVueInit.detailData.push({
								id      : row.id,
								code    : row.code,
								name    : row.name,
								type    : row.type,
								price   : row.price,
								stock: row.stock,
								quantity: row.stock,
								total   : row.total
							});
							vue.$set(vue.listData[idx], 'btncolor', 'btn-success');
							vue.$set(vue.listData[idx], 'btnicon', 'fa fa-check');
						}
						
						vue.checkData_isExists();
					},

					closeModal: function () {
						$(that.elClose).click();
					},

					checkData_isExists: function () {
						var vue = this;

						var editInit = window.FORM_EDIT;
						var editVueInit = editInit.initVue;

						const a = [];
						const objectArray = Object.entries(editVueInit.detailData);

						objectArray.forEach(([key, value]) => {
							a.push(value.id);
						});

						vue.a = a;
					},	

					checkValue_isExists: function (value, arr) {
						var vue = this;

						var status = 'Not exist';

						for (var i = 0; i < arr.length; i++) {
							var contains = arr[i];
							if (contains == value) {
								status = 'Exist';
								break;
							}
						}

						return status;
					}
				},
				mounted: function () {
					var vue = this;

					vue.getDataEmbed();
					vue.checkData_isExists();
				}
			});

		},
	}
})(jQuery);
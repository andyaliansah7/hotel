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

		urlGetData: window.APP.siteUrl + 'adm/payments/get_data_embed',

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
                        var vue   = this;
                        var value = $("#search").val();
                        var guest = $("#guest").val();
                        var id    = $("#id").val();
						
						$.ajax({
                            url: that.urlGetData,
                            type: 'post',
                            data: {
                                'guest': guest,
                                'search': value,
                                'id': id,
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
									if (vue.checkValue_isExists(item.code, vue.a) == 'Exist') {
										btncolor = 'btn-success';
										btnicon = 'fa fa-check';
									}
									listData[i] = {
										'no'        : item.no,
										'id'        : item.id,
										'code'      : item.code,
										'number'    : item.number,
										'type'      : item.type,
										'guest_group': item.guest_group,
										'guest_name': item.guest_name,
										'guest_telp': item.guest_telp,
										'room'      : item.room,
										'room_type' : item.room_type,
										'date_range': item.date_range,
										'detail_cs' : item.detail_cs,
										'price'     : item.price,
										'discount'  : item.discount,
										'deposit'   : item.deposit,
										'total'     : item.total,
										'paid'      : item.paid,
										'has_paid'      : item.has_paid,
										'remark'    : '',
										'typeicon'  : item.typeicon,
										'typecolor' : item.typecolor,
										'btncolor'  : btncolor,
										'btnicon'   : btnicon,
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
						
						if (vue.checkValue_isExists(row.code, vue.a) == 'Exist') {
							// toastr.warning('Data sudah tersedia!')
							index = editVueInit.detailData.map(function (item) {
								return item.code
							}).indexOf(row.code);

							editVueInit.detailData.splice(index, 1);
							vue.$set(vue.listData[idx], 'btncolor', 'btn-default');
							vue.$set(vue.listData[idx], 'btnicon', '');
						}else{
							editVueInit.detailData.push({
								id        : row.id,
								code      : row.code,
								number    : row.number,
								type      : row.type,
								guest_group: row.guest_group,
								guest_name: row.guest_name,
								guest_telp: row.guest_telp,
								room      : row.room,
								room_type : row.room_type,
								date_range: row.date_range,
								detail_cs : row.detail_cs,
								price     : row.price,
								discount  : row.discount,
								deposit   : row.deposit,
								total     : row.total,
								paid      : row.paid,
								has_paid      : row.has_paid,
								typeicon  : row.typeicon,
								typecolor : row.typecolor,
								remark    : ''
							});
							vue.$set(vue.listData[idx], 'btncolor', 'btn-success');
							vue.$set(vue.listData[idx], 'btnicon', 'fa fa-check');
						}

						setTimeout(function () {
							var depo = $("#all_deposit").val();
							var items = editVueInit.detailData;
							var rooms = [];
							var type_room = [];
							var type_cons = [];
							for (var i in items) {
								rooms.push(items[i].room);
								
								if(items[i].type == 'T'){
									type_room.push(items[i].type);
								}

								if(items[i].type == 'C'){
									type_cons.push(items[i].type);
								}
							}
							// console.log(rooms.join());
							var desc = "";
							// var room = (room.length == 0 ? '' : )
							desc = "Kamar : " +rooms.join(", ")+ ". Deposit : " +depo;

							$("#description").val(desc);
							$("#type_room").val(type_room);
							$("#type_cons").val(type_cons);
						}, 150);
						
						
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
							a.push(value.code);
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
					},

					formatPrice : function(value) {
						let val = (value/1).toFixed().replace(',', '.')
						return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
				 	},
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
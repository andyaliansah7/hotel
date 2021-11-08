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
        urlPrintOut: window.APP.siteUrl + 'adm/payments/print_out/',
        urlList: window.APP.siteUrl + 'adm/payments/get_embed',
        urlRequestData: window.APP.siteUrl + 'adm/payments/get_data_detail',

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
					a:'',
					detailData: [],
					depositData: [],
					selectDepositData: [],
					guestIDNumber:'-',
					guestPhone:'-',
					guestAddress:'-',
					guestDepositMaster:0,
					guestDepositKartu:0,
					guestDepositTunai:0,
					guestDepositTrans:0
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
							// beforeSend: function () {
							// 	$(that.elVue).block({
							// 		message: '<h4>Please Wait..</h4>'
							// 	});
							// },
                    		success: function (response) {
								vue.$set(vue, 'detailData', response);
								$(that.elVue).unblock();
                    		}
                    	});
                    },

					handleDataDeposit: function () {
                    	var vue = this;
                    	var id = $("#id").val();

                    	$.ajax({
                    		url: window.APP.siteUrl + 'adm/payments/get_deposit_by_payment',
                    		type: 'post',
                    		data: {
                    			'id': id
                    		},
							dataType: "json",
							// beforeSend: function () {
							// 	$(that.elVue).block({
							// 		message: '<h4>Please Wait..</h4>'
							// 	});
							// },
                    		success: function (response) {
								vue.$set(vue, 'selectDepositData', response);
								$(that.elVue).unblock();

								vue.checkData_isExists();
                    		}
                    	});
                    },

					handleDataGuest: function (guest_id) {
						var vue = this;
						var id = $("#id").val();

						$.ajax({
							type: "POST",
							url: window.APP.siteUrl + 'adm/payments/get_guest',
							dataType: "JSON",
							data: {
								id: id,
								guest_id: guest_id
							},
							success: function (data) {
								vue.$set(vue, 'guestIDNumber', data['id_number']);
								vue.$set(vue, 'guestPhone', data['phone']);
								vue.$set(vue, 'guestAddress', data['address']);
								
								// vue.$set(vue, 'guestDepositKartu', data['deposit_kartu']);
								// vue.$set(vue, 'guestDepositTunai', data['deposit_tunai']);
								// vue.$set(vue, 'guestDepositTrans', data['deposit_trans']);

								// vue.$set(vue, 'guestDepositMaster', data['deposit']);

								// -----------------------------------------------
								var items = data['deposit_list'];
								var depositData = {};

								for (i = 0; i < items.length; i++) {

									var btncolor = 'bg-default';
									var btnicon = '';


									var item = items[i];
									if (vue.checkValue_isExists(item.id, vue.a) == 'Exist') {
										btncolor = 'bg-success';
										// console.log("exdst");
										// btnicon = 'fa fa-check';
									}
									depositData[i] = {
										'id'              : item.id,
										'date'            : item.date,
										'deposit_amount_1': item.deposit_amount_1,
										'deposit_amount_2': item.deposit_amount_2,
										'deposit_amount_3': item.deposit_amount_3,
										'deposit_kartu'   : item.deposit_kartu,
										'deposit_tunai'   : item.deposit_tunai,
										'deposit_trans'   : item.deposit_trans,
										'total_amount'    : item.total_amount,
										'btncolor'        : btncolor,
										'btnicon'         : btnicon,
									};
								}
								vue.depositData = depositData;
								
							},
						});

					},

					getSelectedData: function (row, idx) {
						var vue = this;
						
						if (vue.checkValue_isExists(row.id, vue.a) == 'Exist') {
							// toastr.warning('Data sudah tersedia!')
							index = vue.selectDepositData.map(function (item) {
								return item.id
							}).indexOf(row.id);

							vue.selectDepositData.splice(index, 1);
							vue.$set(vue.depositData[idx], 'btncolor', 'bg-default');
							vue.$set(vue.depositData[idx], 'btnicon', '');
						}else{
							vue.selectDepositData.push({
								id              : row.id,
								date            : row.date,
								deposit_amount_1: row.deposit_amount_1,
								deposit_amount_2: row.deposit_amount_2,
								deposit_amount_3: row.deposit_amount_3,
								deposit_kartu   : row.deposit_kartu,
								deposit_tunai   : row.deposit_tunai,
								deposit_trans   : row.deposit_trans,
								total_amount    : row.total_amount
							});
							vue.$set(vue.depositData[idx], 'btncolor', 'bg-success');
							// vue.$set(vue.depositData[idx], 'btnicon', 'fa fa-check');
						}

						setTimeout(function () {
							var depo = $("#all_deposit").val();
							var items = vue.detailData;
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
						}, 150);
						console.log(vue.selectDepositData);
						
						vue.checkData_isExists();
					},

					countDepositMaster: function () {
						var vue = this;

						var totalAmmount = 0;
						var total = 0;
						var items = vue.selectDepositData;
						
						for (var i in items) {
							totalAmmount += parseFloat(items[i].total_amount.replace(/,/g, ''));
						}
						
					
						vue.$set(vue, 'guestDepositMaster', totalAmmount);
						return totalAmmount.toLocaleString("en-US");
					},

					countDepositKartu: function () {
						var vue = this;

						var totalAmmount = 0;
						var total = 0;
						var items = vue.selectDepositData;
						
						for (var i in items) {
							totalAmmount += parseFloat(items[i].deposit_kartu.replace(/,/g, ''));
						}

						vue.$set(vue, 'guestDepositKartu', totalAmmount);
						return totalAmmount.toLocaleString("en-US");
					},

					countDepositTunai: function () {
						var vue = this;

						var totalAmmount = 0;
						var total = 0;
						var items = vue.selectDepositData;
						
						for (var i in items) {
							totalAmmount += parseFloat(items[i].deposit_tunai.replace(/,/g, ''));
						}

						vue.$set(vue, 'guestDepositTunai', totalAmmount);
						return totalAmmount.toLocaleString("en-US");
					},

					countDepositTrans: function () {
						var vue = this;

						var totalAmmount = 0;
						var total = 0;
						var items = vue.selectDepositData;
						
						for (var i in items) {
							totalAmmount += parseFloat(items[i].deposit_trans.replace(/,/g, ''));
						}

						
						vue.$set(vue, 'guestDepositTrans', totalAmmount);
						return totalAmmount.toLocaleString("en-US");
					},

					checkData_isExists: function () {
						var vue = this;

						const a = [];
						const objectArray = Object.entries(vue.selectDepositData);

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
					},

					handleOnBehalf: function () {
						var vue = this;

						var previousValue;
						$("#guest").on('focus', function (e) {
							previousValue = this.value;
						}).change(function () {
							var id = $(this).val();	

							var storedPreviousValue = previousValue;

							if (vue.detailData.length > 0) {

								Swal.fire({
									title: 'Anda yakin ingin mengganti Pelanggan?',
									text: "Data item akan direset",
									type: 'warning',
									showCancelButton: true,
									confirmButtonColor: '#3085d6',
									cancelButtonColor: '#d33',
									confirmButtonText: 'Ya, Lanjut!',
									cancelButtonText: 'Batal'
								}).then((result) => {
									if (result.value) {
										vue.detailData.splice(0, vue.detailData.length);
									} else if (result.dismiss == 'cancel') {
										
										if (previousValue != undefined) {
											$("#guest").val(storedPreviousValue).trigger('change.select2');
										}
										previousValue = storedPreviousValue;
										vue.handleDataGuest(previousValue);
									}
								})

							}

							previousValue = this.value;
							vue.handleDataGuest(previousValue);
							
						});
						
					},

					handlePaid: function (Event) {
						var vue = this;
						var total_paid_1 = $("#total_paid_1").val();
						var total_paid_2 = $("#total_paid_2").val();
						var total_paid_3 = $("#total_paid_3").val();

						var totalAmmount = 0;
						var items = vue.detailData;

						for (var i in items) {
							totalAmmount += parseFloat(items[i].total.replace(/,/g, ''));
						}
						
						if(total_paid_1.replace(/,/g, '') > 0){ 
							total_paid_1 = total_paid_1.replace(/,/g, '');
						}else{
							total_paid_1 = 0;
						}
						if(total_paid_2.replace(/,/g, '') > 0){ 
							total_paid_2 = total_paid_2.replace(/,/g, '');
						}else{
							total_paid_2 = 0;
						}
						if(total_paid_3.replace(/,/g, '') > 0){ 
							total_paid_3 = total_paid_3.replace(/,/g, '');
						}else{
							total_paid_3 = 0;
						}

						vue.handlePaidSub1();
						vue.handlePaidSub2();
						vue.handlePaidSub3();

						var total = 0;
						total = Number(total_paid_1) + Number(total_paid_2) + Number(total_paid_3);

						// if(total > totalAmmount){
						// 	$('#btn-paid').attr("disabled", true);
						// }else{
						// 	$('#btn-paid').attr("disabled", false);
						// }
						
					},

					handlePaidSub1: function (Event) {
						var vue                 = this;
						var total_paid_1        = $("#total_paid_1").val();
						var total_room_1        = $("#total_room_1").val();
						var total_service_1     = $("#total_service_1").val();
						var total_consumption_1 = $("#total_consumption_1").val();

						if(total_paid_1.replace(/,/g, '') > 0){ 
							total_paid_1 = total_paid_1.replace(/,/g, '');
						}else{
							total_paid_1 = 0;
						}
						if(total_room_1.replace(/,/g, '') > 0){ 
							total_room_1 = total_room_1.replace(/,/g, '');
						}else{
							total_room_1 = 0;
						}
						if(total_service_1.replace(/,/g, '') > 0){ 
							total_service_1 = total_service_1.replace(/,/g, '');
						}else{
							total_service_1 = 0;
						}
						if(total_consumption_1.replace(/,/g, '') > 0){ 
							total_consumption_1 = total_consumption_1.replace(/,/g, '');
						}else{
							total_consumption_1 = 0;
						}

						var total = 0;
						total = Number(total_room_1) + Number(total_service_1) + Number(total_consumption_1);

						// if(total > total_paid_1){
						// 	$('#btn-paid').attr("disabled", true);
						// }else{
						// 	$('#btn-paid').attr("disabled", false);
						// }
					},

					handlePaidSub2: function (Event) {
						var vue = this;
						var total_paid_2        = $("#total_paid_2").val();
						var total_room_2        = $("#total_room_2").val();
						var total_service_2     = $("#total_service_2").val();
						var total_consumption_2 = $("#total_consumption_2").val();

						if(total_paid_2.replace(/,/g, '') > 0){ 
							total_paid_2 = total_paid_2.replace(/,/g, '');
						}else{
							total_paid_2 = 0;
						}
						if(total_room_2.replace(/,/g, '') > 0){ 
							total_room_2 = total_room_2.replace(/,/g, '');
						}else{
							total_room_2 = 0;
						}
						if(total_service_2.replace(/,/g, '') > 0){ 
							total_service_2 = total_service_2.replace(/,/g, '');
						}else{
							total_service_2 = 0;
						}
						if(total_consumption_2.replace(/,/g, '') > 0){ 
							total_consumption_2 = total_consumption_2.replace(/,/g, '');
						}else{
							total_consumption_2 = 0;
						}

						var total = 0;
						total = Number(total_room_2) + Number(total_service_2) + Number(total_consumption_2);

						// if(total > total_paid_2){
						// 	$('#btn-paid').attr("disabled", true);
						// }else{
						// 	$('#btn-paid').attr("disabled", false);
						// }
					},

					handlePaidSub3: function (Event) {
						var vue = this;
						var total_paid_3        = $("#total_paid_3").val();
						var total_room_3        = $("#total_room_3").val();
						var total_service_3     = $("#total_service_3").val();
						var total_consumption_3 = $("#total_consumption_3").val();

						if(total_paid_3.replace(/,/g, '') > 0){ 
							total_paid_3 = total_paid_3.replace(/,/g, '');
						}else{
							total_paid_3 = 0;
						}
						if(total_room_3.replace(/,/g, '') > 0){ 
							total_room_3 = total_room_3.replace(/,/g, '');
						}else{
							total_room_3 = 0;
						}
						if(total_service_3.replace(/,/g, '') > 0){ 
							total_service_3 = total_service_3.replace(/,/g, '');
						}else{
							total_service_3 = 0;
						}
						if(total_consumption_3.replace(/,/g, '') > 0){ 
							total_consumption_3 = total_consumption_3.replace(/,/g, '');
						}else{
							total_consumption_3 = 0;
						}

						var total = 0;
						total = Number(total_room_3) + Number(total_service_3) + Number(total_consumption_3);

						// if(total > total_paid_3){
						// 	$('#btn-paid').attr("disabled", true);
						// }else{
						// 	$('#btn-paid').attr("disabled", false);
						// }
					},
					
					countTotalPrice: function () {
						var vue = this;

						var totalAmmount = 0;
						var items = vue.detailData;
						// console.log(items);
						for (var i in items) {
							totalAmmount += parseFloat(items[i].price.replace(/,/g, ''));
						}

						return totalAmmount.toLocaleString("en-US");
					},

					countTotalDiscount: function () {
						var vue = this;

						var totalAmmount = 0;
						var items = vue.detailData;
						// console.log(items);
						for (var i in items) {
							totalAmmount += parseFloat(items[i].discount.replace(/,/g, ''));
						}

						return totalAmmount.toLocaleString("en-US");
					},

					countTotalDepositAll: function () {
						var vue = this;

						var totalAmmount = 0;
						var total = 0;
						var items = vue.detailData;
						
						for (var i in items) {
							totalAmmount += parseFloat(items[i].deposit.replace(/,/g, ''));
						}

						total = Number(totalAmmount) + Number(vue.guestDepositMaster);
						return total.toLocaleString("en-US");
					},

					countTotalDeposit: function () {
						var vue = this;

						var totalAmmount = 0;
						var items = vue.detailData;
						
						for (var i in items) {
							totalAmmount += parseFloat(items[i].deposit.replace(/,/g, ''));
						}

						return totalAmmount.toLocaleString("en-US");
					},

					countTotalAmount: function () {
						var vue = this;

						var totalAmmount = 0;
						var total = 0;
						var items = vue.detailData;
						// console.log(items);
						for (var i in items) {
							totalAmmount += parseFloat(items[i].total.replace(/,/g, ''));
						}

						// if(totalAmmount > 0){
							total = Number(totalAmmount) - Number(vue.guestDepositMaster);
						// }
						// else{
						// 	total = 0;
						// }
						

						vue.handlePaid();
						return total.toLocaleString("en-US");
					},

					formatPrice : function(value) {
						let val = (value/1).toFixed().replace(',', '.')
						return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
				 	},
                     
				},
				mounted: function () {
                    var vue = this;
					var id = $("#id").val();
					var guest_id = $("#guest").val();
					
                    if(id != 'new'){
						vue.handleData();
						vue.handleDataDeposit();
						setTimeout(function () {
						vue.handleDataGuest(guest_id);
					}, 500);
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
					vuedata: that.initVue.detailData,
					depdata: that.initVue.selectDepositData
				},
				dataType: "json",
				success: function (response) {
					$(that.elVue).unblock();

					window.FORM.showNotification(response.message, response.status);

					if (response.status == "success") {
						setTimeout(function () {
							window.location.href = window.APP.siteUrl + 'adm/payments'
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
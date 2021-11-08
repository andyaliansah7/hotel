/**
 * Javascript Programs
 *
 * @author Andy Aliansah <andyaliansah97@gmail.com>
 *
 */
window.REPORT = (function ($) {

	return {

		elDatatable: null,
		elVue: '#vue-master',
		elDate1: '.date_1',
		elDate2: '.date_2',
		elShift: '.shift',

		urlRequestData: window.APP.siteUrl + 'adm/r_monthrecaps2/get_data_detail',

		urlBahasa: window.APP.baseUrl + 'assets/js/vendor/indonesia.json',

		init: function () {
			var that = this;

			that.handleVue();
		},

		// Master
		handleVue: function () {
			var that = this;

			// Vue Js
			new Vue({
				el: that.elVue,
				delimiters: ['<%', '%>'],
				data: {
					date_1: '',
					date_2: '',
					shift_name: '',
					detailData: [],
					detailData2: [],
					detailData3: [],
					detailData4: [],
					total_kartu: 0,
					total_tunai: 0,
					total_trans: 0,
					total_all_method: 0,
					total_kartu2: 0,
					total_tunai2: 0,
					total_trans2: 0,
					total_all_method2: 0,
					total_kartu_fee: 0,
					total_tunai_fee: 0,
					total_trans_fee: 0,
					total_all_method_fee: 0,
					total_kartu_dep: 0,
					total_tunai_dep: 0,
					total_trans_dep: 0,
					total_all_method_dep: 0,
					total_kartu_grand: 0,
					total_tunai_grand: 0,
					total_trans_grand: 0,
					total_all_method_grand: 0,
				},
				methods: {
					getData: function () {
						var vue = this;
						var date_1 = $(that.elDate1).val();
						var date_2 = $(that.elDate2).val();
						var shift = $(that.elShift).val();

						var shift_name = $(that.elShift + ' option:selected').text();

						vue.$set(vue, 'shift_name', 'Semua');
						if(shift_name != "- Pilih -"){
							vue.$set(vue, 'shift_name', shift_name);
						}

						$.ajax({
							url: that.urlRequestData,
							type: 'post',
							data: {
								'date_1': date_1,
								'date_2': date_2,
								'shift': shift,
							},
							dataType: "json",
							// beforeSend : function() {
							// 	$(that.elVue).block({
							// 		message: '<h4>Please Wait..</h4>'
							// 	});
							// },
							success: function (response) {
								vue.$set(vue, 'date_1', response['date_1']);
								vue.$set(vue, 'date_2', response['date_2']);

								vue.$set(vue, 'detailData', response['data']);

								vue.$set(vue, 'total_kartu', response['total_kartu']);
								vue.$set(vue, 'total_tunai', response['total_tunai']);
								vue.$set(vue, 'total_trans', response['total_trans']);
								vue.$set(vue, 'total_all_method', response['total_all_method']);

								vue.$set(vue, 'total_kartu2', response['total_kartu2']);
								vue.$set(vue, 'total_tunai2', response['total_tunai2']);
								vue.$set(vue, 'total_trans2', response['total_trans2']);
								vue.$set(vue, 'total_all_method2', response['total_all_method2']);

								vue.$set(vue, 'total_kartu_fee', response['total_kartu_fee']);
								vue.$set(vue, 'total_tunai_fee', response['total_tunai_fee']);
								vue.$set(vue, 'total_trans_fee', response['total_trans_fee']);
								vue.$set(vue, 'total_all_method_fee', response['total_all_method_fee']);

								vue.$set(vue, 'total_kartu_dep', response['total_kartu_dep']);
								vue.$set(vue, 'total_tunai_dep', response['total_tunai_dep']);
								vue.$set(vue, 'total_trans_dep', response['total_trans_dep']);
								vue.$set(vue, 'total_all_method_dep', response['total_all_method_dep']);

								vue.$set(vue, 'total_kartu_grand', response['total_kartu_grand']);
								vue.$set(vue, 'total_tunai_grand', response['total_tunai_grand']);
								vue.$set(vue, 'total_trans_grand', response['total_trans_grand']);
								vue.$set(vue, 'total_all_method_grand', response['total_all_method_grand']);
								$(that.elVue).unblock();
							}
						});
					},

					exportExcel: function () {
						var vue = this;
						vue.getData();

						setTimeout(function () {
							window.location = window.APP.siteUrl + 'adm/r_monthrecaps2/export_excel';
						}, 500);
					}
				},
				mounted: function () {

				}
			});

		},

	}

})(jQuery);
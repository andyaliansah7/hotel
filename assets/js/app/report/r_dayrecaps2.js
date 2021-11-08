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

		urlRequestData: window.APP.siteUrl + 'adm/r_dayrecaps2/get_data_detail',

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
					date_1           : '',
					date_2           : '',
					detailData       : [],
					ttl_bca_682      : 0,
					ttl_bca_682p     : 0,
					ttl_bca_edc      : 0,
					ttl_bca_edca     : 0,
					ttl_complimentary: 0,
					ttl_house_use    : 0,
					ttl_mandiri_edc  : 0,
					ttl_mandiri_tf   : 0,
					ttl_pending      : 0,
					ttl_voucher      : 0,
					ttl_all          : 0,
				},
				methods: {
					getData: function () {
						var vue = this;
						var date_1 = $(that.elDate1).val();
						var date_2 = $(that.elDate2).val();
						var shift  = $(that.elShift).val();

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

								vue.$set(vue, 'ttl_bca_682', response['ttl_bca_682']);
								vue.$set(vue, 'ttl_bca_682p', response['ttl_bca_682p']);
								vue.$set(vue, 'ttl_bca_edc', response['ttl_bca_edc']);
								vue.$set(vue, 'ttl_bca_edca', response['ttl_bca_edca']);
								vue.$set(vue, 'ttl_complimentary', response['ttl_complimentary']);
								vue.$set(vue, 'ttl_house_use', response['ttl_house_use']);
								vue.$set(vue, 'ttl_mandiri_edc', response['ttl_mandiri_edc']);
								vue.$set(vue, 'ttl_mandiri_tf', response['ttl_mandiri_tf']);
								vue.$set(vue, 'ttl_pending', response['ttl_pending']);
								vue.$set(vue, 'ttl_voucher', response['ttl_voucher']);
								vue.$set(vue, 'ttl_all', response['ttl_all']);
								$(that.elVue).unblock();
							}
						});
					},

					exportExcel: function () {
						var vue = this;
						vue.getData();

						setTimeout(function () {
							window.location = window.APP.siteUrl + 'adm/r_dayrecaps/export_excel';
						}, 500);
					}
				},
				mounted: function () {

				}
			});

		},

	}

})(jQuery);
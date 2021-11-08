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
		elItem: '.item',
		elShift: '.shift',
		elStatus: '.status',

		urlRequestData: window.APP.siteUrl + 'adm/r_stocks/get_data_detail',

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
					detailData: [],
					total_amount: 0,
					total_kartu: 0,
					total_tunai: 0,
					total_trans: 0,
				},
				methods: {
					getData: function () {
						var vue = this;
						var date_1 = $(that.elDate1).val();
						var date_2 = $(that.elDate2).val();
						var item = $(that.elItem).val();
						var shift = $(that.elShift).val();
						var status = $(that.elStatus).val();

						// vue.$set(vue, 'date', text_date);

						$.ajax({
							url: that.urlRequestData,
							type: 'post',
							data: {
								'date_1': date_1,
								'date_2': date_2,
								'date_2': date_2,
								'item': item,
								'shift': shift,
								'status': status,
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

								vue.$set(vue, 'total_amount', response['total_amount']);
								vue.$set(vue, 'total_kartu', response['total_kartu']);
								vue.$set(vue, 'total_tunai', response['total_tunai']);
								vue.$set(vue, 'total_trans', response['total_trans']);
								$(that.elVue).unblock();
							}
						});
					},

					exportExcel: function () {
						var vue = this;
						vue.getData();

						setTimeout(function () {
							window.location = window.APP.siteUrl + 'adm/r_stocks/export_excel';
						}, 500);
					}
				},
				mounted: function () {

				}
			});

		},

	}

})(jQuery);
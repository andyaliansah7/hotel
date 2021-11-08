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

		urlRequestData: window.APP.siteUrl + 'adm/r_consumption_services/get_data_detail',

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
					gtc_quantity: 0,
					gtc_total: 0,
					gts_quantity: 0,
					gts_total: 0,
					detailData: [],
					detailData2: []
				},
				methods: {
					getData: function () {
						var vue = this;
						var date_1 = $(that.elDate1).val();
						var date_2 = $(that.elDate2).val();

						// vue.$set(vue, 'date', text_date);

						$.ajax({
							url: that.urlRequestData,
							type: 'post',
							data: {
								'date_1': date_1,
								'date_2': date_2
							},
							dataType: "json",
							beforeSend : function() {
								$(that.elVue).block({
									message: '<h4>Please Wait..</h4>'
								});
							},
							success: function (response) {
								vue.$set(vue, 'detailData', response['data']);
								vue.$set(vue, 'detailData2', response['data2']);
								console.log(response);
								vue.$set(vue, 'gtc_quantity', response['gtc_quantity']);
								vue.$set(vue, 'gtc_total', response['gtc_total']);
								vue.$set(vue, 'gts_quantity', response['gts_quantity']);
								vue.$set(vue, 'gts_total', response['gts_total']);
								$(that.elVue).unblock();
							}
						});
					},

					exportExcel: function () {
						var vue = this;
						vue.getData();

						setTimeout(function () {
							window.location = window.APP.siteUrl + 'adm/r_consumption_services/export_excel';
						}, 500);
					}
				},
				mounted: function () {

				}
			});

		},

	}

})(jQuery);
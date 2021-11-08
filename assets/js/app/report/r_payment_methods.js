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

		urlRequestData: window.APP.siteUrl + 'adm/r_payment_methods/get_data_detail',

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
					gt_stay: 0,
					gt_room: 0,
					gt_discount: 0,
					gt_deposit: 0,
					gt_consumption: 0,
					gt_service: 0,
					gt_total: 0,
					detailData: []
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
								// vue.$set(vue, 'gt_stay', response['gt_stay']);
								// vue.$set(vue, 'gt_room', response['gt_room']);
								// vue.$set(vue, 'gt_discount', response['gt_discount']);
								// vue.$set(vue, 'gt_deposit', response['gt_deposit']);
								// vue.$set(vue, 'gt_consumption', response['gt_consumption']);
								// vue.$set(vue, 'gt_service', response['gt_service']);
								// vue.$set(vue, 'gt_total', response['gt_total']);
								$(that.elVue).unblock();
							}
						});
					},

					exportExcel: function () {
						var vue = this;
						vue.getData();

						setTimeout(function () {
							window.location = window.APP.siteUrl + 'adm/r_payment_methods/export_excel';
						}, 500);
					}
				},
				mounted: function () {

				}
			});

		},

	}

})(jQuery);
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

		urlRequestData: window.APP.siteUrl + 'adm/r_eaed/get_data_detail',

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
					detailDataBook: [],
					detailData: [],
					detailData2: [],
					detailData3: [],
					detailData4: [],
					detailData5: [],
					detailData6: [],
					total_extra_bed_1: 0,
					total_extra_bed_2: 0,
					total_extra_bed_3: 0,
					total_extra_bed_book: 0,
					total_pax_1: 0,
					total_pax_2: 0,
					total_pax_3: 0,
					total_pax_book: 0,
					total_room_rate_1: 0,
					total_room_rate_2: 0,
					total_room_rate_3: 0,
					total_discount_1: 0,
					total_discount_2: 0,
					total_discount_3: 0,
					total_room_rate_discount_1: 0,
					total_room_rate_discount_2: 0,
					total_room_rate_discount_3: 0,
					total_extra_bed_all         : 0,
					total_pax_all               : 0,
					total_room_rate_all         : 0,
					total_discount_all          : 0,
					total_room_rate_discount_all: 0,
					guest_group_total: 0,
					guest_group_residence: 0,
					froom_rate_total: 0,
					trx_total: 0,
					room_sealeble: 0,
					room_occupaid: 0,
					room_sold: 0,
					complimentary: 0,
					house_use: 0,
					percentage_occupancy: 0,
					pax_summary: 0,
					avg_room_rate: 0,
					avg_rate_guest: 0,
					room_rev_before_disc: 0,
					room_allowance: 0,
					total_room_revenue: 0,
					total_non_room_revenue: 0,
					total_all_revenue: 0,
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
								vue.$set(vue, 'date_1', response['date_1']);
								vue.$set(vue, 'date_2', response['date_2']);

								vue.$set(vue, 'detailDataBook', response['data_book']);
								vue.$set(vue, 'detailData', response['data']);
								vue.$set(vue, 'detailData2', response['data2']);
								vue.$set(vue, 'detailData3', response['data3']);
								vue.$set(vue, 'detailData4', response['data4']);
								vue.$set(vue, 'detailData5', response['data5']);
								vue.$set(vue, 'detailData6', response['data6']);

								vue.$set(vue, 'total_extra_bed_1', response['total_extra_bed_1']);
								vue.$set(vue, 'total_extra_bed_2', response['total_extra_bed_2']);
								vue.$set(vue, 'total_extra_bed_3', response['total_extra_bed_3']);
								vue.$set(vue, 'total_extra_bed_book', response['total_extra_bed_book']);

								vue.$set(vue, 'total_pax_1', response['total_pax_1']);
								vue.$set(vue, 'total_pax_2', response['total_pax_2']);
								vue.$set(vue, 'total_pax_3', response['total_pax_3']);
								vue.$set(vue, 'total_pax_book', response['total_pax_book']);

								vue.$set(vue, 'total_room_rate_1', response['total_room_rate_1']);
								vue.$set(vue, 'total_room_rate_2', response['total_room_rate_2']);
								vue.$set(vue, 'total_room_rate_3', response['total_room_rate_3']);

								vue.$set(vue, 'total_discount_1', response['total_discount_1']);
								vue.$set(vue, 'total_discount_2', response['total_discount_2']);
								vue.$set(vue, 'total_discount_3', response['total_discount_3']);

								vue.$set(vue, 'total_room_rate_discount_1', response['total_room_rate_discount_1']);
								vue.$set(vue, 'total_room_rate_discount_2', response['total_room_rate_discount_2']);
								vue.$set(vue, 'total_room_rate_discount_3', response['total_room_rate_discount_3']);

								vue.$set(vue, 'total_extra_bed_all', response['total_extra_bed_all']);
								vue.$set(vue, 'total_pax_all', response['total_pax_all']);
								vue.$set(vue, 'total_room_rate_all', response['total_room_rate_all']);
								vue.$set(vue, 'total_discount_all', response['total_discount_all']);
								vue.$set(vue, 'total_room_rate_discount_all', response['total_room_rate_discount_all']);

								vue.$set(vue, 'guest_group_total', response['guest_group_total']);
								vue.$set(vue, 'guest_group_residence', response['guest_group_residence']);

								vue.$set(vue, 'froom_rate_total', response['froom_rate_total']);
								vue.$set(vue, 'trx_total', response['trx_total']);

								vue.$set(vue, 'room_sealeble', response['room_sealeble']);
								vue.$set(vue, 'room_occupaid', response['room_occupaid']);
								vue.$set(vue, 'room_sold', response['room_sold']);
								vue.$set(vue, 'complimentary', response['complimentary']);
								vue.$set(vue, 'house_use', response['house_use']);
								vue.$set(vue, 'percentage_occupancy', response['percentage_occupancy']);
								vue.$set(vue, 'pax_summary', response['pax_summary']);
								vue.$set(vue, 'avg_room_rate', response['avg_room_rate']);
								vue.$set(vue, 'avg_rate_guest', response['avg_rate_guest']);
								vue.$set(vue, 'room_rev_before_disc', response['room_rev_before_disc']);
								vue.$set(vue, 'room_allowance', response['room_allowance']);
								vue.$set(vue, 'total_room_revenue', response['total_room_revenue']);
								vue.$set(vue, 'total_non_room_revenue', response['total_non_room_revenue']);
								vue.$set(vue, 'total_all_revenue', response['total_all_revenue']);

								$(that.elVue).unblock();
							}
						});
					},

					exportExcel: function () {
						var vue = this;
						vue.getData();

						setTimeout(function () {
							window.location = window.APP.siteUrl + 'adm/r_eaed/export_excel';
						}, 500);
					}
				},
				mounted: function () {

				}
			});

		},

	}

})(jQuery);
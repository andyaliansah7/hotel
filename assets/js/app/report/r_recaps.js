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
		elYear: '.year_filter',
		elDate2: '.date_2',
		elShift: '.shift',

		urlRequestData: window.APP.siteUrl + 'adm/R_recaps/get_data_detail',

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
					year_text: '',
					date_2: '',
					shift_name: '',

					dataRoom: [],
					dataMeeting: [],
					dataPoolside: [],
					dataResto: [],
					dataMischarge: [],
					dataSwimming: [],

					tc_jan: 0,
					ts_jan: 0,
					tc_feb: 0,
					ts_feb: 0,
					tc_mar: 0,
					ts_mar: 0,
					tc_apr: 0,
					ts_apr: 0,
					tc_mei: 0,
					ts_mei: 0,
					tc_jun: 0,
					ts_jun: 0,
					tc_jul: 0,
					ts_jul: 0,
					tc_agu: 0,
					ts_agu: 0,
					tc_sep: 0,
					ts_sep: 0,
					tc_okt: 0,
					ts_okt: 0,
					tc_nov: 0,
					ts_nov: 0,
					tc_des: 0,
					ts_des: 0,

					px_jan: 0,
					px_feb: 0,
					px_mar: 0,
					px_apr: 0,
					px_mei: 0,
					px_jun: 0,
					px_jul: 0,
					px_agu: 0,
					px_sep: 0,
					px_okt: 0,
					px_nov: 0,
					px_des: 0,

					tc_rsd_jan: 0,
					ts_rsd_jan: 0,
					tc_rsd_feb: 0,
					ts_rsd_feb: 0,
					tc_rsd_mar: 0,
					ts_rsd_mar: 0,
					tc_rsd_apr: 0,
					ts_rsd_apr: 0,
					tc_rsd_mei: 0,
					ts_rsd_mei: 0,
					tc_rsd_jun: 0,
					ts_rsd_jun: 0,
					tc_rsd_jul: 0,
					ts_rsd_jul: 0,
					tc_rsd_agu: 0,
					ts_rsd_agu: 0,
					tc_rsd_sep: 0,
					ts_rsd_sep: 0,
					tc_rsd_okt: 0,
					ts_rsd_okt: 0,
					tc_rsd_nov: 0,
					ts_rsd_nov: 0,
					tc_rsd_des: 0,
					ts_rsd_des: 0,

					tc_mee_jan: 0,
					ts_mee_jan: 0,
					tc_mee_feb: 0,
					ts_mee_feb: 0,
					tc_mee_mar: 0,
					ts_mee_mar: 0,
					tc_mee_apr: 0,
					ts_mee_apr: 0,
					tc_mee_mei: 0,
					ts_mee_mei: 0,
					tc_mee_jun: 0,
					ts_mee_jun: 0,
					tc_mee_jul: 0,
					ts_mee_jul: 0,
					tc_mee_agu: 0,
					ts_mee_agu: 0,
					tc_mee_sep: 0,
					ts_mee_sep: 0,
					tc_mee_okt: 0,
					ts_mee_okt: 0,
					tc_mee_nov: 0,
					ts_mee_nov: 0,
					tc_mee_des: 0,
					ts_mee_des: 0,

					tc_poo_jan: 0,
					ts_poo_jan: 0,
					tc_poo_feb: 0,
					ts_poo_feb: 0,
					tc_poo_mar: 0,
					ts_poo_mar: 0,
					tc_poo_apr: 0,
					ts_poo_apr: 0,
					tc_poo_mei: 0,
					ts_poo_mei: 0,
					tc_poo_jun: 0,
					ts_poo_jun: 0,
					tc_poo_jul: 0,
					ts_poo_jul: 0,
					tc_poo_agu: 0,
					ts_poo_agu: 0,
					tc_poo_sep: 0,
					ts_poo_sep: 0,
					tc_poo_okt: 0,
					ts_poo_okt: 0,
					tc_poo_nov: 0,
					ts_poo_nov: 0,
					tc_poo_des: 0,
					ts_poo_des: 0,

					tc_res_jan: 0,
					ts_res_jan: 0,
					tc_res_feb: 0,
					ts_res_feb: 0,
					tc_res_mar: 0,
					ts_res_mar: 0,
					tc_res_apr: 0,
					ts_res_apr: 0,
					tc_res_mei: 0,
					ts_res_mei: 0,
					tc_res_jun: 0,
					ts_res_jun: 0,
					tc_res_jul: 0,
					ts_res_jul: 0,
					tc_res_agu: 0,
					ts_res_agu: 0,
					tc_res_sep: 0,
					ts_res_sep: 0,
					tc_res_okt: 0,
					ts_res_okt: 0,
					tc_res_nov: 0,
					ts_res_nov: 0,
					tc_res_des: 0,
					ts_res_des: 0,

					tc_mis_jan: 0,
					ts_mis_jan: 0,
					tc_mis_feb: 0,
					ts_mis_feb: 0,
					tc_mis_mar: 0,
					ts_mis_mar: 0,
					tc_mis_apr: 0,
					ts_mis_apr: 0,
					tc_mis_mei: 0,
					ts_mis_mei: 0,
					tc_mis_jun: 0,
					ts_mis_jun: 0,
					tc_mis_jul: 0,
					ts_mis_jul: 0,
					tc_mis_agu: 0,
					ts_mis_agu: 0,
					tc_mis_sep: 0,
					ts_mis_sep: 0,
					tc_mis_okt: 0,
					ts_mis_okt: 0,
					tc_mis_nov: 0,
					ts_mis_nov: 0,
					tc_mis_des: 0,
					ts_mis_des: 0,

					tc_swi_jan: 0,
					ts_swi_jan: 0,
					tc_swi_feb: 0,
					ts_swi_feb: 0,
					tc_swi_mar: 0,
					ts_swi_mar: 0,
					tc_swi_apr: 0,
					ts_swi_apr: 0,
					tc_swi_mei: 0,
					ts_swi_mei: 0,
					tc_swi_jun: 0,
					ts_swi_jun: 0,
					tc_swi_jul: 0,
					ts_swi_jul: 0,
					tc_swi_agu: 0,
					ts_swi_agu: 0,
					tc_swi_sep: 0,
					ts_swi_sep: 0,
					tc_swi_okt: 0,
					ts_swi_okt: 0,
					tc_swi_nov: 0,
					ts_swi_nov: 0,
					tc_swi_des: 0,
					ts_swi_des: 0,

					ttl_jan: 0,
					ttl_jan: 0,
					ttl_feb: 0,
					ttl_feb: 0,
					ttl_mar: 0,
					ttl_mar: 0,
					ttl_apr: 0,
					ttl_apr: 0,
					ttl_mei: 0,
					ttl_mei: 0,
					ttl_jun: 0,
					ttl_jun: 0,
					ttl_jul: 0,
					ttl_jul: 0,
					ttl_agu: 0,
					ttl_agu: 0,
					ttl_sep: 0,
					ttl_sep: 0,
					ttl_okt: 0,
					ttl_okt: 0,
					ttl_nov: 0,
					ttl_nov: 0,
					ttl_des: 0,
					ttl_des: 0,
				},
				methods: {
					getData: function () {
						var vue = this;
						var year_filter = $(that.elYear).val();
						// var date_2 = $(that.elDate2).val();
						// var shift = $(that.elShift).val();

						vue.$set(vue, 'year_text', year_filter);

						$.ajax({
							url: that.urlRequestData,
							type: 'post',
							data: {
								'year_filter': year_filter
							},
							dataType: "json",
							beforeSend : function() {
								$(that.elVue).block({
									message: '<h4>Please Wait..</h4>'
								});
							},
							success: function (response) {
								// vue.$set(vue, 'date_1', response['date_1']);
								// vue.$set(vue, 'date_2', response['date_2']);

								vue.$set(vue, 'dataRoom', response['data_room']);
								vue.$set(vue, 'dataMeeting', response['data_meeting']);
								vue.$set(vue, 'dataPoolside', response['data_poolside']);
								vue.$set(vue, 'dataResto', response['data_resto']);
								vue.$set(vue, 'dataMischarge', response['data_mischarge']);
								vue.$set(vue, 'dataSwimming', response['data_swimming'])

								vue.$set(vue, 'tc_jan', response['tc_jan']);
								vue.$set(vue, 'ts_jan', response['ts_jan']);
								vue.$set(vue, 'tc_feb', response['tc_feb']);
								vue.$set(vue, 'ts_feb', response['ts_feb']);
								vue.$set(vue, 'tc_mar', response['tc_mar']);
								vue.$set(vue, 'ts_mar', response['ts_mar']);
								vue.$set(vue, 'tc_apr', response['tc_apr']);
								vue.$set(vue, 'ts_apr', response['ts_apr']);
								vue.$set(vue, 'tc_mei', response['tc_mei']);
								vue.$set(vue, 'ts_mei', response['ts_mei']);
								vue.$set(vue, 'tc_jun', response['tc_jun']);
								vue.$set(vue, 'ts_jun', response['ts_jun']);
								vue.$set(vue, 'tc_jul', response['tc_jul']);
								vue.$set(vue, 'ts_jul', response['ts_jul']);
								vue.$set(vue, 'tc_agu', response['tc_agu']);
								vue.$set(vue, 'ts_agu', response['ts_agu']);
								vue.$set(vue, 'tc_sep', response['tc_sep']);
								vue.$set(vue, 'ts_sep', response['ts_sep']);
								vue.$set(vue, 'tc_okt', response['tc_okt']);
								vue.$set(vue, 'ts_okt', response['ts_okt']);
								vue.$set(vue, 'tc_nov', response['tc_nov']);
								vue.$set(vue, 'ts_nov', response['ts_nov']);
								vue.$set(vue, 'tc_des', response['tc_des']);
								vue.$set(vue, 'ts_des', response['ts_des']);

								vue.$set(vue, 'px_jan', response['px_jan']);
								vue.$set(vue, 'px_feb', response['px_feb']);
								vue.$set(vue, 'px_mar', response['px_mar']);
								vue.$set(vue, 'px_apr', response['px_apr']);
								vue.$set(vue, 'px_mei', response['px_mei']);
								vue.$set(vue, 'px_jun', response['px_jun']);
								vue.$set(vue, 'px_jul', response['px_jul']);
								vue.$set(vue, 'px_agu', response['px_agu']);
								vue.$set(vue, 'px_sep', response['px_sep']);
								vue.$set(vue, 'px_okt', response['px_okt']);
								vue.$set(vue, 'px_nov', response['px_nov']);
								vue.$set(vue, 'px_des', response['px_des']);

								vue.$set(vue, 'tc_rsd_jan', response['tc_rsd_jan']);
								vue.$set(vue, 'tc_rsd_feb', response['tc_rsd_feb']);
								vue.$set(vue, 'tc_rsd_mar', response['tc_rsd_mar']);
								vue.$set(vue, 'tc_rsd_apr', response['tc_rsd_apr']);
								vue.$set(vue, 'tc_rsd_mei', response['tc_rsd_mei']);
								vue.$set(vue, 'tc_rsd_jun', response['tc_rsd_jun']);
								vue.$set(vue, 'tc_rsd_jul', response['tc_rsd_jul']);
								vue.$set(vue, 'tc_rsd_agu', response['tc_rsd_agu']);
								vue.$set(vue, 'tc_rsd_sep', response['tc_rsd_sep']);
								vue.$set(vue, 'tc_rsd_okt', response['tc_rsd_okt']);
								vue.$set(vue, 'tc_rsd_nov', response['tc_rsd_nov']);
								vue.$set(vue, 'tc_rsd_des', response['tc_rsd_des']);

								vue.$set(vue, 'ts_rsd_jan', response['ts_rsd_jan']);
								vue.$set(vue, 'ts_rsd_feb', response['ts_rsd_feb']);
								vue.$set(vue, 'ts_rsd_mar', response['ts_rsd_mar']);
								vue.$set(vue, 'ts_rsd_apr', response['ts_rsd_apr']);
								vue.$set(vue, 'ts_rsd_mei', response['ts_rsd_mei']);
								vue.$set(vue, 'ts_rsd_jun', response['ts_rsd_jun']);
								vue.$set(vue, 'ts_rsd_jul', response['ts_rsd_jul']);
								vue.$set(vue, 'ts_rsd_agu', response['ts_rsd_agu']);
								vue.$set(vue, 'ts_rsd_sep', response['ts_rsd_sep']);
								vue.$set(vue, 'ts_rsd_okt', response['ts_rsd_okt']);
								vue.$set(vue, 'ts_rsd_nov', response['ts_rsd_nov']);
								vue.$set(vue, 'ts_rsd_des', response['ts_rsd_des']);

								vue.$set(vue, 'tc_mee_jan', response['tc_mee_jan']);
								vue.$set(vue, 'ts_mee_jan', response['ts_mee_jan']);
								vue.$set(vue, 'tc_mee_feb', response['tc_mee_feb']);
								vue.$set(vue, 'ts_mee_feb', response['ts_mee_feb']);
								vue.$set(vue, 'tc_mee_mar', response['tc_mee_mar']);
								vue.$set(vue, 'ts_mee_mar', response['ts_mee_mar']);
								vue.$set(vue, 'tc_mee_apr', response['tc_mee_apr']);
								vue.$set(vue, 'ts_mee_apr', response['ts_mee_apr']);
								vue.$set(vue, 'tc_mee_mei', response['tc_mee_mei']);
								vue.$set(vue, 'ts_mee_mei', response['ts_mee_mei']);
								vue.$set(vue, 'tc_mee_jun', response['tc_mee_jun']);
								vue.$set(vue, 'ts_mee_jun', response['ts_mee_jun']);
								vue.$set(vue, 'tc_mee_jul', response['tc_mee_jul']);
								vue.$set(vue, 'ts_mee_jul', response['ts_mee_jul']);
								vue.$set(vue, 'tc_mee_agu', response['tc_mee_agu']);
								vue.$set(vue, 'ts_mee_agu', response['ts_mee_agu']);
								vue.$set(vue, 'tc_mee_sep', response['tc_mee_sep']);
								vue.$set(vue, 'ts_mee_sep', response['ts_mee_sep']);
								vue.$set(vue, 'tc_mee_okt', response['tc_mee_okt']);
								vue.$set(vue, 'ts_mee_okt', response['ts_mee_okt']);
								vue.$set(vue, 'tc_mee_nov', response['tc_mee_nov']);
								vue.$set(vue, 'ts_mee_nov', response['ts_mee_nov']);
								vue.$set(vue, 'tc_mee_des', response['tc_mee_des']);
								vue.$set(vue, 'ts_mee_des', response['ts_mee_des']);

								vue.$set(vue, 'tc_poo_jan', response['tc_poo_jan']);
								vue.$set(vue, 'ts_poo_jan', response['ts_poo_jan']);
								vue.$set(vue, 'tc_poo_feb', response['tc_poo_feb']);
								vue.$set(vue, 'ts_poo_feb', response['ts_poo_feb']);
								vue.$set(vue, 'tc_poo_mar', response['tc_poo_mar']);
								vue.$set(vue, 'ts_poo_mar', response['ts_poo_mar']);
								vue.$set(vue, 'tc_poo_apr', response['tc_poo_apr']);
								vue.$set(vue, 'ts_poo_apr', response['ts_poo_apr']);
								vue.$set(vue, 'tc_poo_mei', response['tc_poo_mei']);
								vue.$set(vue, 'ts_poo_mei', response['ts_poo_mei']);
								vue.$set(vue, 'tc_poo_jun', response['tc_poo_jun']);
								vue.$set(vue, 'ts_poo_jun', response['ts_poo_jun']);
								vue.$set(vue, 'tc_poo_jul', response['tc_poo_jul']);
								vue.$set(vue, 'ts_poo_jul', response['ts_poo_jul']);
								vue.$set(vue, 'tc_poo_agu', response['tc_poo_agu']);
								vue.$set(vue, 'ts_poo_agu', response['ts_poo_agu']);
								vue.$set(vue, 'tc_poo_sep', response['tc_poo_sep']);
								vue.$set(vue, 'ts_poo_sep', response['ts_poo_sep']);
								vue.$set(vue, 'tc_poo_okt', response['tc_poo_okt']);
								vue.$set(vue, 'ts_poo_okt', response['ts_poo_okt']);
								vue.$set(vue, 'tc_poo_nov', response['tc_poo_nov']);
								vue.$set(vue, 'ts_poo_nov', response['ts_poo_nov']);
								vue.$set(vue, 'tc_poo_des', response['tc_poo_des']);
								vue.$set(vue, 'ts_poo_des', response['ts_poo_des']);

								vue.$set(vue, 'tc_res_jan', response['tc_res_jan']);
								vue.$set(vue, 'ts_res_jan', response['ts_res_jan']);
								vue.$set(vue, 'tc_res_feb', response['tc_res_feb']);
								vue.$set(vue, 'ts_res_feb', response['ts_res_feb']);
								vue.$set(vue, 'tc_res_mar', response['tc_res_mar']);
								vue.$set(vue, 'ts_res_mar', response['ts_res_mar']);
								vue.$set(vue, 'tc_res_apr', response['tc_res_apr']);
								vue.$set(vue, 'ts_res_apr', response['ts_res_apr']);
								vue.$set(vue, 'tc_res_mei', response['tc_res_mei']);
								vue.$set(vue, 'ts_res_mei', response['ts_res_mei']);
								vue.$set(vue, 'tc_res_jun', response['tc_res_jun']);
								vue.$set(vue, 'ts_res_jun', response['ts_res_jun']);
								vue.$set(vue, 'tc_res_jul', response['tc_res_jul']);
								vue.$set(vue, 'ts_res_jul', response['ts_res_jul']);
								vue.$set(vue, 'tc_res_agu', response['tc_res_agu']);
								vue.$set(vue, 'ts_res_agu', response['ts_res_agu']);
								vue.$set(vue, 'tc_res_sep', response['tc_res_sep']);
								vue.$set(vue, 'ts_res_sep', response['ts_res_sep']);
								vue.$set(vue, 'tc_res_okt', response['tc_res_okt']);
								vue.$set(vue, 'ts_res_okt', response['ts_res_okt']);
								vue.$set(vue, 'tc_res_nov', response['tc_res_nov']);
								vue.$set(vue, 'ts_res_nov', response['ts_res_nov']);
								vue.$set(vue, 'tc_res_des', response['tc_res_des']);
								vue.$set(vue, 'ts_res_des', response['ts_res_des']);

								vue.$set(vue, 'tc_mis_jan', response['tc_mis_jan']);
								vue.$set(vue, 'ts_mis_jan', response['ts_mis_jan']);
								vue.$set(vue, 'tc_mis_feb', response['tc_mis_feb']);
								vue.$set(vue, 'ts_mis_feb', response['ts_mis_feb']);
								vue.$set(vue, 'tc_mis_mar', response['tc_mis_mar']);
								vue.$set(vue, 'ts_mis_mar', response['ts_mis_mar']);
								vue.$set(vue, 'tc_mis_apr', response['tc_mis_apr']);
								vue.$set(vue, 'ts_mis_apr', response['ts_mis_apr']);
								vue.$set(vue, 'tc_mis_mei', response['tc_mis_mei']);
								vue.$set(vue, 'ts_mis_mei', response['ts_mis_mei']);
								vue.$set(vue, 'tc_mis_jun', response['tc_mis_jun']);
								vue.$set(vue, 'ts_mis_jun', response['ts_mis_jun']);
								vue.$set(vue, 'tc_mis_jul', response['tc_mis_jul']);
								vue.$set(vue, 'ts_mis_jul', response['ts_mis_jul']);
								vue.$set(vue, 'tc_mis_agu', response['tc_mis_agu']);
								vue.$set(vue, 'ts_mis_agu', response['ts_mis_agu']);
								vue.$set(vue, 'tc_mis_sep', response['tc_mis_sep']);
								vue.$set(vue, 'ts_mis_sep', response['ts_mis_sep']);
								vue.$set(vue, 'tc_mis_okt', response['tc_mis_okt']);
								vue.$set(vue, 'ts_mis_okt', response['ts_mis_okt']);
								vue.$set(vue, 'tc_mis_nov', response['tc_mis_nov']);
								vue.$set(vue, 'ts_mis_nov', response['ts_mis_nov']);
								vue.$set(vue, 'tc_mis_des', response['tc_mis_des']);
								vue.$set(vue, 'ts_mis_des', response['ts_mis_des']);

								vue.$set(vue, 'tc_swi_jan', response['tc_swi_jan']);
								vue.$set(vue, 'ts_swi_jan', response['ts_swi_jan']);
								vue.$set(vue, 'tc_swi_feb', response['tc_swi_feb']);
								vue.$set(vue, 'ts_swi_feb', response['ts_swi_feb']);
								vue.$set(vue, 'tc_swi_mar', response['tc_swi_mar']);
								vue.$set(vue, 'ts_swi_mar', response['ts_swi_mar']);
								vue.$set(vue, 'tc_swi_apr', response['tc_swi_apr']);
								vue.$set(vue, 'ts_swi_apr', response['ts_swi_apr']);
								vue.$set(vue, 'tc_swi_mei', response['tc_swi_mei']);
								vue.$set(vue, 'ts_swi_mei', response['ts_swi_mei']);
								vue.$set(vue, 'tc_swi_jun', response['tc_swi_jun']);
								vue.$set(vue, 'ts_swi_jun', response['ts_swi_jun']);
								vue.$set(vue, 'tc_swi_jul', response['tc_swi_jul']);
								vue.$set(vue, 'ts_swi_jul', response['ts_swi_jul']);
								vue.$set(vue, 'tc_swi_agu', response['tc_swi_agu']);
								vue.$set(vue, 'ts_swi_agu', response['ts_swi_agu']);
								vue.$set(vue, 'tc_swi_sep', response['tc_swi_sep']);
								vue.$set(vue, 'ts_swi_sep', response['ts_swi_sep']);
								vue.$set(vue, 'tc_swi_okt', response['tc_swi_okt']);
								vue.$set(vue, 'ts_swi_okt', response['ts_swi_okt']);
								vue.$set(vue, 'tc_swi_nov', response['tc_swi_nov']);
								vue.$set(vue, 'ts_swi_nov', response['ts_swi_nov']);
								vue.$set(vue, 'tc_swi_des', response['tc_swi_des']);
								vue.$set(vue, 'ts_swi_des', response['ts_swi_des']);

								vue.$set(vue, 'ttl_jan', response['ttl_jan']);
								vue.$set(vue, 'ttl_feb', response['ttl_feb']);
								vue.$set(vue, 'ttl_mar', response['ttl_mar']);
								vue.$set(vue, 'ttl_apr', response['ttl_apr']);
								vue.$set(vue, 'ttl_mei', response['ttl_mei']);
								vue.$set(vue, 'ttl_jun', response['ttl_jun']);
								vue.$set(vue, 'ttl_jul', response['ttl_jul']);
								vue.$set(vue, 'ttl_agu', response['ttl_agu']);
								vue.$set(vue, 'ttl_sep', response['ttl_sep']);
								vue.$set(vue, 'ttl_okt', response['ttl_okt']);
								vue.$set(vue, 'ttl_nov', response['ttl_nov']);
								vue.$set(vue, 'ttl_des', response['ttl_des']);

								$(that.elVue).unblock();
							}
						});
					},

					exportExcel: function () {
						var vue = this;
						vue.getData();

						setTimeout(function () {
							window.location = window.APP.siteUrl + 'adm/R_recaps/export_excel';
						}, 500);
					}
				},
				mounted: function () {

				}
			});

		},

	}

})(jQuery);
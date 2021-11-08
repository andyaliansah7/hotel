/**
 * Javascript Datetime
 *
 * @author  Hikmahtiar <hikmahtiar.cool@gmail.com>
 */
window.DATETIME = (function($) {
	return {
		elDate : '.app-date',
		elTime : '.app-time',
		elWeek : '.week-options',
		elWeekNumber : '.week-number',
		elDateStart : '.date-start',
		elDateFinish : '.date-finish',
		elInputDateAdv : '.date-input-advance',
		elInputDate : '.date-input',
		elInputDate2 : '.date-input2',
		elInputTime : '.time-input',
		elInputYear : '.date-year',

		init : function() {
			var parentThis = this;

			parentThis.handleDate();
			parentThis.handleTime();
		},

		handleDate : function() {
			var parentThis = this;

			var months = [
				'Januari',
				'Februari',
				'Maret',
				'April',
				'Mei',
				'Juni',
				'Juli',
				'Agustus',
				'September',
				'Oktober',
				'November',
				'Desember'
			];

			var myDays = [
				'Minggu',
				'Senin',
				'Selasa',
				'Rabu',
				'Kamis',
				'Jum&#39;at',
				'Sabtu'
			];

			var date = new Date();
			var day = date.getDate();
			var month = date.getMonth();
			var thisDay = date.getDay(), thisDay = myDays[thisDay];
			var yy = date.getYear();
			var year = (yy < 1000) ? yy + 1900 : yy;

			$(parentThis.elDate).html(thisDay + ', ' + day + ' ' + months[month] + ' ' + year);
		},

		handleTime : function() {
			var parentThis = this;

			function startTime() {
				var today = new Date(),
				curr_hour = today.getHours(),
				curr_min = today.getMinutes(),
				curr_sec = today.getSeconds();
				curr_hour = checkTime(curr_hour);
				curr_min = checkTime(curr_min);
				curr_sec = checkTime(curr_sec);

				$(parentThis.elTime).html(curr_hour+":"+curr_min+":"+curr_sec);
			}

			function checkTime(i) {
				if (i<10) {
					i = "0" + i;
				}
				return i;
			}

			setInterval(startTime, 500);
		},

		handleWeek: function() {
			var parentThis = this;

			var splt = $(parentThis.elWeek).val().split('-');
			$(parentThis.elDateStart).val(splt[0]);
			$(parentThis.elDateFinish).val(splt[1]);

			var selectedText = $(parentThis.elWeek + ' option:selected').text();
			var splt2 = selectedText.split('-');
			$(parentThis.elWeekNumber).val(splt2[0].trim());


			$(parentThis.elWeek).change(function() {
				var splt = this.value.split('-');
				$(parentThis.elDateStart).val(splt[0]);
				$(parentThis.elDateFinish).val(splt[1]);

				var selectedText = $(parentThis.elWeek + ' option:selected').text();
				var splt2 = selectedText.split('-');
				$(parentThis.elWeekNumber).val(splt2[0].trim());
			});
		},

		initTimePicker : function() {
			var parentThis = this;

			$(parentThis.elInputTime).datetimepicker({
				date: false,
				format: 'HH:mm'
			});
		},

		initDatePicker: function() {
			var parentThis = this;

			$(parentThis.elInputDate).datetimepicker({
				weekStart: 0,
				time: false,
				locale: "id",
				format: "DD/MM/YYYY"
			});

			$(parentThis.elInputDate2).datetimepicker({
				weekStart: 0,
				time: false,
				locale: "id",
				format: "DD/MM/YYYY"
			});

		},

		initDatePickerAdvance: function () {
			var parentThis = this;

			$(parentThis.elInputDateAdv).each(function () {
				$(this).datetimepicker();
			});

		},

		initDateYear: function() {
			var parentThis = this;

			$(parentThis.elInputYear).datetimepicker({
				weekStart : false,
				time : false,
				year : true,
				format : 'YYYY'
			});
		},

		// datepicker with button
		initDatePickerWithButton : function(elButton, elInputForDate) {
			var parentThis = this;

			$(elButton).datetimepicker({
				weekStart: 0,
				time: false,
				format: 'DD/MM/YYYY'
			}).on('change', function (e, date) {
				$(elInputForDate).val($(e.target).attr('value'));
			});
		}

	}
})(jQuery);

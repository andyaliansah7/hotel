/**
 * Javascript Form
 *
 * @author Hikmahtiar <hikmahtiar.cool@gmail.com>
 */

window.FORM = (function($) {
	return {
		// menampilkan notifikasi/alert dengan plugin sweetalert
		showNotification: function(message, status) {
			toastr.success(message)
		},

		handleEditModal : function(elForm, elEdit, elModal, elModalContent, elBtnModalClose, elDatatable) {
			var parentThis = this;
			// edit data
			$(document).on('click', elEdit, function(e) {
				e.preventDefault();

				// show modal and request url (in href link edit)
				$(elModal).modal('show');
				$(elModalContent).load($(this).attr('href'), function() {
					parentThis.handleForm(elForm, elBtnModalClose, elDatatable);
					$('.select2').select2();
				});
			});
		},

		handleForm : function(elForm, elBtnModalClose, elDatatable) {
			var parentThis = this;

			// $(elForm).validate();

			$(elForm).validate({
				rules: {
					email: {
						// required: true,
						// email: true,
					},
					password: {
						required: true,
						minlength: 5
					},
					terms: {
						required: true
					},
				},
				messages: {
					email: {
						// required: "Please enter a email address",
						email: "Please enter a vaild email address"
					},
					password: {
						required: "Please provide a password",
						minlength: "Your password must be at least 5 characters long"
					},
					terms: "Please accept our terms"
				},
				errorElement: 'span',
				errorPlacement: function (error, element) {
					error.addClass('invalid-feedback');
					element.closest('.form-group').append(error);
				},
				highlight: function (element, errorClass, validClass) {
					$(element).addClass('is-invalid');
				},
				unhighlight: function (element, errorClass, validClass) {
					$(element).removeClass('is-invalid');
				}
			});

			$(elForm).ajaxForm({
				dataType : 'json',
				beforeSend : function() {
					$(elForm).block({
						message: '<h6>Harap tunggu..</h6>'
		            });
				},
				success : function(response) {

					var elSelect2 = '.select2';
					var elReadOnly = '.readonly-edit';

					$(elForm).unblock();

					// jika response success
					if(response.status == 'success')
					{
						// reload datatable and notification
						elDatatable.ajax.reload();
						parentThis.showNotification(response.message, response.status);

						// jika id == new, reset form
						// id = close , close modal
						// id = save , edit data 
						if(response.id == 'new') {
							$(elForm).clearForm();
							$(elSelect2).val('').trigger('change');
							$(elForm).find('input[name=id]').val('new');
							$(elForm).find(elReadOnly).removeAttr('readonly');
						}

						if(response.id == 'close') {
							$(elBtnModalClose).click();
						}

						if(response.id != 'close' && response.id != 'new') {
							$(elForm).find('input[name=id]').val(response.id);
							$(elForm).find(elReadOnly).attr('readonly', 'readonly');
						}
					}
				}
			});
		},

		handleCheckField: function(elDivID, elResult, elUrl, elBtn)
		{
			var typingTimer;
			var doneTypingInterval = 1000;
			var inputKeyup = $(elDivID);
			$(inputKeyup).keyup(function() {
				var Ide = this.value;
				clearTimeout(typingTimer);
			    if (Ide != "") {
			        typingTimer = setTimeout(doneTyping, doneTypingInterval);
			    }else{
			    	$(elResult).html('');
			    }

				function doneTyping () {
					check_id(Ide);
				}
			});
			
			function check_id(Ide)
			{			
				$.ajax({
					type : "POST",
					data : {
						'id' : Ide
					},
					url : elUrl,
					dataType : "JSON",
					beforeSend : function(result)
					{
						$(elResult).html('<i class="glyphicon fa fa-spinner fa-spin"> </i>');
					},
					success : function(result)
					{
						if (result.status === false) {
							$(elResult).html('<i class="glyphicon glyphicon-ok text-green"> ID Tersedia</i>');
							$(elBtn).attr('disabled', false);
						}else if (result.status === true) {
							$(elBtn).attr('disabled', true);
							$(elResult).html('<i class="glyphicon glyphicon-remove text-red"> ID Tidak Tersedia</i>');
						}
					}
				});
			}
		},

		handleCheckField2 : function(elDivID, elResult, elUrl, elBtn, stringSuccess, stringFailure)
		{
			var typingTimer;
			var doneTypingInterval = 1000;
			var inputKeyup = $(elDivID);
			$(inputKeyup).keyup(function() {
				var Ide = this.value;
				clearTimeout(typingTimer);
			    if (Ide != "") {
			        typingTimer = setTimeout(doneTyping, doneTypingInterval);
			    }else{
			    	$(elResult).html('');
			    }

				function doneTyping () {
					check_id(Ide);
				}
			});
			
			function check_id(Ide)
			{			
				$.ajax({
					type : "POST",
					data : {
						'id' : Ide
					},
					url : elUrl,
					dataType : "JSON",
					beforeSend : function(result)
					{
						$(elResult).html('<i class="glyphicon fa fa-spinner fa-spin"> </i>');
					},
					success : function(result)
					{
						if (result.status === false) {
							$(elResult).html('<i class="glyphicon glyphicon-ok text-green"> ' + stringSuccess + '</i>');
							// $(elDivID).addClass('is-valid');
							$(elBtn).attr('disabled', false);
						}else if (result.status === true) {
							$(elBtn).attr('disabled', true);
							$(elDivID).addClass('is-invalid');
							$(elResult).html('<i class="glyphicon glyphicon-remove text-red"> '+stringFailure+'</i>');
						}
					}
				});
			}
		},

		handleCheckFieldIdentityNumber : function(Url, elSelectID, elTextID1, elTextID2, elTextID3, elTextID4, elAddIdentity)
		{
			var inputidentity = $(elSelectID);
			$(inputidentity).change(function (e) {
				var id = $(e.target).val();
				
				// $(elAddIdentity).attr('hidden', false);
				// if(id != 'register'){
				// 	$(elAddIdentity).attr('hidden', true);
				// }

				$.ajax({
					type: "POST",
					url: Url,
					dataType: "JSON",
					data: {
						id: id
					},
					success: function (data) {

						// if(data['status'] == true){
						// 	$(elTextID1).attr('readonly', true);
						// 	$(elTextID2).attr('readonly', true);
						// 	$(elTextID3).attr('readonly', true);
						// }else{
						// 	$(elTextID1).attr('readonly', false);
						// 	$(elTextID2).attr('readonly', false);
						// 	$(elTextID3).attr('readonly', false);
						// }

						$($(elAddIdentity)).val(data['id_number']);
						$($(elTextID1)).val(data['name']);
						$($(elTextID2)).val(data['phone']);
						$($(elTextID3)).val(data['address']);
						// $(elTextID4).val(data['name']);
						
					},
				});			
			});
		},

		handleNestedSelect : function(Url, elSelectID1, elSelectID2, elAdditionalID1, elAdditionalID2, elAdditionalID3, elAdditionalID4, elAdditionalID5, elAdditionalID6, error_message)
		{
			var select1 = $(elSelectID1);
			var select2 = $(elSelectID2);
			var elAdditional1 = $(elAdditionalID1);
			var elAdditional2 = $(elAdditionalID2);
			var elAdditional3 = $(elAdditionalID3);
			var elAdditional4 = $(elAdditionalID4);
			var elAdditional5 = $(elAdditionalID5);
			var elAdditional6 = $(elAdditionalID6);

			var today = new Date();
			var dd = String(today.getDate()).padStart(2, '0');
			var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
			var yyyy = today.getFullYear();

			today = yyyy + '-' + mm + '-' + dd;

			$(select1).change(function (e) {
				var id = $(e.target).val();
				$(elAdditional3).val('');

				if(id != ''){
					
					if(elAdditional1.val() == '' || elAdditional2.val() == ''){
						$(select1).val('');
						toastr.error(error_message);
					/* }else if (elAdditional1.val() < today || elAdditional1.val() > elAdditional2.val()){ */
					}else if (elAdditional1.val() > elAdditional2.val()){
						$(select1).val('');
						toastr.error(error_message);
					}else{
						$.ajax({
							type: "POST",
							url: Url,
							dataType: "JSON",
							data: {
								room_type_id: id,
								date_in: elAdditional1.val(),
								date_out: elAdditional2.val(),
								id: $('#id').val()
							},
							success: function (data) {
								
								$(select2).html("<option value=''>- Pilih -</option>");
								$.each(data['room_available'], function(i, d) {
									$(select2).append('<option value="' + d.room_id + '">' + d.room_number + '</option>');
								});

								// const oneDay     = 24 * 60 * 60 * 1000;
								// const firstDate  = new Date(elAdditional1.val());
								// const secondDate = new Date(elAdditional2.val());

								// const diffDays = Math.round(Math.abs((firstDate - secondDate) / oneDay));
								// console.log(firstDate);
								var guest_group_discount = 0;
								var price_per_night = 0;
								var interval = 0;
								var price_total   = 0;
								var price_total_a = 0;
								var price_total_b = 0;
								
								guest_group_discount = $("#guest_group_discount").val();
								price_per_night      = data['price_per_night'];
								interval             = data['interval'];
								price_total          = data['price_total'];

								price_total_a = (price_total * (guest_group_discount/100));
								price_total_b = (price_total - price_total_a);

								$(elAdditional3).val(Math.ceil(price_per_night));
								$(elAdditional4).val(Math.ceil(price_total));
								$(elAdditional5).val(Math.ceil(interval));
								$(elAdditional6).val(Math.ceil(price_total_b));

								document.getElementById("interval").innerHTML = data['interval'] +' malam';
								document.getElementById("day-addon2").innerHTML = data['interval'] +' malam';

							},
						});	
					}	
					
				}

			});
		},

		handleNestedSelectCheckin : function(Url, elSelectID1, elSelectID2, elAdditionalID1, elAdditionalID2, elAdditionalID3, elAdditionalID4, elAdditionalID5, elAdditionalID6, error_message)
		{
			var select1 = $(elSelectID1);
			var select2 = $(elSelectID2);
			var elAdditional1 = $(elAdditionalID1);
			var elAdditional2 = $(elAdditionalID2);
			var elAdditional3 = $(elAdditionalID3);
			var elAdditional4 = $(elAdditionalID4);
			var elAdditional5 = $(elAdditionalID5);
			var elAdditional6 = $(elAdditionalID6);

			var today = new Date();
			var dd = String(today.getDate()).padStart(2, '0');
			var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
			var yyyy = today.getFullYear();

			today = yyyy + '-' + mm + '-' + dd;

			$(select1).change(function (e) {
				var id = $(e.target).val();
				$(elAdditional3).val('');

				if(id != ''){
					
					if(elAdditional1.val() == '' || elAdditional2.val() == ''){
						$(select1).val('');
						toastr.error(error_message);
					/* }else if (elAdditional1.val() < today || elAdditional1.val() > elAdditional2.val()){ */
					}else if (elAdditional1.val() > elAdditional2.val()){
						$(select1).val('');
						toastr.error(error_message);
					}else{
						$.ajax({
							type: "POST",
							url: Url,
							dataType: "JSON",
							data: {
								room_type_id: id,
								date_in: elAdditional1.val(),
								date_out: elAdditional2.val(),
								id: $('#id').val()
							},
							success: function (data) {
								
								$(select2).html("<option value=''>- Pilih -</option>");
								$.each(data['room_available'], function(i, d) {
									$(select2).append('<option value="' + d.room_id + '">' + d.room_number + '</option>');
								});

								// const oneDay     = 24 * 60 * 60 * 1000;
								// const firstDate  = new Date(elAdditional1.val());
								// const secondDate = new Date(elAdditional2.val());

								// const diffDays = Math.round(Math.abs((firstDate - secondDate) / oneDay));
								// console.log(firstDate);
								var guest_group_discount = 0;
								var price_per_night = 0;
								var interval = 0;
								var price_total   = 0;
								var price_total_a = 0;
								var price_total_b = 0;
								
								guest_group_discount = $("#guest_group_discount").val();
								price_per_night      = data['price_per_night'];
								interval             = data['interval'];
								price_total          = data['price_total'];

								price_total_a = (price_total * (guest_group_discount/100));
								price_total_b = (price_total - price_total_a);

								$(elAdditional3).val(Math.ceil(price_per_night));
								$(elAdditional4).val(Math.ceil(price_total));
								$(elAdditional5).val(Math.ceil(interval));
								$(elAdditional6).val(Math.ceil(price_total_b));

									$("#weekday_price").val(Math.ceil(data['weekday_price']));
									$("#weekend_price").val(Math.ceil(data['weekend_price']));

								document.getElementById("interval").innerHTML = data['interval'] +' malam';
								document.getElementById("day-addon2").innerHTML = data['interval'] +' malam';

							},
						});	
					}	
					
				}

			});
		}

	}
})(jQuery);
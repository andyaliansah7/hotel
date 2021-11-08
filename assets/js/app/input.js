/**
 * Javascript INput
 *
 * @author Hikmahtiar <hikmahtiar.cool@gmail.com>
 */

window.INPUT = (function($) {
	return {
		elSelect2 : '.select2',
		elSelect2AddNew : '.select2addnew',
		elInputNumber : '.number-mask',
		elDate : '.date-mask',
		elCurrency : '.currency-mask',

		numberingOnly: function(inputEl) {
			$(inputEl).keydown(function (event) {


	            if (event.shiftKey == true) {
	                event.preventDefault();
	            }

	            if ((event.keyCode >= 48 && event.keyCode <= 57) || (event.keyCode >= 96 && event.keyCode <= 105) || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 37 || event.keyCode == 39 || event.keyCode == 46 || event.keyCode == 190) {

	            } else {
	                event.preventDefault();
	            }
	            
	            if($(this).val().indexOf('.') !== -1 && event.keyCode == 190)
	                event.preventDefault();

	        });
		},

		numberingKeyDown: function(event, val) {

            if (event.shiftKey == true) {
                //event.preventDefault();
            }

            if (
            	(event.keyCode >= 48 && event.keyCode <= 57) || 
            	(event.keyCode >= 96 && event.keyCode <= 105) || 
            	event.keyCode == 8 || 
            	event.keyCode == 9 || 
            	event.keyCode == 37 || 
            	event.keyCode == 39 || 
            	event.keyCode == 46 || 
            	event.keyCode == 190 || 
            	event.keyCode == 16 ||
            	event.keyCode == 17
            ) {

            } else {
                event.preventDefault();
            }
            
            if($(val).val().indexOf('.') !== -1 && event.keyCode == 190)
                event.preventDefault();

		},

		moveInputInTable: function(event, val) {
			var $this = $(val);
		    var $tr = $this.closest("tr");
		    var id = 'test';
		    
		    if(event.keyCode == 38){
		        $tr.prev().find('input[id^='+id+']').focus();
		    }
		    else if(event.keyCode == 40)
		    {
		       $tr.next().find("input[id^='"+id+"']").focus();
		    }
		},

		handleCheckboxAll : function(elParentCheckBox, elSubCheckbox) {

			$(elParentCheckBox).click(function(){
				checkboxes = $(elSubCheckbox);
				for(var i=0, n=checkboxes.length;i<n;i++) {
					checkboxes[i].checked = $(elParentCheckBox).is(':checked');
				}
			});
		},

		handleSelect2 : function() {
			var parentThis = this;
			
			$(function () {
				
				$(parentThis.elSelect2).select2({
					theme: 'bootstrap4'
				})

			})
		},

		handleSelect2AddNew : function() {
			var parentThis = this;
			
			$(function () {
				
				$(parentThis.elSelect2AddNew).select2({
					theme: 'bootstrap4',
					tags: true,

					createTag: function(params) {
						var term = $.trim(params.term);
						var count = 0
						var existsVar = false;
						//check if there is any option already
						if($('#keywords option').length > 0){
							$('#keywords option').each(function(){
								if ($(this).text().toUpperCase() == term.toUpperCase()) {
									existsVar = true
									return false;
								}else{
									existsVar = false
								}
							});
							if(existsVar){
								return null;
							}
							return {
								id: params.term,
								text: params.term,
								newTag: true
							}
						}
						//since select has 0 options, add new without comparing
						else{
							return {
								id: params.term + ' [Add New]',
								text: params.term + ' [Add New]',
								newTag: true
							}
						}
					},
				})

			})
		},

		handleMaskNumber : function() {
			var parentThis = this;
			$(parentThis.elInputNumber).inputmask("numeric");
		},

		handleMaskDate : function() {
			var parentThis = this;
			$(parentThis.elDate).inputmask("date");
		}, 

		handleMaskCurrency : function() {
			var parentThis = this;
			$(parentThis.elCurrency).inputmask("decimal",{
				radixPoint:".", 
				groupSeparator: ",", 
				digits: 2,
				autoGroup: true,
			});
		},

		/**
		 * Handle Bahan Kimia
		 *
		 * Buat 2 input text (text dan hidden)
		 * Buat 1 span or div untuk menampilkan stringnya
		 */
		handleBahanKimia : function(elInput, elInputHidden, elText, type = 'sub') {

			// ketka mengetik
			$(elInput).keyup(function() {
				var _val = this.value;

				var array = Array.from(_val);
        		var txt = '';

        		// looping kata2 yg diketik menjadi satuan
        		// dan di replace jika integer
				for(var i in array) {

					var string = array[i];

					if(isInt(string)) {
						txt += '<'+type+'>'+array[i]+'</'+type+'>';
					}
					else {
						txt += array[i];
					}
				}

				$(elInputHidden).val(txt);
				$(elText).html(txt);
			});


			function isInt(value) {
		        return !isNaN(value) && 
		            parseInt(Number(value)) == value && 
		        	!isNaN(parseInt(value, 10));
	        }
		}
	}
})(jQuery);
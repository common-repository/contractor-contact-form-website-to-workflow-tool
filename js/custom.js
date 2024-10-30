jQuery(function($) {

	$(window).on('load', function() {
		setTimeout(function() {
			$('#jp-message').slideUp(800);
		}, 4000);
	});

	var containerWidth = $(".customer-page-container").width();
	if(containerWidth < 350) {
		$(".customer-page-container").addClass("form-mobile-view");
	} else {
		$(".customer-page-container").removeClass("form-mobile-view");
	}
	
	jQuery.validator.addMethod("alphanumeric", function(value, element) {
	    return this.optional(element) || /^[a-z0-9\\-]+$/i.test(value);
	}, "Only letters and numbers are allowed");

	// validate signup form on keyup and submit
	$("#jobprogrssCustomerSignupForm").validate({
		email:true,
		rules: {
			'first_name': {
				"required": true	
			},
			'last_name': {
				'required': true
			},
			'address[zip]': {
                alphanumeric: true
			},
			'billing[zip]': {
                alphanumeric: true
			},
			'cpatchaTextBox': {
				required: true
			}
		},
		messages: {
			first_name: "Please enter the first name.",
			last_name: "Please enter the last name.",
			cpatchaTextBox: "Correct captcha is required. Click the refresh icon to generate a new one"
		},
		errorPlacement: function(error, element) {
			error.insertAfter( element);
		},
		onkeyup: false
	});
	$.validator.addClassRules("jps-required-input", {
		required: true
  	});
	if($('.jps-comm-name .jps-comm-fname').hasClass('jps-required-input')) {
		$.validator.addClassRules("jps-comm-fname", {
			required: true
		});
	} else {
		$.validator.addClassRules("jps-comm-fname", {
			required: function(element) {
				return $("input[name='contact[0][last_name]']").val()!="" && $('input[name="jp_customer_type2"]').is(':checked') ;
			}
		});
	}

	if($('.jps-comm-name .jps-comm-lname').hasClass('jps-required-input')) {
		$.validator.addClassRules("jps-comm-lname", {
			required: true
		});
	} else {
		$.validator.addClassRules("jps-comm-lname", {
			required: function(element) {
				return $("input[name='contact[0][first_name]']").val()!="" && $('input[name="jp_customer_type2"]').is(':checked');
			}
		});
	}
	 
	$('#jobprogrssCustomerSignupForm').on('submit', function() {
	    // check validation
	    if ($("#cpatchaTextBox").val() != code) {
	    	$('#cpatchaTextBox-error').css('display', 'none');
	    	$('.captcha-invalid').text("Correct captcha is required. Click the refresh icon to generate a new one");
		    return false;
		}
	});

	// default customer type 1 selected first type is commercial
	$("input:checkbox[name='jp_customer_type1']").prop("checked", true);
	$('.billing-address-container').hide();
	
	$(".jobprogress-customer-type").on('change',function(){

		var all_customer_types = "input:checkbox[class='jobprogress-customer-type']";
		if($(this).attr('name') === 'jp_customer_type2') {
			$('.jobprogress-residential-type').hide();
			$('.jobprogress-commercial-type').show();
			$('#first_name-error').hide();
			$('#last_name-error').hide();
			$('#company_name_commercial-error').show();
		} else {
			$('.jobprogress-commercial-type').hide();
			$('.jobprogress-residential-type').show();
			$('#first_name-error').show();
			$('#last_name-error').show();
			$('#company_name_commercial-error').hide();
		}
		$(all_customer_types).prop("checked", false);
		$(this).prop("checked", true);
	});

	if($('.jps-customer-form-wrap').hasClass('jps-form-ui')) {
		$(".select2").select2({
			minimumResultsForSearch: Infinity
		}).on('change', function (e) {
			var input = $(this).parent().find('.extension-field');
		});
	
		$(".main-phone").select2({
			minimumResultsForSearch: Infinity,
			dropdownParent: $(".main-phone-container")
		}).on('change', function (e) {
			var input = $(this).parent().find('.extension-field');
		});
	
		$(".billing-state").select2({
			minimumResultsForSearch: Infinity,
			dropdownParent: $(".billing-state-container")
		}).on('change', function (e) {
			var input = $(this).parent().find('.extension-field');
		});
	
		$(".billing-country").select2({
			minimumResultsForSearch: Infinity,
			dropdownParent: $(".billing-country-container")
		}).on('change', function (e) {
			var input = $(this).parent().find('.extension-field');
		});
	
		$(".state-list").select2({
			minimumResultsForSearch: Infinity,
			dropdownParent: $(".state-list-container")
		}).on('change', function (e) {
			var input = $(this).parent().find('.extension-field');
		});
	
		$(".country-list").select2({
			minimumResultsForSearch: Infinity,
			dropdownParent: $(".country-list-container")
		}).on('change', function (e) {
			var input = $(this).parent().find('.extension-field');
		});
	
		$(".jp-trade").select2({
			placeholder: "Select Trade Type",
			dropdownParent: $(".jp-trade-container")
		}).on('change', function (e) {
			var input = $(this).parent().find('.extension-field');
	
			if($.inArray('24', $(this).val()) > -1 ) {
				$('.other-trade-note-container').show();
			} else {
				$('.other-trade-note-container').hide();
			}
			
		});
		
		$(".jp-referral").select2({
			placeholder: "Select Referral",
			dropdownParent: $(".jp-referral-container")
		}).on("select2:select", function(e) {
			if($(this).val() == 'other') {
				$('.referred-by-note-block').show();
			} else {
				$('.referred-by-note-block').hide();
			}
		});
	}
	if(!$('.jps-customer-form-wrap').hasClass('jps-form-ui')) {
		$(".jp-trade").on('change', function (e) {
			if($.inArray('24', $(this).val()) > -1 ) {
				$('.other-trade-note-container').show();
			} else {
				$('.other-trade-note-container').hide();
			}
			
		});
		$(".jp-referral").on("change", function(e) {
			if($(this).val() == 'other') {
				$('.referred-by-note-block').show();
			} else {
				$('.referred-by-note-block').hide();
			}
		});
	}
	function phoneInputMask() {
		$('.mask-select').mask("(000) 000-0000", {placeholder: "(xxx) xxx-xxxx"});
	}
	phoneInputMask();
	$('.form-combine-select input').focus(function(){
		$(this).parent().addClass('active');
	}).focusout(function(){
	  $(this).parent().removeClass('active');
	});

	//additional email
	
	_.templateSettings = {
	  interpolate: /\{\{(.+?)\}\}/g
	};

	// var template = _.template($('.billing-address').html());
	// $("input:checkbox[name='same_as_customer_address']").after(template);

	var y = 1;
	$('body').delegate('.start-additional-emails', 'click', function(e) {
		
		if($('.additional-emails').length <= 4) {

			var template = _.template($('.additional-email').html());

			$('.additional-emails').last().after(template({
				index : y,
				className: 'jp-email-' + y
			}));

			$('.additional-emails').find('.start-additional-emails').addClass('hideAddBtn');
		}
		y++;
	});
	
	//remove additional email
	$('body').delegate('.additional-email-remove', 'click', function(e) {
		$(this).parents('.additional-emails').remove();
	});

	/**
	 * additional phones
	 */
	var x = 1;
	$('body').delegate('.add-additional-phone', 'click', function(e) {
		if($('.jobprogress-customer-phone').length <= 4) {
			var template = _.template($('.additional-phone').html());
			
			$('.jobprogress-customer-phone')
			.last()
			.after(
				template({
					index: x,
					className: 'jp-select-' + x
				})
			);
				
			if($('.jps-customer-form-wrap').hasClass('jps-form-ui')) {
				$('.jp-select-' + x).find('.select-input').select2({
					minimumResultsForSearch: Infinity,
					dropdownParent: $('.jp-select-' + x)
				}).on('change', function (e) {
					var input = $(this).parent().find('.extension-field');
				});
			}
			
			$('.jp-select-' + x).find('.add-additional-phone').addClass('hideAddBtn');
			
		}
		x++;
		phoneInputMask();
			
	});

	$('body').delegate('.remove-additional-phone', 'click', function(e) {
		$(this).parents('.jobprogress-customer-phone').remove();
	});

	$("input:checkbox[name='same_as_customer_address']").on('change', function(){
		$('.billing-address-container').show();
		if( ($("input:checkbox[name='same_as_customer_address']").prop("checked")) ){
			$('.billing-address-container').hide();
		}
	});

	/* captcha create code */
	var code;
	function createCaptcha() {
	  	//clear the contents of captcha div first 
	  	if(document.getElementById('jp_captcha')) {
		  	document.getElementById('jp_captcha').innerHTML = "";
		  	var charsArray =
		  	"0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ@!#$%^&*";
		  	var lengthOtp = 6;
		  	var captcha = [];
		  	for (var i = 0; i < lengthOtp; i++) {
		    	//below code will not allow Repetition of Characters
			    var index = Math.floor(Math.random() * charsArray.length + 1); //get the next character from the array
			    if (captcha.indexOf(charsArray[index]) == -1)
		      		captcha.push(charsArray[index]);
		    	else i--;
		  	}
		  	var canv = document.createElement("canvas");
		  	canv.id = "jp_captcha_img";
		  	canv.width = 100;
		  	canv.height = 50;
		  	var ctx = canv.getContext("2d");
		  	ctx.font = "25px Georgia";
		  	ctx.strokeText(captcha.join(""), 0, 30);
		  	//storing captcha so that can validate you can save it somewhere else according to your specific requirements
		  	code = captcha.join("");
		  	document.getElementById("jp_captcha").appendChild(canv); // adds the canvas to the body element
	  	}
	}
	createCaptcha();

	$('.refresh-captcha').click(function() {
		createCaptcha();
	})
	/* disable select if referral matched */
	$('select.jp-referral option').each(function() {
		if($(this).attr('selected')) {
			$('select.jp-referral').addClass('jps-field-disabled');
		}
	})
});

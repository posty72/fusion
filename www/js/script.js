

// Document ready function
$(document).ready(function(){

/*** IE Placeholder Fix **/

if($.browser.msie){
   $('input[placeholder]').each(function(){  
        
        var input = $(this);        
        
        $(input).val(input.attr('placeholder'));
                
        $(input).focus(function(){
             if (input.val() == input.attr('placeholder')) {
                 input.val('');
             }
        });

        
        $(input).blur(function(){
            if (input.val() == '' || input.val() == input.attr('placeholder')) {
                input.val(input.attr('placeholder'));
            }
        });
    });
};


/*** IE Placeholder Fix **/

if($.browser.msie){
   $('textarea[placeholder]').each(function(){  
        
        var input = $(this);        
        
        $(input).val(input.attr('placeholder'));
                
        $(input).focus(function(){
             if (input.val() == input.attr('placeholder')) {
                 input.val('');
             }
        });

        
        $(input).blur(function(){
            if (input.val() == '' || input.val() == input.attr('placeholder')) {
                input.val(input.attr('placeholder'));
            }
        });
    });
};




/*** Apply 'em' tag random word in an h2 ***/

	$("#pageContent article h1 a").each(function(){

//		var text = $(this).text();
		var origText = this.innerHTML;
		text = origText;
		var arrText = text.split(" ");

		var emText = arrText[Math.floor(Math.random()*arrText.length)];

		var emTextc = emText.replace(emText, '<em>'+emText+'</em>');
		//console.log(emText);
		origText = origText.replace(emText, emTextc);

		//console.log(origtext);

		this.innerHTML = origText;

	})


/*** Apply style to Webkit browsers **

	if($.browser.webkit && window.matchMedia("(min-width: 960px)").matches == true) {

		$("em").css("background-image", "url('graphics/purple_light.gif')");
		$("em").css("background-size", "50%");
		$("em").css("-webkit-background-clip", "text");
		$("em").css("background-clip", "text");
		$("em").css("color", "transparent");
	}
*/
/*** Fade in a background image on the body tag ***/

	$(window).resize(loadBackground);
	$(window).load(loadBackground);
	
	function loadBackground() {

		if(window.matchMedia("(min-width: 960px)").matches == true) {

			var c = new Image();

			$(c).ready().fadeIn(function(){
			    $("body").css("background-image", "url('graphics/home-1.jpg')");
			    $("body").css("background-attachment", "fixed");
			})

			c.src = "graphics/home-1.jpg";

		} else {
			$("body").css("background-image", "none");
		}

	}

/*** Contact Form validation ***/
	
	$('form #firstName').blur(validateName);
	$('form #lastName').blur(validateName);
	$('form #companyName').blur(validateCompanyName);
	$('form #email').blur(validateEmail);
	$('form #phoneNumber').blur(validatePhoneNumber);
	$('form #message').blur(validateMessage);


/* Contact Form Validation function */
	function validateCompanyName(){
		var cn = document.getElementById('companyName').value;
		if(cn.length < 2) {
			$('#cnameMsg').html('Enter your companies name');
		} else {
			$('#cnameMsg').html('');
		}
	}

	function validateMessage(){
		var m = document.getElementById('message').value;
		if(m.length < 2) {
			$('#msgMsg').html('Enter your message');
		} else {
			$('#msgMsg').html('');
		}
	}

	function validateEmail(){
		var e = document.getElementById('email').value;
		var ere = /^[a-zA-Z0-9._%-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
		if(!ere.test(e)) {
			$('#emailMsg').html('Enter a valid email address');
		}else {
			$('#emailMsg').html('');
		}
	}

	function validateName(){
		var fn = document.getElementById('firstName').value;
		var ln = document.getElementById('lastName').value;
		var valid = true;
		if(fn.length < 2) {
			valid = false;
		}
		if(ln.length < 2) {
			valid = false;
		}
		if(valid == false) {
			$('#nameMsg').html('Please enter your name');
		}else {
			$('#nameMsg').html('');
		}
	}

	function validatePhoneNumber(){
		var pn = document.getElementById('phoneNumber').value;
		if(isNaN(pn) || pn == '') {
			$('#phnoMsg').html('Enter a phone number');
		}else {
			$('#phnoMsg').html('');
		}
	}


/** Add Post **/


if(document.getElementById('postMediaType').value == 'null') {
	$('#postMediaText').hide();
	$('#postMediaFile').hide();
	$('#postMediaFile').prev().hide();
} else if(document.getElementById('postMediaType').value == 'none'){
	$('#postMediaText').hide();
	$('#postMediaFile').hide();
	$('#postMediaFile').prev().hide();
} else {
	if(document.getElementById('postMediaType').value == 'pdf' || document.getElementById('postMediaType').value == 'image') {
		$('#postMediaText').hide();
	} else if (document.getElementById('postMediaType').value = 'video') {
		$('#postMediaFile').hide();
		$('#postMediaFile').prev().hide();
	}
}
//console.log(document.getElementById('postMediaType').value)


$('#postMediaType').change(unGreyPostMediaInput);

function unGreyPostMediaInput() {

	$('#postMediaText').hide();
	$('#postMediaFile').hide();
	$('#postMediaFile').prev().hide();

	var selectedValue = $(this).find(":selected").val();
	//console.log(selectedValue);

	if(selectedValue == 'video') {
		$('#postMediaText').show();
	} else if(selectedValue == 'null' || selectedValue == 'none'){
		$('#postMediaText').hide();
		$('#postMediaFile').hide();
		$('#postMediaFile').prev().hide();
	 } else {
		$('#postMediaFile').show();
		$('#postMediaFile').prev().show();
	}

}

if(window.matchMedia("(max-width: 480px)").matches == true) {
	$('.title').hide();
}

}); // document ready
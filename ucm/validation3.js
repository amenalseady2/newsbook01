/***************************/
//@Author: Adrian "yEnS" Mato Gondelle & Ivan Guardado Castro
//@website: www.yensdesign.com
//@email: yensamg@gmail.com
//@license: Feel free to use it, but keep this credits please!					
/***************************/

$(document).ready(function(){
	//global vars
	var form = $("#customForm");
	
	var email = $("#email");
	var msgemail = $("#msgemail");
	
	var pass = $("#pwd");
	var msgpassowrd = $("#msgpassowrd");
	
	//On blur
	email.blur(validateEmail);
	pass.blur(validatePass);
	
	//On key press
	email.keyup(validateEmail);
	pass.keyup(validatePass);
	
	//On Submitting
	form.submit(function(){
		if(validateEmail())// & validatePass())
			return true
		else
			return false;
	});


	function validatePass(){
		//if it's NOT valid
		var a = $("#pwd").val();
		var illegalChars2= /[\(\)\<\>\;\:\\\/\"\[\]\!\@\#\$\%\~\*\&\`\?\}\{\|\-\+\=]/
    	var illegalChars =/[a-zA-Z0-9]/;///[0-9a-zA-Z]/;
		
		if(a=="")
		{
			pass.addClass("error");
			msgpassowrd.text("*");
			msgpassowrd.addClass("errortext");
			return false;
		}
		//if it's valid
		else{
			pass.removeClass("error");
			msgpassowrd.text("");
			msgpassowrd.removeClass("errortext");
			return true;
		}
	}
	
	function validateEmail(){
		//testing regular expression
		var a = $("#email").val();
		var filter = /^[a-zA-Z0-9]+[a-zA-Z0-9_.-]+[a-zA-Z0-9_-]+@[a-zA-Z0-9]+[a-zA-Z0-9.-]+[a-zA-Z0-9]+.[a-z]{2,4}$/;
		//if it's valid email
		if(a=="")
		{
			email.addClass("error");
			msgemail.text("*");
			msgemail.addClass("errortext");
			return false;
		}
		//if it's NOT valid
		else{
			email.removeClass("error");
			msgemail.text("");
			msgemail.removeClass("errortext");
			return true;
		}
	}
});
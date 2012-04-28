/***************************/
//@Author: Adrian "yEnS" Mato Gondelle & Ivan Guardado Castro
//@website: www.yensdesign.com
//@email: yensamg@gmail.com
//@license: Feel free to use it, but keep this credits please!					
/***************************/

$(document).ready(function(){
	//global vars
	var form = $("#customForm");
	
	var fname = $("#fname");
	var msgfname = $("#msgfname");
	
	var lname = $("#lname");
	var msglname = $("#msglname");
	
	var email = $("#email");
	var msgemail = $("#msgemail");
	
	//var pass = $("#pwd");
	//var msgpassowrd = $("#msgpassowrd");
	
	//On blur
	fname.blur(validateFName);
	lname.blur(validateLName);
	email.blur(validateEmail);
	//pass.blur(validatePass);
	
	//On key press
	fname.keyup(validateFName);
	lname.keyup(validateLName);
	email.keyup(validateEmail);
	//pass.keyup(validatePass);
	
	//On Submitting
	form.submit(function(){
		//if(validateFName() & validateLName() & validateEmail() & validatePass())
		if(validateFName() & validateLName() & validateEmail())
			return true
		else
			return false;
	});


	function validateFName(){
		//if it's NOT valid
		var a = $("#fname").val();
		var illegalChars2= /[\(\)\<\>\;\:\\\/\"\[\]\!\@\#\$\%\~\*\&\`\?\}\{\|\-\+\=]/
    	var illegalChars =/[a-zA-Z0-9]/;///[0-9a-zA-Z]/;
		
		if(a=="")
		{
			fname.addClass("error");
			msgfname.text("*");
			msgfname.addClass("errortext");
			return false;
		}
		else if((fname.val().length < 3) && ( illegalChars.test(a) && !a.match(illegalChars2) ) )
		{
			fname.addClass("error");
			msgfname.text("XXX");
			msgfname.addClass("errortext");
			return false;
		}
		else if ( !illegalChars.test(a) || a.match(illegalChars2) ) 
		{
        	fname.addClass("error");
			msgfname.text("!");
			msgfname.addClass("errortext");
			return false;
    	}
		//if it's valid
		else{
			fname.removeClass("error");
			msgfname.text("");
			msgfname.removeClass("error");
			return true;
		}
	}
	
	function validateLName(){
		//if it's NOT valid
		var a = $("#lname").val();
		var illegalChars2= /[\(\)\<\>\;\:\\\/\"\[\]\!\@\#\$\%\~\*\&\`\?\}\{\|\-\+\=]/
    	var illegalChars =/[a-zA-Z0-9]/;///[0-9a-zA-Z]/;
		
		if(a=="")
		{
			lname.addClass("error");
			msglname.text("*");
			msglname.addClass("errortext");
			return false;
		}
		else if((lname.val().length < 3) && ( illegalChars.test(a) && !a.match(illegalChars2) ) )
		{
			lname.addClass("error");
			msglname.text("XXX");
			msglname.addClass("errortext");
			return false;
		}
		else if ( !illegalChars.test(a) || a.match(illegalChars2) ) 
		{
        	lname.addClass("error");
			msglname.text("!");
			msglname.addClass("errortext");
			return false;
    	}
		//if it's valid
		else{
			lname.removeClass("error");
			msglname.text("");
			msglname.removeClass("error");
			return true;
		}
	}

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
		else if((pass.val().length < 5))
		{
			pass.addClass("error");
			msgpassowrd.text("XXXXX");
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
		else if(filter.test(a)){
			email.removeClass("error");
			msgemail.text("");
			msgemail.removeClass("errortext");
			return true;
		}
		//if it's NOT valid
		else{
			email.addClass("error");
			msgemail.text("!");
			msgemail.addClass("errortext");
			return false;
		}
	}
	
	function validatePass1(){
		var a = $("#password1");
		var b = $("#password2");

		//it's NOT valid
		if(pass1.val().length <5){
			pass1.addClass("error");
			pass1Info.text("Ey! Remember: At least 5 characters: letters, numbers and '_'");
			pass1Info.addClass("error");
			return false;
		}
		//it's valid
		else{			
			pass1.removeClass("error");
			pass1Info.text("At least 5 characters: letters, numbers and '_'");
			pass1Info.removeClass("error");
			validatePass2();
			return true;
		}
	}
	function validatePass2(){
		var a = $("#password1");
		var b = $("#password2");
		//are NOT valid
		if( pass1.val() != pass2.val() ){
			pass2.addClass("error");
			pass2Info.text("Passwords doesn't match!");
			pass2Info.addClass("error");
			return false;
		}
		//are valid
		else{
			pass2.removeClass("error");
			pass2Info.text("Confirm password");
			pass2Info.removeClass("error");
			return true;
		}
	}
});
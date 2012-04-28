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
	
	var alias = $("#alias");
	var msgalias = $("#msgalias");
	
	var city = $("#city");
	var msgcity = $("#msgcity");
	
	var website = $("#website");
	var msgwebsite = $("#msgwebsite");
	
	var iam = $("#iam");
	var msgiam = $("#msgiam");
	
	var ilike = $("#ilike");
	var msgilike = $("#msgilike");
	
	var myexperience = $("#myexperience");
	var msgmyexp = $("#msgmyexp");
	
	var email = $("#email");
	var emailInfo = $("#emailInfo");
	var pass1 = $("#pass1");
	var pass1Info = $("#pass1Info");
	var pass2 = $("#pass2");
	var pass2Info = $("#pass2Info");
	var message = $("#message");
	
	//On blur
	fname.blur(validateFName);
	lname.blur(validateLName);
	alias.blur(validateAlias);
	city.blur(validateCity);
	website.blur(validateWebsite);
	iam.blur(validateIam);
	ilike.blur(validateIlike);
	myexperience.blur(validateMyexp);
	
	email.blur(validateEmail);
	pass1.blur(validatePass1);
	pass2.blur(validatePass2);
	
	//On key press
	fname.keyup(validateFName);
	lname.keyup(validateLName);
	alias.keyup(validateAlias);
	city.keyup(validateCity);
	website.keyup(validateWebsite);
	iam.keyup(validateIam);
	ilike.keyup(validateIlike);
	myexperience.keyup(validateMyexp);
	
	pass1.keyup(validatePass1);
	pass2.keyup(validatePass2);
	message.keyup(validateMessage);
	//On Submitting
	form.submit(function(){
		if(validateFName() & validateLName() & validateAlias() & validateCity() & validateWebsite() & validateIam() & validateIlike() & validateMyexp())// & validateEmail() & validatePass1() & validatePass2() & validateMessage())
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
			msgfname.text("Required.");
			msgfname.addClass("errortext");
			return false;
		}
		else if((fname.val().length < 3) && ( illegalChars.test(a) && !a.match(illegalChars2) ) )
		{
			fname.addClass("error");
			msgfname.text("At least 3 characters are required.");
			msgfname.addClass("errortext");
			return false;
		}
		else if ( !illegalChars.test(a) || a.match(illegalChars2) ) 
		{
        	fname.addClass("error");
			msgfname.text("Special characters are not allowed.");
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
			msglname.text("Required.");
			msglname.addClass("errortext");
			return false;
		}
		else if((lname.val().length < 3) && ( illegalChars.test(a) && !a.match(illegalChars2) ) )
		{
			lname.addClass("error");
			msglname.text("At least 3 characters are required.");
			msglname.addClass("errortext");
			return false;
		}
		else if ( !illegalChars.test(a) || a.match(illegalChars2) ) 
		{
        	lname.addClass("error");
			msglname.text("Special characters are not allowed.");
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

	function validateAlias(){
		//if it's NOT valid
		var a = $("#alias").val();
		var illegalChars2= /[\(\)\<\>\;\:\\\/\"\[\]\!\@\#\$\%\~\*\&\`\?\}\{\|\-\+\=]/
    	var illegalChars =/[a-zA-Z0-9]/;///[0-9a-zA-Z]/;
		
		if ( a!=="" && !illegalChars.test(a) || a.match(illegalChars2) ) 
		{
        	alias.addClass("error");
			msgalias.text("Special characters are not allowed.");
			msgalias.addClass("errortext");
			return false;
    	}
		else if((alias.val().length < 3) && ( illegalChars.test(a) && !a.match(illegalChars2) ) )
		{
			alias.addClass("error");
			msgalias.text("At least 3 characters are required.");
			msgalias.addClass("errortext");
			return false;
		}
		//if it's valid
		else{
			alias.removeClass("error");
			msgalias.text("");
			msgalias.removeClass("error");
			return true;
		}
	}

	function validateCity(){
		//if it's NOT valid
	
		var a = $("#city").val();
		var illegalChars2= /[\(\)\<\>\;\:\\\/\"\[\]\!\@\#\$\%\~\*\&\`\?\}\{\|\-\+\=]/
    	var illegalChars =/[a-zA-Z0-9]/;///[0-9a-zA-Z]/;
		
		if ( a!=="" && !illegalChars.test(a) || a.match(illegalChars2) ) 
		{
        	city.addClass("error");
			msgcity.text("Special characters are not allowed.");
			msgcity.addClass("errortext");
			return false;
    	}
		else if((city.val().length < 3) && ( illegalChars.test(a) && !a.match(illegalChars2) ) )
		{
			city.addClass("error");
			msgcity.text("At least 3 characters are required.");
			msgcity.addClass("errortext");
			return false;
		}
		//if it's valid
		else{
			city.removeClass("error");
			msgcity.text("");
			msgcity.removeClass("error");
			return true;
		}
	}

	function validateWebsite(){
		//if it's NOT valid
	
		var a = $("#website").val();
		var illegalChars2= /[\(\)\<\>\;\\\"\[\]\!\@\#\$\%\~\*\&\`\?\}\{\|\-\+\=]/
    	var illegalChars =/[a-zA-Z0-9]/;///[0-9a-zA-Z]/;
		
		if ( a!=="" && !illegalChars.test(a) || a.match(illegalChars2) ) 
		{
        	website.addClass("error");
			msgwebsite.text("Special characters are not allowed. The only allowed speacial characters are : and /");
			msgwebsite.addClass("errortext");
			return false;
    	}
		else if((website.val().length < 5) && ( illegalChars.test(a) && !a.match(illegalChars2) ) )
		{
			website.addClass("error");
			msgwebsite.text("At least 5 characters are required.");
			msgwebsite.addClass("errortext");
			return false;
		}
		//if it's valid
		else{
			website.removeClass("error");
			msgwebsite.text("");
			msgwebsite.removeClass("error");
			return true;
		}
	}

	function validateIam(){
		//if it's NOT valid
	
		var a = $("#iam").val();
		var illegalChars2= /[\(\)\<\>\;\:\\\/\"\[\]\!\@\#\$\%\~\*\&\`\?\}\{\|\-\+\=]/
    	var illegalChars =/[a-zA-Z0-9]/;///[0-9a-zA-Z]/;
		
		if ( a!=="" &&  a.match(illegalChars2) ) 
		{
        	iam.addClass("error");
			msgiam.text("Special characters are not allowed.");
			msgiam.addClass("errortextarea");
			return false;
    	}
		//else if((iam.val().length < 10) && ( !a.match(illegalChars2) ) )
//		{
//			iam.addClass("error");
//			msgiam.text("At least 10 characters are required.");
//			msgiam.addClass("errortextarea");
//			return false;
//		}
		//if it's valid
		else{
			iam.removeClass("error");
			msgiam.text("");
			msgiam.removeClass("errortextarea");
			return true;
		}
	}

	function validateIlike(){
		//if it's NOT valid
	
		var a = $("#ilike").val();
		var illegalChars2= /[\(\)\<\>\;\:\\\/\"\[\]\!\@\#\$\%\~\*\&\`\?\}\{\|\-\+\=]/
    	var illegalChars =/[a-zA-Z0-9]/;///[0-9a-zA-Z]/;
		
		if ( a!=="" && a.match(illegalChars2) ) 
		{
        	ilike.addClass("error");
			msgilike.text("Special characters are not allowed.");
			msgilike.addClass("errortextarea");
			return false;
    	}
		//else if((ilike.val().length < 10) && (  !a.match(illegalChars2) ) )
//		{
//			ilike.addClass("error");
//			msgilike.text("At least 10 characters are required.");
//			msgilike.addClass("errortextarea");
//			return false;
//		}
		//if it's valid
		else{
			ilike.removeClass("error");
			msgilike.text("");
			msgilike.removeClass("errortextarea");
			return true;
		}
	}

	function validateMyexp(){
		//if it's NOT valid
	
		var a = $("#myexperience").val();
		var illegalChars2= /[\(\)\<\>\;\:\\\/\"\[\]\!\@\#\$\%\~\*\&\`\?\}\{\|\-\+\=]/
    	var illegalChars =/[a-zA-Z0-9]/;///[0-9a-zA-Z]/;
		
		if ( a!=="" && a.match(illegalChars2) ) 
		{
        	myexperience.addClass("error");
			msgmyexp.text("Special characters are not allowed.");
			msgmyexp.addClass("errortextarea");
			return false;
    	}
		//else if((myexperience.val().length < 10) && (  !a.match(illegalChars2) ) )
//		{
//			myexperience.addClass("error");
//			msgmyexp.text("At least 10 characters are required.");
//			msgmyexp.addClass("errortextarea");
//			return false;
//		}
		//if it's valid
		else{
			myexperience.removeClass("error");
			msgmyexp.text("");
			msgmyexp.removeClass("errortextarea");
			return true;
		}
	}



	
	//validation functions
	function validateEmail(){
		//testing regular expression
		var a = $("#email").val();
		var filter = /^[a-zA-Z0-9]+[a-zA-Z0-9_.-]+[a-zA-Z0-9_-]+@[a-zA-Z0-9]+[a-zA-Z0-9.-]+[a-zA-Z0-9]+.[a-z]{2,4}$/;
		//if it's valid email
		if(filter.test(a)){
			email.removeClass("error");
			emailInfo.text("Valid E-mail please, you will need it to log in!");
			emailInfo.removeClass("error");
			return true;
		}
		//if it's NOT valid
		else{
			email.addClass("error");
			emailInfo.text("Stop cowboy! Type a valid e-mail please :P");
			emailInfo.addClass("error");
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
	function validateMessage(){
		//it's NOT valid
		if(message.val().length < 10){
			message.addClass("error");
			return false;
		}
		//it's valid
		else{			
			message.removeClass("error");
			return true;
		}
	}
});
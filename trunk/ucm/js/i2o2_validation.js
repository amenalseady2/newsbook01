function select_otherfield()
{
  if(document.getElementById("diseaseid").value=="SUGGEST NEW INTEREST")
  {
	 document.getElementById("otherques").style.display="block";
  }
  else
  {
  document.getElementById("otherques").style.display="none";
  }
}

function select_otherfieldsub()
{
  if(document.getElementById("subdiseaseid").value=="SUGGEST NEW INTEREST")
  {
	 document.getElementById("otherques1").style.display="block";
  }
  else
  {
  document.getElementById("otherques1").style.display="none";
  }
}

function signups(theform)
{
	var emailRegex = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;
	if(theform.email.value =="")
	{
	theform.email.style.border="1px solid red";
	return false;
	}
	if(!theform.email.value.match(emailRegex))
	{
		alert("Enter Correct Email Address");
	theform.email.style.border="1px solid red";
	return false;
	}
	if(theform.pwd.value == "")
	{
	theform.pwd.style.border="1px solid red";
	return false;
	}
    if(theform.diseaseid.value == "Other")
	{
	 if(theform.otherques.value=="")	
	 {
	 theform.otherques.style.border="1px solid red";
	 return false;
	 }
	}
	
		
	return true;
}
function check_admin(theform)
{
	var emailRegex = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;
	if(theform.email.value =="")
	{
	theform.email.style.border="1px solid red";
	return false;
	}
	if(!theform.email.value.match(emailRegex))
	{
		alert("Enter Correct Email Address");
	theform.email.style.border="1px solid red";
	return false;
	}
	if(theform.pwd.value == "")
	{
	theform.pwd.style.border="1px solid red";
	return false;
	}
	return true;
}
function signins(theform)
{
	var emailRegex = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;
	if(theform.email.value =="")
	{
	 theform.email.style.border="1px solid red";
	return false;
	}
	if(!theform.email.value.match(emailRegex))
	{
		alert("Enter Correct Email Address");
	theform.email.style.border="1px solid red";
	return false;
	}
	if(theform.pwd.value == "")
	{
	theform.pwd.style.border="1px solid red";
	return false;
	}	
  return true;
}


function add_ques(theform)
{
  if(theform.strquestion.value =="")
	{
	 theform.strquestion.style.border="1px solid red";
	return false;
	}
  return true;
}
function check_bug(theform)
{var emailRegex = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;
var sc=theform.security_code.value;

  if(theform.email.value =="")
	{
	 theform.email.style.border="1px solid red";
	return false;
	}
	if(!theform.email.value.match(emailRegex))
	{
		alert("Enter Correct Email Address");
	theform.email.style.border="1px solid red";
	return false;
	}
  if(theform.fname.value =="")
	{
	 theform.fname.style.border="1px solid red";
	return false;
	}
  if(theform.subject.value =="")
	{
	 theform.subject.style.border="1px solid red";
	return false;
	}
	 if(theform.usecurity_code.value =="")
	{
	 theform.usecurity_code.style.border="1px solid red";
	return false;
	}
	else
	{if(theform.usecurity_code.value!=sc)
	{
		alert("Enter Correct Code");
	 theform.usecurity_code.style.border="1px solid red";
	return false;
	}
	}
  return true;
}
function uques(theform)
{
  if(theform.stranswer.value =="")
	{
	 theform.stranswer.style.border="1px solid red";
	return false;
	}
  return true;
}

function interest(theform)
{
  if(theform.strinterest.value =="")
	{
	 theform.strinterest.style.border="1px solid red";
	return false;
	}
  return true;
}

function updaye_answes(qid,ansid)
{
 mywindow = window.open("update_answer.php?ques_id="+qid+"&ans_id="+ansid,"mywindow","width=550,height=320,toolbar=no,location=no,directories=no,status=no,menubar=no, scrollbars=yes,resizable=no,copyhistory=no,top=100px,left=350px;");
}

///////////////////////AJAX CODE HERE//////////////
function GetXmlHttpObject()
{
 var xmlHttp=null;
  try
    { xmlHttp=new XMLHttpRequest();  }
  catch (e)
    { // Internet Explorer
     try
      {   xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");  }
    catch (e)
     {    xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");  }
     }
  return xmlHttp;
}
function fetchdata(str,box)
{
 var obj=GetXmlHttpObject();
 obj.open("GET",str,true);
 obj.onreadystatechange=function f1()
  {
  document.getElementById(box).innerHTML="<div style='color:#999999;font-size:12px;font-weight:bold;padding-left:30px;padding-top:50px'><img src='images/loading.gif' alt='Loading' /></div>";
  if(obj.readyState==4)
   {
   var x=obj.responseText;
   document.getElementById(box).innerHTML=x;
   obj=null;
  }
  }
 obj.send(null);
}
/////////////////////////////////////ajax code ends here///////////////////////////////////////////////////////

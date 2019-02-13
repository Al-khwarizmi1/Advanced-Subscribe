/**
 * 
 */
function storeEmailidinDB(email,ctrlFunCalling,successMes,confirmMess,mailSendCanceled, youAreAlreadySub,sendMailFail){
		
	advancedStoreProductDetailsToDB(email,ctrlFunCalling,successMes,confirmMess,mailSendCanceled, youAreAlreadySub,sendMailFail);
	 
}//end hear

var xmlhttp;
function advancedStoreProductDetailsToDB(email,ctrlFunCalling,successMes,confirmMess,mailSendCanceled,youAreAlreadySub,sendMailFail)
{
	
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {   
	      var responce = xmlhttp.responseText;
	      
	      responce = trim(responce);
	     // alert(responce);
	      if(responce == 'confirm to send mail again')
	      {
	    	   if(confirm(confirmMess))
	    		   {
	    		   		ajaxRequesSend(ctrlFunCalling,email,10);
	    		   		//document.getElementById('successMsg').innerHTML = successMes;
	    		   }
	    	   else{
	    		   document.getElementById('successMsg').innerHTML = mailSendCanceled;
	    	   }
	      }
	      else if(responce == 'inserted success')
	      {
	    	  // document.getElementById('successMsg').innerHTML= 'You are alreday notified';
	    	  document.getElementById('successMsg').innerHTML = successMes;
	      }
	      else if(responce == 'you are already subscriber')
	    	  {
	    	 
	    	  document.getElementById('successMsg').innerHTML = youAreAlreadySub;
	    	  }
	      else{
	    	  	 // document.getElementById('padding_div').innerHTML=xmlhttp.responseText;
	    	  		document.getElementById('successMsg').innerHTML = sendMailFail;
	    	  	  return 1;
	      }
	     
    }
 }

ajaxRequesSend(ctrlFunCalling,email,'no');
}

function ajaxRequesSend(ctrlFunCalling,email,flag){
	//alert(ctrlFunCalling+'?eMailId='+email);
	xmlhttp.open("GET",ctrlFunCalling+'?eMailId='+email+'&sendMailAgain='+flag,true);
	//xmlhttp.open("GET",ctrlFunCalling+'?eMailId=gopi@gmail.com&sendMailAgain='+flag,true);
	xmlhttp.send();

	
}
function trim(stringToTrim) {
	return stringToTrim.replace(/^\s+|\s+$/g,"");
}


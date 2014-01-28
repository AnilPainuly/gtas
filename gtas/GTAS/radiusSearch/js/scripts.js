

/*
	//Author Name     : Frank Sunil
	//Date            : Jan 14, 2013
	//Purpose         : Function is used to allow dot and hyphen values.
*/
function numberDot(evt)
{		
		evt = (evt) ? evt : window.event
		var charCode = (evt.which) ? evt.which : evt.keyCode 
//alert(charCode);
			/* for enabling enter, backspace, delete and arrow keys */
		if((charCode==13) ||(charCode==8) ||(charCode==45) || (charCode==46) ||(charCode==116)|| (charCode > 36) && (charCode < 41))
			return true;

		/* for enabling arrow keys and enter  ends here*/
		if((charCode < 48) || (charCode > 57) && (charCode > 32))
		{
			alert('You can only enter numbers  here');
				return false;
		}
		return true;
}

/*
	//Author Name     : Frank Sunil
	//Date            : Jan 14, 2013
	//Purpose         : Function is used to allow dot and hyphen values.
*/
function onlynum(evt)
{		
		evt = (evt) ? evt : window.event
		var charCode = (evt.which) ? evt.which : evt.keyCode 

			/* for enabling enter, backspace, delete and arrow keys */
		if((charCode==13) ||(charCode==8) || (charCode==116)|| (charCode > 36) && (charCode < 41))
			return true;

		/* for enabling arrow keys and enter  ends here*/
		if((charCode < 48) || (charCode > 57) && (charCode > 32))
		{
			alert('You can only enter numbers here');
				return false;
		}
		return true;
}

/*
	//Author Name     : Frank Sunil
	//Date            : Jan 22, 2013
	//Purpose         : Clearing text box data
*/
function clearData(startTime, endTime)
{
	$('#'+startTime).val("");
	$('#'+endTime).val("");
}

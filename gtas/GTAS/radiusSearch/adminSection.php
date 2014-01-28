<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Manage Events</title>
</head> 
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Droid+Sans:400,700%7CUbuntu:400,500italic,400italic,700&amp;subset=latin,latin">
<link rel="stylesheet" type="text/css" href="selectListLib/jquery.multiselect.css" />
<link rel="stylesheet" type="text/css" href="selectListLib/style.css" />
<link rel="stylesheet" type="text/css" href="selectListLib/jquery-ui.css" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js"></script>
<script type="text/javascript" src="selectListLib/src/jquery.multiselect.js"></script>



<?php 
error_reporting(E_ALL);
ini_set("display_errors", 0); 
session_start(); 
//****************************************************************************************
    //Page Name       : adminSection.php
    //Author Name     : Alan Anil
    //Date            : July 30 2013
    //Input Parameters:
    //Purpose         : Create new event.
	//***********************************************************************************
	//| Ref No.	|  Author name	| Date	         |  Modification description
	//	1		Parker Prashant		20-Aug-2013		Error msgs adjacent to field and datepicker plugin
	//***********************************************************************************
/*====================================================================== */
require 'generalFunction.php'; 
?>
<body style="background: #ffffff">
<?php
$todayDate = date("Y-m-d");
$eventTypesCounter = 0 ;
$getAllEventTypes  = getEventTypes(); 
$errorCode         = 0;
$getConfimMsg      = '';
$errorValue        = '';
$getResult         = '';
$eventIdGot        = ''; 
$eventNameGot      = '';
$eventZipGot       = '';
$eventDateGot      = '';
$categoryArr       = '';
$getEventName	   = '';
$getZipcode		   = '';
$getEventType	   = array();
$getStartDate	   = '';
$getEventId		   = '';
if(isset($_REQUEST['eventId']) && $_REQUEST['eventId'] != '')
{
	$getEventDetails  = $_REQUEST['eventId'];
	$eventIdFirst     = explode("(", $getEventDetails);
	$eventIdSec       = explode(")", $eventIdFirst[1]); 
	$getEventFinalId  = $eventIdSec[0];
	if($getEventFinalId != 0)
	{
	  $getResult        = getEventInfoForEdit($getEventFinalId);
	  $eventIdGot       = $getResult['Event_ID'];
	  $eventNameGot     = $getResult['Event_Name'];
	  $eventZipGot      = $getResult['Event_Zip'];
	  $eventDateGot     = $getResult['Event_Date'];
	  /* for converting date from db added by parker*/
	  list($year,$month,$date)=split('[-]',$eventDateGot);
	  $eventDateGot=$month.'/'.$date.'/'.$year;
	    /* for converting date from db added by parker ends*/
	  $eventCategoryGot = $getResult['Event_Category'];
	  $categoryArr      = explode(",", $eventCategoryGot);
	}
}
/*variables for errors */
$errorZip='';
$errorEventName='';
$errorEventType='';
$errorStartDate='';
$errorEventId='';
if(isset($_REQUEST['submit']))
{ 
	$getEventName  = $_REQUEST['eventName'];
	$getZipcode    = trim($_REQUEST['zipcode']);
	$getEventType  = $_REQUEST['eventType'];
	$getStartDate  = trim($_REQUEST['startDate']);
	/* converting start date in date format */
	list($month, $day, $year)=split('[/]', $getStartDate); 
	$getStartDate = $year.'-'.$month.'-'.$day;
	/* converting start date in date format ends*/
	$getEventId    = trim($_REQUEST['eventId']);
	if($getZipcode!='' && !is_numeric($getZipcode))
	{
		$errorCode  = 1;
		$errorZip = "<span class='errorText'>Zip code can contain numbers only.</span>";	
	}
	if($getEventId!='' && !is_numeric($getEventId))
	{
		$errorCode  = 1;
		$errorEventId = "<span class='errorText'>Event Id can contain only numbers.</span>";	
	}
	if($getEventName == '')
	{
		$errorCode  = 1;
		$errorEventName = "<span class='errorText'>Please enter Event Name.</span>";	
	}
	if($getZipcode == '')
	{
		$errorCode  = 1;
		$errorZip = "<span class='errorText'>Please enter ZIP Code.</span>";	
	}
	if($getEventType == '')
	{
		$errorCode  = 1;
		$errorEventType = "<span class='errorText'>Please enter Event Type.</span>";	
	} 
	if($getStartDate == '')
	{
		$errorCode  = 1;
		$errorStartDate = "<span class='errorText'>Please enter Start Date.</span>";	
	}
	if($getEventId == '')
	{
		$errorCode  = 1;
		$errorEventId = "<span class='errorText'>Please enter Event ID.</span>";	
	}
	if($getStartDate != '')
	{
		list($yy,$mm,$dd)=explode("-",$getStartDate); 
		if (is_numeric($yy) && is_numeric($mm) && is_numeric($dd)) 
		{ }
		else
		{
			$errorCode  = 1;
			$errorStartDate = "<span class='errorText'>Please enter Start Date in given format.</span>";
		} 
	}
	if($errorCode == 0)
	{
		$noOfValues = strlen($getZipcode);
		if($noOfValues != 5)
		{
			$errorCode  = 1;
			$errorZip = "<span class='errorText'>Please enter five digit ZIP Code.</span>";	
		}
		else
		{
			$getConfimMsg  = insertOrUpdateValue($getEventName,$getZipcode,$getEventType,$getStartDate, $getEventId);
			/* empty values of text field */
			$getEventName='';
			$getZipcode='';
			$getStartDate='';
			$getEventId='';
		}
	}
}
 
?> 
<div class="mainDiv" style="background: #ffffff">
<div class="mainInnerDiv2">
<div class="spTitleBar topHeading">Manage (Add/Edit) Events</div>
<div class="errorArea">
    <?php 
	
	if(isset($_REQUEST['submit']) && ($_REQUEST['eventName'] != '') && ($_REQUEST['zipcode'] != '')  
	   && ($_REQUEST['startDate'] != '') && ($_REQUEST['eventId'] != ''))
	{
		if($getConfimMsg != '')
		{
			echo "<div class='successMsg'><h3>$getConfimMsg</h3></div>";
		}
	}
	?>
</div> 
<form method="post" name="radiusSearchForm" id="radiusSearchFormId" action=""> 
<div class="zipCodeSearch">
    <div style="margin-bottom:5px;">
    <input type="hidden" name="country" value="us" />
    <input type="hidden" name="unit" value="miles" /> 
    <div class="radiusSearchContent"><span class="innerText">Event Name</span><span class="requiredStar"> *</span></div>
    <input maxlength="200" type="text" class="width211" name="eventName" id="eventName" placeholder="Enter Name" 
    value="<?php if($eventNameGot != '') { echo $eventNameGot; } ?><?php if(isset($getEventName)){echo $getEventName;}?>" />
    <?php if(!empty($errorEventName)){echo $errorEventName;} ?>
    </div>
    <div style="margin-bottom:5px;">
    <div class="radiusSearchContent"><span class="innerText">ZIP Code</span><span class="requiredStar"> *</span></div>
    <input type="text" maxlength="5" class="width211" onkeypress="return onlynum(event)" name="zipcode" id="idZipCode" placeholder="Enter ZIP" 
    value="<?php if($eventZipGot != '') { echo $eventZipGot; } ?><?php if(isset($getZipcode)){echo $getZipcode;}?>" />
   <?php if(!empty($errorZip)){echo $errorZip;} ?>
    </div>
     <div style="margin-bottom:5px;">
    <div class="radiusSearchContent"><span class="innerText">Event Type</span><span class="requiredStar"> *</span></div>
         <select name="eventType[]" id="eventTypeId" multiple="multiple"    class="dropDown"> 
         <?php foreach($getAllEventTypes as $filterEvents)
         { ?>
            <option value='<?php echo $getAllEventTypes[$eventTypesCounter] ?>'
            <?php foreach($categoryArr as $multiselCategory) { 
				if($multiselCategory == $getAllEventTypes[$eventTypesCounter])
				{ echo "selected"; }
		 } ?> >
			<?php echo $getAllEventTypes[$eventTypesCounter] ?>
            </option> 
         <?php
		 $eventTypesCounter++;
         }  ?>
         </select>
    <?php if(!empty($errorEventType)){echo $errorEventType;} ?>
    </div>
    <div style="margin-bottom:5px;">
    <div class="radiusSearchContent"><span class="innerText">Start Date</span><span class="requiredStar"> *</span></div><input id="datepicker" onkeypress="return notnum(event)" type="text" class="width211" name="startDate" placeholder="Enter Start Date" 
    value="<?php if($eventDateGot != '') { echo $eventDateGot; } ?><?php if(isset($getStartDate)){echo $getStartDate;}?>" />
     <?php if(!empty($errorStartDate)){echo $errorStartDate;}?>
    </div>
    <div style="margin-bottom:5px;">
    <div class="radiusSearchContent"><span class="innerText">Event ID</span><span class="requiredStar"> *</span></div>
    <input type="text" maxlength="9" onkeypress="return onlynum(event)" class="width211" name="eventId" id="idEventId" placeholder="Enter Event Id" 
    value="<?php if($eventIdGot != '') { echo $eventIdGot; } ?><?php if(isset($getEventId)){echo $getEventId;}?>" />
    <?php if(!empty($errorEventId)){echo $errorEventId;}?>
    </div>
      
    
    
    <div class="bottomButtons">
     <div class="requiredStar" style="margin-bottom:5px;">(* = Required Field)</div>
     <div class="submitButton">
    <input type="submit" name="submit" id="submitId" title="Submit" value="Submit" class="formbutton buttonClass" />
    </div>
    </div>
</div> 
</div>
</div>
</form>     
</body>
</html>
<script type="text/javascript">
$("#eventTypeId").multiselect(); 
$("form").bind("submit", function(){   
});   
</script>
<link rel="stylesheet" type="text/css" href="style/jquery.autocomplete.css" />
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/jquery.autocomplete.js"></script>
<script>
 
$().ready(function() {

        $("#eventName").autocomplete("autocomplete.php", {
            minChars: 3,
            selectFirst: false,
            formatItem: function(data, i, n, value) {
            return value.split("-")[0];
            },
            //Not used, just for splitting employee_ID
            //formatResult: function(data, value) {
            //   return value.split("-")[1];
            //}  
            });

            $('#eventName').result(function(event, data, formatted) {
            location.href = "?eventId=" + data
            });

    });
  
</script>   
<!-- date picker plugin starts here -->
<link rel="stylesheet" href="style/jquery-ui.css" />
  <script type="text/javascript" src="js/jquery-1.9.1.js"></script>
  <script type="text/javascript" src="js/jquery-ui.js"></script>
  <script>
  var $j= jQuery.noConflict();
   $j(function() {
    $j( "#datepicker" ).datepicker({ dateFormat: 'mm/dd/yy' }).val();
  });
  function notnum(evt)
{
	evt = (evt) ? evt : window.event
	var charCode = (evt.which) ? evt.which : evt.keyCode 
	if(charCode>=48 && charCode<=57)
	{
		return false;
	}
}
  function onlynum(evt)
{		
		evt = (evt) ? evt : window.event
		var charCode = (evt.which) ? evt.which : evt.keyCode 
		//alert(charCode);
			/* for enabling enter, backspace, delete and arrow keys */
		if((charCode==13) ||(charCode==8) ||(charCode==46) ||(charCode==116)|| (charCode > 36) && (charCode < 41))
		{
			return true;
				
		}
		/* for enabling arrow keys and enter  ends here*/
		if((charCode < 48) || (charCode > 57) && (charCode > 32))
		{
			alert('You can only enter numbers here');
				return false;
		}
		return true;
}
</script>

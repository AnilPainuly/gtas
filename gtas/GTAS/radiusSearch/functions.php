<?php  

session_start(); 
//****************************************************************************************
    //Page Name       : adminSection.php
    //Author Name     : Alan Anil
    //Date            : July 30 2013
    //Input Parameters:
    //Purpose         : Create new event.
//**************************************************************************************** 
//  error_reporting(E_ALL);
//  ini_set("display_errors", 1);
/*====================================================================== */
require 'generalFunction.php'; 
$eventTypesCounter = 0 ;
$getAllEventTypes  = getEventTypes(); 
$errorCode = 0;
if(isset($_REQUEST['submit']) && ($_REQUEST['eventName'] != '') && ($_REQUEST['zipcode'] != ''))
{
	$getEventName  = $_REQUEST['eventName'];
	$getZipcode    = $_REQUEST['zipcode'];
	$getEventType  = $_REQUEST['eventType'];
	insertOrUpdateValue($getEventName,$getZipcode,$getEventType);
}
if(isset($_REQUEST['submit']))
{
	$errorCode = 1;
 	$errorValue = "Please enter Event Name and Zip Code both.";
}
?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head> 
<body>
<div class="mainDiv">
<div class="mainInnerDiv">
<div class="topHeading">Create Event-Custom Database</div>
<div>
    <?php 
	if($errorCode == 1)
	{
		echo $errorValue;
	}
	?>
</div>
<form method="post" name="radiusSearchForm" id="radiusSearchFormId" action=""> 
<div class="zipCodeSearch">
    <div style="margin-bottom:5px;">
    <input type="hidden" name="country" value="us" />
    <input type="hidden" name="unit" value="miles" /> 
    <div class="radiusSearchContent">Event name:</div><input type="text" name="eventName" placeholder="Enter Name" />
    </div>
    <div style="margin-bottom:5px;">
    <div class="radiusSearchContent">ZIP Code:</div><input type="text" name="zipcode" placeholder="Enter ZIP" />
    </div>
     <div style="margin-bottom:5px;">
    <div class="radiusSearchContent">Event Type:</div>
         <select name="eventType"  class="dropDown">
         <option value="0">-select-</option> 
         <?php foreach($getAllEventTypes as $filterEvents)
         {
            echo "<option value='$getAllEventTypes[$eventTypesCounter]'>$getAllEventTypes[$eventTypesCounter]</option>";
            $eventTypesCounter++;
         }
         
         ?>
         </select>
    </div>
     <div class="submitButton">
    <input type="submit" name="submit" value="Submit" />
    </div>
</div>      

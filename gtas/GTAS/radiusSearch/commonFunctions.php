<?php
define("ADMINEMAIL","algross@gtallsports.com");
#	@Name		:	commonFunctions
#	@Author		:	Matthew Umesh
#	@Date		:	Jan 14,2013
#	@Purpose		:	to keep all the functions
include_once("connection.php");

#	@Name			:	stateList
#	@Author			:	Matthew Umesh	
#	@Date			:	Dec 30,2013
#	@Purpose		:	To return state List
function stateList() {
    return $state_list = array('AL'=>"Alabama",
                'AK'=>"Alaska", 
                'AZ'=>"Arizona", 
                'AR'=>"Arkansas", 
                'CA'=>"California", 
                'CO'=>"Colorado", 
                'CT'=>"Connecticut", 
                'DE'=>"Delaware", 
                'DC'=>"District Of Columbia", 
                'FL'=>"Florida", 
                'GA'=>"Georgia", 
                'HI'=>"Hawaii", 
                'ID'=>"Idaho", 
                'IL'=>"Illinois", 
                'IN'=>"Indiana", 
                'IA'=>"Iowa", 
                'KS'=>"Kansas", 
                'KY'=>"Kentucky", 
                'LA'=>"Louisiana", 
                'ME'=>"Maine", 
                'MD'=>"Maryland", 
                'MA'=>"Massachusetts", 
                'MI'=>"Michigan", 
                'MN'=>"Minnesota", 
                'MS'=>"Mississippi", 
                'MO'=>"Missouri", 
                'MT'=>"Montana",
                'NE'=>"Nebraska",
                'NV'=>"Nevada",
                'NH'=>"New Hampshire",
                'NJ'=>"New Jersey",
                'NM'=>"New Mexico",
                'NY'=>"New York",
                'NC'=>"North Carolina",
                'ND'=>"North Dakota",
                'OH'=>"Ohio", 
                'OK'=>"Oklahoma", 
                'OR'=>"Oregon", 
                'PA'=>"Pennsylvania", 
                'RI'=>"Rhode Island", 
                'SC'=>"South Carolina", 
                'SD'=>"South Dakota",
                'TN'=>"Tennessee", 
                'TX'=>"Texas", 
                'UT'=>"Utah", 
                'VT'=>"Vermont", 
                'VA'=>"Virginia", 
                'WA'=>"Washington", 
                'WV'=>"West Virginia", 
                'WI'=>"Wisconsin", 
                'WY'=>"Wyoming");
}
#	@Name			:	stateDropDown
#	@Author			:	Matthew Umesh	
#	@Date			:	Dec 30,2013
#	@Purpose		:	To return state dropdown
#	@Argument		:	$selected - selected value| $fieldName - name of dropdown
function stateDropDown($selected='',$fieldName)
{
	$stateArray=stateList();
	$str = "<select name='$fieldName' id='$fieldName'>";
	$str .= "<option value=''>Select State</option>";
	foreach($stateArray as $key=>$val)
	{
		$temp = ($selected==$key) ? "selected='selected'" : '';
		$str .="<option value='".$key."' $temp>".$val."</option>";
	}
	$str .="</select>";
	echo $str;
}
#	@Name			:	isValidDate
#	@Author			:	Matthew Umesh	
#	@Date			:	Dec 30,2013
#	@Purpose		:	To check valid date format
#	@Argument		:	$dateTime - date
function isValidDate($dateTime) {
    if (trim($dateTime) == '') {
        return false;
    }
    if (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{2,4})?$/', $dateTime, $matches)) {
        list($all,$mm,$dd,$year) = $matches;
        if ($year <= 99) 
            $year += 2000;

        return checkdate($mm, $dd, $year);
    }
    return false;
}
#	@Name			:	isValidTime
#	@Author			:	Matthew Umesh	
#	@Date			:	Dec 30,2013
#	@Purpose		:	To check valid time format
#	@Argument		:	$dateTime - time value
function isValidTime($dateTime) {
    if (trim($dateTime) == '') {
        return false;
    }
    if (preg_match('/^((([01]?[0-9])|(2[0-3]))(:[0-5][0-9]){0,2}(\s+(am|pm))?)?$/i', $dateTime, $matches)) {
        return true;
    }
    return false;
}
#	@Name			:	sendEmail
#	@Author			:	Matthew Umesh	
#	@Date			:	Dec 30,2013
#	@Purpose		:	To check valid time format
#	@Argument		:	$contactEmail - email address  | id - event id
function sendEmail($contactEmail,$id)
{
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
	// More headers
	$headers .= 'From: '. ADMINEMAIL . "\r\n";
	
	$message = "<html>
		<title>
			<head>
			</head>
		</title>
	<body>";
	$message .= "<div style ='text-align:center;'><img src='http://s376341053.onlinehome.us/sites/GTAS/radiusSearch/uploads/logo.jpg' width='400'></div>";
	$message .= "<p>".$postArray['contactName']."</p>";
	$message .= "<p>Thank you for completing your event information.  Your event will be live and searchable by <a href='http://s376341053.onlinehome.us/sites/GTAS/radiusSearch/publishEvent.php?id=$id'>CLICKING HERE</a> within approximately 2 hours.</p>";
	$message .= "<p>This process is designed to verify this e-mail address as to being the responsible party for adding this event and also the contact e-mail address for this listing.  You are responsible to eliminate as much duplication as possible by only the actual listed event director making these submissions.</p>";
	$message .= "<p>The event will be searchable by the event category, date range, and also by using a radius search using a valid zip code. </p>";
	$message .= "<p>Current listings are free and allow all members the capability of searching for your event.</p>";
	$message .= "<p>Please contact GT All Sports for entire group membership notifications for National and World Level Events.</p>";
	$message .= "<p>Donations are welcome. <a href='http://www.gtallsports.com/donations/'>CLICK HERE</a> to support this web site and to support building new features for your enjoyment.</p>";
	$message .= "<p>Print this <a href='http://www.gtallsports.com/?page=GTAS_Flyer'>GT ALL SPORTS FLYER</a> and post at your business to help increase membership and access to GT All Sports and your event listings.</p>";
	$message .= "<p>Thank you for your event listing,<br>
	<a href='mailto:algross@gtallsports.com'>Al Gross</a>, Founder of GT All Sports</p>";
	$message .= "</body></html>";
	
	if(mail($contactEmail,'GTAS Event Confirmation',$message,$headers))
		return 1;	
	else
		return 0;	
}

#	@Name			:	formCaption
#	@Author			:	Matthew Umesh	
#	@Date			:	Jan 15,2014
#	@Purpose		:	to create form caption
#	@Argument		:	$field -  field name,$requiredFields -  validation array
function formCaption($field,$requiredFields)
{
	$str='';
	$pieces = preg_split('/(?=[A-Z])/',$field);
	$str=implode(' ',$pieces);
	if(array_key_exists($field,$requiredFields))
		$str=$str.'<span> *</span>';
	$str = ucwords($str);
	$str = str_replace("Day Of", '"Day of"', $str);	
	$str = str_replace("-public", '-Public', $str);	
	$str = str_replace("-admin", '-Admin', $str);	
	return $str;
}

#	@Name			:	changeDateFormat
#	@Author			:	Matthew Umesh	
#	@Date			:	Jan 20,2013
#	@Purpose		:	change the date format to save into database
#	@Argument		:	data - date string which we need to change
function changeTimeFormat($data)
{
	return date('H:i:s',strtotime($data));
}

#	@Name			:	changeDateFormat
#	@Author			:	Matthew Umesh	
#	@Date			:	Jan 20,2013
#	@Purpose		:	change the date format to save into database
#	@Argument		:	data - date string which we need to change
function changeDateFormat($data)
{
	return date('Y-m-d',strtotime($data));
}

#	@Name			:	getEventTypes
#	@Author			:	Matthew Umesh	
#	@Date			:	Dec 30,2013
#	@Purpose		:	To get category
#	@Argument		:	NA
function getEventTypes()
{
	  $sql= "SELECT 
	                   Name
             FROM 
			           event_category
				ORDER BY Name";
					  
	    $result          = mysql_query($sql);  
		while($getResult = mysql_fetch_array($result))
		{ 	
		    $finalResult[]   = $getResult['Name'];         
		}  
	  return $finalResult;
}
#	@Name			:	placeHolderMessage
#	@Author			:	Matthew Umesh	
#	@Date			:	Jan 15,2014
#	@Purpose		:	to create form caption
#	@Argument		:	$field -  field name,$requiredFields -  validation array
function placeHolderMessage($field)
{
	$placeHolder = array('nameOfTheEvent'=> "Enter Event",
	'eventLocationName'=> "Enter Location Name",
	'eventLocationAddress'=> "Enter Address",
	'eventLocationCity'=> "Enter City",
	'eventLocationZipCode'=> "Enter Zip",
	'contactName'=> "Enter Contact Name",
	'contactEmailAddress'=> "Enter Email",
	'contactPhoneNumber-public'=> "Enter Phone",
	'contactPhoneNumber-admin'=> "Enter Phone",
	'earlyWeigh-inLocation'=> "Enter Location",
	'dayOfWeigh-inLocation'=> "Enter Location",
	'registrationLink'=> "http://",
	'linkToWebsite'=> "http://");
	return $placeHolder[$field];
}
# validation array - it keep all the fields which required on form
$requiredFields = array("nameOfTheEvent" => "Please Enter Event Name",
"eventCategory" => "Please Select Event Category",
"national/WorldRankingEvent" => "Please Select National Event",
"startDateOfEvent" => "Please Enter Start Date",
"startTimeOfEvent" => "Please Enter Start Time",
"eventLocationName"=> "Please Enter Event Location Name",
"eventLocationAddress"=> "Please Enter Event Location Address",
"eventLocationCity" => "Please Enter Event Location City",
"eventLocationState"=>"Please Enter Event Location State",
"eventLocationZipCode"=> "Please Enter Event Location Zip",
"contactName"=> "Please Enter Contact Name",
"contactEmailAddress" => "Please Enter Contact Email Address",
"contactPhoneNumber-admin"=> "Please Enter Contact Phone Number",
);

define("EVENTCREATED","1");
define("QUERYERROR","2");

?>
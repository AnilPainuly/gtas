<?php
//****************************************************************************************
    //Page Name       : generalFunction.php
    //Author Name     : Alan Anil
    //Date            : July 30 2013
    //Input Parameters: 
    //Purpose         : Inernal functions for fetching values on the basis of zipcode.
//**************************************************************************************** 
//  error_reporting(E_ALL);
//  ini_set("display_errors", 1);
/*====================================================================== */
?>
<link rel="stylesheet" href="style/style.css" type="text/css" media="screen" />
<?php
require 'connection.php';
function getEventInfo($getZipcode,$eventTypeInfo,$stDate='',$endDate='',$sDistance=0)
{	  
	  $getFinalresult   = '';
	  /* conversion of date according to database */
	  if(!empty($stDate))
	  {
	  	list($month, $day, $year)=split('[/]', $stDate);
		$stDate = $year.'-'.$month.'-'.$day;
	  }
	   if(!empty($endDate))
	  {
	  	list($month, $day, $year)=split('[/]', $endDate);
		$endDate = $year.'-'.$month.'-'.$day;
	  }
	   /* conversion of date according to database ends */	
	/*
	This condition added by Frank
	Date: Aug 30, 2013
	*/
	 // Removing 0 from month if month < 10
/*	$dateFrom = $stDate;
	list($month, $day, $year) = split('[/.-]', $dateFrom);
	if($month <10)
		$month = substr($month,1);
	$stDate = $month.'/'.$day.'/'.$year;
	if($stDate == '//')
		$stDate = "";
		
	$dateTo = $endDate;
	list($month, $day, $year) = split('[/.-]', $dateTo);
	if($month <10)
		$month = substr($month,1);
	$endDate = $month.'/'.$day.'/'.$year;
	
	if($endDate == '//')
		$endDate = "";	
	// end removing 0 from month
	
	//	$filter = 'DATE(order_date) >="'.date("Y-m-d",strtotime($dateFrom)).'"';
	
 */ 
 	if($stDate!='' && $endDate=='')
		$andEventCondition.= " AND Event_Date >='".$stDate."'";
	 if($endDate!='' && $stDate=='')
		$andEventCondition.= " AND Event_Date <='".$endDate."'";
	 if($stDate!='' && $endDate!='')
	 	$andEventCondition.= " AND (Event_Date >='".$stDate."' AND Event_Date <='".$endDate."')";
	 
	 
	foreach ($eventTypeInfo as $key => $value)
	{
		  if($key==0)
		  {
		  $andEventCondition .= " AND  (Event_Category LIKE '%$value%'";
			}
		  else
			{
			$andEventCondition .= " OR  Event_Category LIKE '%$value%'";
			}
	}	
		 if(!empty($eventTypeInfo))
		 {
		 	$andEventCondition .=")";
		 }
		$sql= "SELECT 
	                     *
                FROM 
		                 events
                WHERE 
		                 Event_ZIP = $getZipcode $andEventCondition";	
						
						 	  
	$result            = mysql_query($sql); 
	while($resultArray = mysql_fetch_array($result))
		{
			
				if($resultArray['Event_Name'] != '')
				{ 
				  if(!empty($eventTypeInfo))
	                {
						$checkEventCondition = $resultArray['Event_Category']; 
							$eventName         = $resultArray['Event_Name']; 
							$eventId           = $resultArray['Event_ID'];
							$eventZip          = $resultArray['Event_ZIP'];
							$eventSDate        = $resultArray['Event_Date'];
							list($year,$month,$date)=split('[-]',$eventSDate);
							$eventSDate = $month.'/'.$date.'/'.$year;
							$eventCategory     = $resultArray['Event_Category']; 
							$linkUrl           = "http://gtallsports.site-ym.com/events/event_details.asp?id=$eventId";
							$eventName         = "<a href='$linkUrl'  target='_blank'>$eventName</a>";
							$getFinalresult .=   " <tr>
										<td>$eventName</td>
										<td>$eventCategory</td>
										<td>$eventSDate</td>   
										
										<td class='centerText'>$sDistance</td>
								   </tr> ";
					}
					else
					{	
					 
							$eventName         = $resultArray['Event_Name']; 
							$eventId           = $resultArray['Event_ID'];
							$eventZip          = $resultArray['Event_ZIP'];
							$eventSDate        = $resultArray['Event_Date'];
							list($year,$month,$date)=split('[-]',$eventSDate);
							$eventSDate = $month.'/'.$date.'/'.$year;
							$eventCategory     = $resultArray['Event_Category'];
							$linkUrl           = "http://gtallsports.site-ym.com/events/event_details.asp?id=$eventId";
							$eventName         = "<a href='$linkUrl'  target='_blank'>$eventName</a>";
						    $getFinalresult .=   " <tr>
										<td>$eventName</td>
										<td>$eventCategory</td>
										<td>$eventSDate</td>    
										
										<td class='centerText'>$sDistance</td>
								   </tr> ";
					}
				}
	    }  
	 return $getFinalresult ;
	
}

function getEventInfoForPrint($getZipcode,$eventTypeInfo,$stDate='',$endDate='',$sDistance=0)
{
	  $getFinalresult   = '';
	  $getFinalresultArray=array();
	  /* conversion of date according to database */
	  if(!empty($stDate))
	  {
	  	list($month, $day, $year)=split('[/]', $stDate);
		$stDate = $year.'-'.$month.'-'.$day;
	  }
	   if(!empty($endDate))
	  {
	  	list($month, $day, $year)=split('[/]', $endDate);
		$endDate = $year.'-'.$month.'-'.$day;
	  }
 	if($stDate!='' && $endDate=='')
		$andEventCondition.= " AND Event_Date >='".$stDate."'";
	 if($endDate!='' && $stDate=='')
		$andEventCondition.= " AND Event_Date <='".$endDate."'";
	 if($stDate!='' && $endDate!='')
	 	$andEventCondition.= " AND (Event_Date >='".$stDate."' AND Event_Date <='".$endDate."')";
	 
	 
	foreach ($eventTypeInfo as $key => $value)
	{
		  if($key==0)
		  {
		  $andEventCondition .= " AND  (Event_Category LIKE '%$value%'";
			}
		  else
			{
			$andEventCondition .= " OR  Event_Category LIKE '%$value%'";
			}
	}	
		 if(!empty($eventTypeInfo))
		 {
		 	$andEventCondition .=")";
		 }
		$sql= "SELECT 
	                     *
                FROM 
		                 events
                WHERE 
		                 Event_ZIP = $getZipcode $andEventCondition";	
						
						 	  
	$result            = mysql_query($sql); 
	while($resultArray = mysql_fetch_array($result))
		{
			
				if($resultArray['Event_Name'] != '')
				{ 
				  if(!empty($eventTypeInfo))
	                {
						$checkEventCondition = $resultArray['Event_Category']; 
							$eventName         = $resultArray['Event_Name']; 
							$eventId           = $resultArray['Event_ID'];
							$eventZip          = $resultArray['Event_ZIP'];
							$eventSDate        = $resultArray['Event_Date'];
							list($year,$month,$date)=split('[-]',$eventSDate);
							$eventSDate = $month.'/'.$date.'/'.$year;
							$eventCategory     = $resultArray['Event_Category']; 
							
							$getFinalresult .=   " <tr>
										<td>$eventName</td>
										<td>$eventCategory</td>
										<td>$eventSDate</td>   
										
										<td class='centerText'>$sDistance</td>
								   </tr> ";
							$getFinalresultArray[]=array($eventName,$eventCategory,$eventSDate,$sDistance);
					}
					else
					{	
					 
							$eventName         = $resultArray['Event_Name']; 
							$eventId           = $resultArray['Event_ID'];
							$eventZip          = $resultArray['Event_ZIP'];
							$eventSDate        = $resultArray['Event_Date'];
							list($year,$month,$date)=split('[-]',$eventSDate);
							$eventSDate = $month.'/'.$date.'/'.$year;
							$eventCategory     = $resultArray['Event_Category'];
						
						    $getFinalresult .=   " <tr>
										<td>$eventName</td>
										<td>$eventCategory</td>
										<td>$eventSDate</td>    
										
										<td class='centerText'>$sDistance</td>
								   </tr> ";
						   	$getFinalresultArray[]=array($eventName,$eventCategory,$eventSDate,$sDistance);
					}
				}
	    }  
	return $getFinalresultArray ;
	
}
function getEventTypes()
{
	  $eventId            = '';
	  $getFinalTyperesult = '';	
	  $sql= "SELECT 
	                   *
             FROM 
			           event_category";
					  
	    $result          = mysql_query($sql);  
		while($getResult = mysql_fetch_array($result))
		{ 	
		    $finalResult[]   = $getResult['Name'];         
		}  
	  return $finalResult;
}
function insertOrUpdateValue($getEventName,$getZipcode,$getEventType,$getStartDate, $getEventId)
{ 
	  $finalEventType     = implode(",",$getEventType);	
	  $eventId            = '';
	  $todayTime          = date("Y-m-d"); 
	  $getFinalTyperesult = '';	
	  $confirmMsg = '';
	  $sql= "SELECT
	                            *
             FROM
			                    events
             WHERE 
			                    Event_Name LIKE '$getEventName' AND Event_ID = $getEventId";
					  
	    $result          = mysql_query($sql);  
		$resultArray     = mysql_fetch_array($result); 
		if($resultArray != '')
		{ 
			$eventInnerQuery = "SET 
			                             Event_ID = $getEventId, Event_Name = '$getEventName', 
			                             Event_ZIP  = '$getZipcode',Event_Date = '$getStartDate'";
			if($getEventType != '0')
			{
				$eventInnerQuery .= ", Event_Category = '$finalEventType' ";
			}
			$sql = "UPDATE  
			                         events  
					                 $eventInnerQuery  
					WHERE 
					                 Event_Name = '$getEventName' ";
					$result          = mysql_query($sql);
					if($result)
					{				 
						$confirmMsg =  "Event Updated";	
	  	            }			
		}
		else
		{  
			if($finalEventType != '0')
			{
				  $sql = "INSERT INTO 
			                     events (Event_ID, Event_Name ,Event_ZIP ,Event_Date ,Event_Category)
                    VALUES       ($getEventId, '$getEventName', '$getZipcode', '$getStartDate', '$finalEventType') ";
			}
			else
			{
				$sql = "INSERT INTO 
									 events (Event_ID, Event_Name ,Event_ZIP ,Event_Date)
						VALUES       ($getEventId, '$getEventName', '$getZipcode', '$getStartDate') ";
			}		
					
					
					$result          = mysql_query($sql);
					if($result)
					{				 
						$confirmMsg = "Event Added";	
	  	            }			
		} 
		return $confirmMsg;
		 
}

function getEventInfoForEdit($getEventId)
{
	  $sql= "SELECT
	                            *
             FROM
			                    events
             WHERE 
			                    Event_ID = $getEventId";
					  
	    $result          = mysql_query($sql);  
		$getResult = mysql_fetch_array($result) ;
		     
	  return $getResult;
}			
#	Name		:	stateList
#	Author		:	Matthew Umesh	
#	Date		:	Dec 30,2013
#	Purpose		:	To return state List
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
#	Name		:	stateDropDown
#	Author		:	Matthew Umesh	
#	Date		:	Dec 30,2013
#	Purpose		:	To return state dropdown
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
?>
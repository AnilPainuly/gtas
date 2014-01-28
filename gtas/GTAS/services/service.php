<?php
/*
$server ->register(
            "arryCheck", // name of function   
			array(" "=>' '), // inputs   
			array("response"=>'SOAP-ENC:Array') // outputs  
		    );
*/			
require 'lib/nusoap.php';
require 'functions.php';
require 'connection.php';
$server = new nusoap_server();
$server ->configureWSDL("EventDetails", "EventService");
$server ->register(
            "eventsDataImport", // name of function     
			array('Event_ID'=>'xsd:int',
				'Event_Name'=>'xsd:string',
				'Event_Date'=>'xsd:string'), // inputs
			array('return' => 'xsd:string'),      // output parameters
				'urn:EventService',                      // namespace
				'urn:EventService#eventsDataImport',                // soapaction
				'rpc',                                // style
				'encoded',                            // use
				'displays EventDetails'           		  // documentation
          );

function eventsDataImport($eventId, $getEventName, $eventDate)
{ 
	  $todayTime          = $eventDate; 
	  $getFinalTyperesult = '';	
	  $resultStatus       = "Failure";
	  $eventInnerQuery    = ''; 
	  $sql = 'check';
	   $sql= "SELECT
	                            *
             FROM
			                    events
             WHERE 
			                    Event_Name LIKE '$getEventName'";
					  
	    $result          = mysql_query($sql);  
		$resultArray     = mysql_fetch_array($result); 
		if($resultArray != '')
		{ 
			$eventInnerQuery = ""; 
		       $sql = "UPDATE  
			                              events  
					    SET               Event_ID = $eventId, 
									      Event_Name = '$getEventName', 
										  Event_Date = '$todayTime'  
					    WHERE 
					                 Event_Name = '$getEventName' ";
					$result          = mysql_query($sql);
					if($result)
					{				 
						//echo "Event Updated";
						$resultStatus = "Update Successfull";	
	  	            }			
		}
		else
		{   
				$sql = "INSERT INTO 
									 events (Event_ID, Event_Name ,Event_Date)
						VALUES       ('$eventId', '$getEventName', '$todayTime') ";
			 	
					
					$result          = mysql_query($sql);
					if($result)
					{				 
						//echo "Event Added";	
						$resultStatus = "Insert Successfull";	
	  	            }			
		} 
		//return $resultStatus; 
		return $resultStatus;
}			
			 
			
$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : ' ' ;
$server->service($HTTP_RAW_POST_DATA);		
?>

 
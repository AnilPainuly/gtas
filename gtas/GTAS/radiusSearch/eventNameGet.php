<?php 
error_reporting(E_ALL);
ini_set("display_errors", 0);
//sleep( 1 ); -- example sleeps 3
define("MAX_MATCHES", 10); 
$MIN_CHARS = 3; 
$q = NULL;
if(isset($_GET['term']))
	$q = $_GET['term'];

// remove slashes if they were magically added
if (get_magic_quotes_gpc()) $q = stripslashes($q);
	
$format = 'json';

// Require the query string
if ($q)
{
	if (strlen($q) >= $MIN_CHARS)
	{		
		$queryResult = getEventName($q, MAX_MATCHES);
			
		// Now, get the total count for the queryStr and category for the search.
		if ($queryResult)
		{
			$result = array();
			if(mysql_num_rows($queryResult)) {
				while($match = mysql_fetch_assoc($queryResult)) {
					array_push($result, array("label"=>'['.$match['Event_ID'].'] '.$match['Event_Name'], "value" => $match['Event_ID']));
				}
			}
			/* output in necessary format */
			if($format == 'json') {
				header('Content-type: application/json');
				echo json_encode($result);
			}
			else {
				header('Content-type: text/xml');			
			}		
		}			
		else{
			if($format == 'json') {
				header('Content-type: application/json');
				echo "{No Match Found for $q}";
			}
		}
	}
	else
	{
		if($format == 'json') {
			header('Content-type: application/json');
			echo "{Minimum of $MIN_CHARS characters required }";
		}
	}
}	
else{
	if($format == 'json') {
		header('Content-type: application/json');
		echo "{Minimum of $MIN_CHARS characters required }";
	}
}

function getEventName($eventName, $maxResults)
	{   
		if ($maxResults <= 0) $maxResults = 10;
					$sql = "SELECT Event_ID ,Event_Name 
							FROM  events
							WHERE (Event_Name like '%$eventName%' )   
							LIMIT 0, $maxResults
				";
		$result = mysql_query($sql);
		
		if ($result) return $result;
		return null;
	}	
?>
<?php
include_once("connection.php");
	$q=$_GET['q'];
	$eventName=mysql_real_escape_string($q);
	$sql="SELECT Event_ID ,Event_Name 
		FROM  events
		WHERE (Event_Name like '%$eventName%' )    ";
	$result = mysql_query($sql);
	
	if($result)
	{
		while($row=mysql_fetch_array($result))
		{
			echo $row['Event_Name']."(".$row['Event_ID'].")"."\n";
		}
	}
?>
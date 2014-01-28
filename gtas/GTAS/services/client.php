<?php
require 'lib/nusoap.php';
//require 'functions.php';
$client = new nusoap_client("http://s376341053.onlinehome.us/sites/GTAS/services/service.php?wsdl");
 
$price = $client->call('eventsDataImport',array("Event_ID"=>123,"Event_Name"=>"Final Test Event 2","Event_Date"=>"2013-08-01"));
 
echo "<pre>";
print_r($price);
echo "</pre>";
 	
?>
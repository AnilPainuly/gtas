<?php
#	Name		:	publishEvent
#	Author		:	Matthew Umesh
#	Date		:	Dec 27,2013
#	Purpose		:	to publish the event.
ini_set("display_errors",'0');
/*$con = mysql_connect("localhost","root","password");
mysql_select_db('test');*/
include_once 'generalFunction.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Create Event Form</title>

<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Droid+Sans:400,700%7CUbuntu:400,500italic,400italic,700&amp;subset=latin,latin">

<link rel="stylesheet" type="text/css" href="selectListLib/style.css" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.js"></script>

<link rel="stylesheet" type="text/css" href="selectListLib/jquery-ui.css" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js"></script>




<link rel="stylesheet" href="style/style.css" type="text/css" media="screen" />



	<link rel="stylesheet" type="text/css" media="print" href="style/print.css">
		<script type="text/javascript" src="js/browserselector.js"></script> 






</head>

<body>
	<div class='container'>
<div class='mainHeader'>
	
	<div class='headerInner'>
	<div class='headerLogo'><a href='http://www.gtallsports.com'><img src='style/images/header.png'></a></div>
	<div class='toolbar'>
		<div id="toolbarMenu">
			<a href="http://www.gtallsports.com">Home</a>
				 &nbsp; | &nbsp; 	<a href="https://gtallsports.site-ym.com/general/?type=CONTACT">Contact Us</a>
					 &nbsp; | &nbsp; <a href="https://gtallsports.site-ym.com/login.aspx">Sign In</a>
					  &nbsp; | &nbsp; <a href="https://gtallsports.site-ym.com/general/register_member_type.asp?">Register</a>
			</div>
		</div>
		<div class='logoContent'>
			
			<div class='siteContent'></div>
					
				
			
		</div>
	</div>
	
</div>	

<div class="mainDiv">
<div class="mainInnerDiv">
	<div class="contentDiv">
<!--<div class="spTitleBar topHeading">Create Event Form</div>-->
<div class="createEventHeading">Event Publish Form</div>


<?php 
$id=$_GET['id'];
$query="UPDATE newEvents SET status_=1 WHERE id=$id";
$res = mysql_query($query);

	if(mysql_affected_rows() == 0)
		echo "<h3>Not found any pending event, either event is already activated or event not exists. </h3>";
	else	
	{
		echo "Your Event is activated. Now it is visible in <a href='http://s376341053.onlinehome.us/sites/GTAS/radiusSearch/publicEventSearch.php'>public search page</a>";
	}

 ?> 
</div>
</div>
</div>
</div>
<div id="Copyright">
			Membership Software Powered by <a href="http://www.yourmembership.com/">YourMembership.com<span style="font-size:11px">&reg;</span></a> &nbsp;::&nbsp; <a href="https://gtallsports.site-ym.com/ams/legal-privacy.htm">Legal/Privacy</a><!-- Copyright (c) 1998-2013 YourMembership.com Inc. All Rights Reserved. Copyright: Certain elements of this website are: Copyright (c) 1998-2013, YourMembership.com, Incorporated. YourMembership.com, Incorporated provides a limited license to use its Copyrights to the entity from whose web page you are viewing. Certain elements of this website may also be copyrighted by that entity; please see its Terms of Use or contact the organization for more information. General information about copyright laws can be found at: http://www.copyright.gov/. For more specific information, please consult an attorney. -->
</div>
</body>
</html>
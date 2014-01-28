<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php
#	Name		:	eventForm
#	Author		:	Matthew Umesh
#	Date		:	Dec 24,2013
#	Purpose		:	to get the data from members for event

$id = $_GET['id'] == '' ? 3 : $_GET['id'];
include_once 'generalFunction.php';
$query = "SELECT 
			id,
			if(startDateOfEvent='0000-00-00','',date_format(startDateOfEvent,'%m/%d/%Y')) as startDateOfEvent,
			if(startTimeOfEvent='00:00:00','',date_format(startTimeOfEvent,'%h:%i %p')) as startTimeOfEvent,
			if(endDateOfEvent='0000-00-00','',date_format(endDateOfEvent,'%m/%d/%Y')) as endDateOfEvent,
			if(endTimeOfEvent='00:00:00','',date_format(endTimeOfEvent,'%h:%i %p')) as endTimeOfEvent,
			if(registrationCutOffDate='0000-00-00','',date_format(registrationCutOffDate,'%m/%d/%Y')) as registrationCutOffDate,
			if(`earlyWeigh-inDate`='0000-00-00','',date_format(`earlyWeigh-inDate`,'%m/%d/%Y')) as earlyWeighInDate,
			if(`earlyWeigh-inTime`='00:00:00','',date_format(`earlyWeigh-inTime`,'%h:%i %p')) as earlyWeighInTime,
			if(`earlyWeigh-inTime_`='00:00:00','',date_format(`earlyWeigh-inTime_`,'%h:%i %p')) as earlyWeighInTime_,
			if(`dayOfEventWeigh-inTime`='00:00:00','',date_format(`dayOfEventWeigh-inTime`,'%h:%i %p')) as dayOfEventWeighInTime,
			if(`dayOfEventWeigh-inTime_`='00:00:00','',date_format(`dayOfEventWeigh-inTime_`,'%h:%i %p')) as dayOfEventWeighInTime_,
			if(eventCreatedDate_='0000-00-00','',date_format(eventCreatedDate_,'%m/%d/%Y')) as eventCreatedDate,
			if(LastUpdated_='0000-00-00','',date_format(LastUpdated_,'%m/%d/%Y')) as LastUpdated_,
			nameOfTheEvent,
			eventCategory,
			if(`national/WorldRankingEvent`=0,'NO','YES') as `national/WorldRankingEvent`,
			eventLocationName,
			eventLocationAddress,
			eventLocationCity,
			eventLocationState,
			eventLocationZipCode,
			contactName,
			contactEmailAddress,
			`contactPhoneNumber-public`,
			registrationLink,
			linkToWebsite,
			`earlyWeigh-inLocation`,
			`dayOfWeigh-inLocation`,
			`eventImage/logoUpload`,
			uploadFlyerForEvent,
			fileName_,
			enterEventDetails
		FROM 
			newEvents 
		WHERE 
			id= '$id' AND 
			status_=1";
$result = mysql_query($query);
$row=mysql_fetch_assoc($result);

?>

<head>
<title>Event Details</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
			<div class='siteContent'>
            </div>
		</div>
	</div>
</div>	

<div class="mainDiv">
<div class="mainInnerDiv">

	<div class="contentDiv">
   
<!--<div class="spTitleBar topHeading">Create Event Form</div>-->
<div class="createEventHeading">Event Detail</div>
<?php ob_start();?>
<div class="eventDetailsSection">
		<h2><?php echo ucwords(stripslashes($row['nameOfTheEvent']))?></h2>
		<div class="eventDetailsImg"><?php if($row['eventImage/logoUpload'] != '') { ?>
        <img src="./uploads/<?php echo $row['eventImage/logoUpload']?>"  alt="" title="" /><?php } ?></div>
		<div class="eventDetailsRow">
			<div class="eventDetailsLeft">
				Event Category
			</div><!-- /.eventDetailsLeft -->
			<div class="eventDetailsDivider">:</div>
			<div class="eventDetailsRight"><?php 
			$eventCategory     = str_replace(',',', ',$row['eventCategory']);
			echo $eventCategory?>
			</div><!-- /.eventDetailsRight -->
		</div><!-- /.eventDetailsRow -->
		
		<div class="eventDetailsRow">
			<div class="eventDetailsLeft">
				When
			</div><!-- /.eventDetailsLeft -->
			<div class="eventDetailsDivider">:</div>
			<div class="eventDetailsRight">
				<?php echo $row['startDateOfEvent']?><?php echo $row['endDateOfEvent']!='' ? " Through (".$row['endDateOfEvent'].")" : '' ; ?><br />
      			<?php echo $row['startTimeOfEvent']?><?php echo $row['endTimeOfEvent']!='' ? " Through (".$row['endTimeOfEvent'].")" : '' ; ?>
			</div><!-- /.eventDetailsRight -->
		</div><!-- /.eventDetailsRow -->
		
		<div class="eventDetailsRow">
			<div class="eventDetailsLeft">
				Where
			</div><!-- /.eventDetailsLeft -->
			<div class="eventDetailsDivider">:</div>
			<div class="eventDetailsRight">
				<?php echo stripslashes($row['eventLocationName'])?><br />
				<?php echo stripslashes($row['eventLocationAddress'])?><br />
				<?php echo stripslashes($row['eventLocationCity'])?>, <?php echo $row['eventLocationState']?> <?php echo $row['eventLocationZipCode']?><br />
			</div><!-- /.eventDetailsRight -->
		</div><!-- /.eventDetailsRow -->
		
		<div class="eventDetailsRow">
			<div class="eventDetailsLeft">
				Contact
			</div><!-- /.eventDetailsLeft -->
			<div class="eventDetailsDivider">:</div>
			<div class="eventDetailsRight">
				<?php echo $row['contactName']?><br /> 
				<a href="mailto:<?php echo $row['contactEmailAddress']?>"><?php echo $row['contactEmailAddress']?></a><br />
				Phone: <?php echo $row['contactPhoneNumber-public']?>
			</div><!-- /.eventDetailsRight -->
		</div><!-- /.eventDetailsRow -->
<?php 	if($row['earlyWeighInDate'] != '')
		{ ?>
		<div class="eventDetailsRow">
			<div class="eventDetailsLeft">
				Early Weigh In Date
			</div><!-- /.eventDetailsLeft -->
			<div class="eventDetailsDivider">:</div>
			<div class="eventDetailsRight">
				<?php echo $row['earlyWeighInDate']?>
			</div><!-- /.eventDetailsRight -->
		</div><!-- /.eventDetailsRow --><?php
		} 
		
		if($row['earlyWeighInTime'] != '')
		{ ?>
		<div class="eventDetailsRow">
			<div class="eventDetailsLeft">
				Early Weigh-In Time
			</div><!-- /.eventDetailsLeft -->
			<div class="eventDetailsDivider">:</div>
			<div class="eventDetailsRight">
				from <?php echo $row['earlyWeighInTime']?> to <?php echo $row['earlyWeighInTime_']?> 
			</div><!-- /.eventDetailsRight -->
		</div><!-- /.eventDetailsRow --><?php
		} 
		
		if($row['earlyWeigh-inLocation'] != '')
		{ ?>
		<div class="eventDetailsRow">
			<div class="eventDetailsLeft">
				Early Weigh-In Location
			</div><!-- /.eventDetailsLeft -->
			<div class="eventDetailsDivider">:</div>
			<div class="eventDetailsRight">
				<?php echo stripslashes($row['earlyWeigh-inLocation'])?><br />
			</div><!-- /.eventDetailsRight -->
		</div><!-- /.eventDetailsRow --><?php
		} 
		
		if($row['dayOfEventWeighInTime'] != '')
		{ ?>
		
		<div class="eventDetailsRow">
			<div class="eventDetailsLeft">
				"Day of" Weigh-In Time
			</div><!-- /.eventDetailsLeft -->
			<div class="eventDetailsDivider">:</div>
			<div class="eventDetailsRight">
				from <?php echo $row['dayOfEventWeighInTime']?> to <?php echo $row['dayOfEventWeighInTime_']?>
			</div><!-- /.eventDetailsRight -->
		</div><!-- /.eventDetailsRow --><?php
		} 
		
		if($row['dayOfWeigh-inLocation'] != '')
		{ ?>
		
		<div class="eventDetailsRow">
			<div class="eventDetailsLeft">
				"Day of" Weigh-In Location
			</div><!-- /.eventDetailsLeft -->
			<div class="eventDetailsDivider">:</div>
			<div class="eventDetailsRight">
				<?php echo stripslashes($row['dayOfWeigh-inLocation'])?>
			</div><!-- /.eventDetailsRight -->
		</div><!-- /.eventDetailsRow --><?php
		} 
		if($row['registrationCutOffDate'] != '')
		{ ?>
		
		<div class="eventDetailsRow">
			<div class="eventDetailsLeft">
            	Registration CutOff Date
			</div><!-- /.eventDetailsLeft -->
			<div class="eventDetailsDivider">:</div>
			<div class="eventDetailsRight">
				<?php echo $row['registrationCutOffDate']?>
			</div><!-- /.eventDetailsRight -->
		</div><!-- /.eventDetailsRow --><?php
		} 
		if($row['linkToWebsite'] != '')
		{ ?>
		
		<div class="eventDetailsRow">
			<div class="eventDetailsLeft">
				Link to Event Web Site
			</div><!-- /.eventDetailsLeft -->
			<div class="eventDetailsDivider">:</div>
			<div class="eventDetailsRight">
				<a href="<?php echo $row['linkToWebsite']?>" target="_blank"><?php echo $row['linkToWebsite']?></a>
			</div><!-- /.eventDetailsRight -->
		</div><!-- /.eventDetailsRow --><?php
		} 
		if($row['registrationLink'] != '')
		{ ?>
		
		<div class="eventDetailsRow">
			<div class="eventDetailsLeft">
				Registration Link to Event
			</div><!-- /.eventDetailsLeft -->
			<div class="eventDetailsDivider">:</div>
			<div class="eventDetailsRight">
				<a href="<?php echo $row['registrationLink']?>" target="_blank"><?php echo $row['registrationLink']?></a>
			</div><!-- /.eventDetailsRight -->
		</div><!-- /.eventDetailsRow --><?php
		} 
		if($row['uploadFlyerForEvent'] != '')
		{ ?>
		
		<div class="eventDetailsRow">
			<div class="eventDetailsLeft">
				Download Flyer for Event
			</div><!-- /.eventDetailsLeft -->
			<div class="eventDetailsDivider">:</div>
			<div class="eventDetailsRight">
            <?php 	if(strpos($row['uploadFlyerForEvent'],",") === false)
					{ ?>
						<a href="./uploads/<?php echo $row['uploadFlyerForEvent'];?>" target="_blank"><?php echo $row['fileName_'];?></a>
<?php				}
					else
					{
						$tempArray = explode(',',$row['uploadFlyerForEvent']);
						$fileNameArray = explode(',',$row['fileName_']);
						$counter=0;
						foreach($tempArray as $val)
						{ 
							if($counter != 0)
								echo ', '; 	?><a href="./uploads/<?php echo $val;?>" target="_blank"><?php echo $fileNameArray[$counter];?></a><?php						++$counter;
						}
					} 
			
			?>
				
			</div><!-- /.eventDetailsRight -->
		</div><!-- /.eventDetailsRow --><?php
		} ?>
		<!-- /.eventDetailsRow -->
	</div><!-- /.eventDetailsSection --><?php 
	if($row['enterEventDetails'] != '')
	{ ?>
	<div class="eventDetailsBottom">EVENT DETAILS</div> 
	<div class="eventDetailsSection">
	<?php echo stripslashes($row['enterEventDetails'])?>
	</div><?php
	}	?>
    
<?php $out2 = ob_get_contents(); ?>
</div>

<div align="center" style="padding-bottom:10px;">
<input type="button" value="Print" onclick="printEvent();" class="eventbutton">
</div>
</div>
</div>
</div>

<div id="Copyright">
			Membership Software Powered by <a href="http://www.yourmembership.com/">YourMembership.com<span style="font-size:11px">&reg;</span></a> &nbsp;::&nbsp; <a href="https://gtallsports.site-ym.com/ams/legal-privacy.htm">Legal/Privacy</a><!-- Copyright (c) 1998-2013 YourMembership.com Inc. All Rights Reserved. Copyright: Certain elements of this website are: Copyright (c) 1998-2013, YourMembership.com, Incorporated. YourMembership.com, Incorporated provides a limited license to use its Copyrights to the entity from whose web page you are viewing. Certain elements of this website may also be copyrighted by that entity; please see its Terms of Use or contact the organization for more information. General information about copyright laws can be found at: http://www.copyright.gov/. For more specific information, please consult an attorney. -->
</div>
</body>
</html>
<?php
	include("MPDF/mpdf.php");

	$mpdf=new mPDF('c'); 
	$mpdf->SetDisplayMode('fullpage');
	$fileName = "temp/Event_".$row['id'].".pdf";
	$stylesheet = file_get_contents('style/pdfPrint.css');
	$mpdf->WriteHTML($stylesheet,1);
	$mpdf->WriteHTML($out2);
	$mpdf->Output($fileName);
?>
<script>
function printEvent()
{
	window.open("<?php echo $fileName ?>", '_blank');
}
</script>
<?php 
session_start();
// including functions and db connection 
include_once('commonFunctions.php');
	//****************************************************************************************
    //Page Name       : adminSearch
    //Author Name     : Frank Sunil
    //Date            : Jan 14, 2013
    //Input Parameters: 
    //Purpose         : Search events on the basis of filters.
	//**************************************************************************************** 
	#	@Name			:	dateChange
	#	@Author			:	Frank Sunil	
	#	@Date			:	Jan 15,2014
	#	@Purpose		:	to change date format (dd/mm/yyyy)
	#	@Argument		:	$date -  date	
	function dateChange($date)
	{
		return date('m/d/Y', strtotime($date));
	}
	if(!isset($_REQUEST['btnSearch']) )
	{
		$_REQUEST=$_SESSION['searchFilters'];
	}
 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Event Search - Admin</title>

	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Droid+Sans:400,700%7CUbuntu:400,500italic,400italic,700&amp;subset=latin,latin">
	<link rel="stylesheet" type="text/css" href="selectListLib/style.css" />
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.js"></script>

	<link rel="stylesheet" type="text/css" href="selectListLib/jquery-ui.css" />
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js"></script>
	<script src="./js/jquery-ui-timepicker-addon.js"></script>
	<link rel="stylesheet" type="text/css" href="selectListLib/jquery.multiselect.css" />
	<script type="text/javascript" src="selectListLib/src/jquery.multiselect.js"></script>

	<link rel="stylesheet" href="style/style.css" type="text/css" media="screen" />
	<link rel="stylesheet" type="text/css" media="print" href="style/print.css">
	<script type="text/javascript" src="js/browserselector.js"></script> 
	<!-- Scripts for datatables -->
	<script type="text/javascript" src="js/tablesort/jquery.js"></script> 
	<script type="text/javascript" src="js/tablesort/jquery.dataTables.min.js"></script> 
	<!-- script for table dort plugin -->
	<script type="text/javascript" src="js/scripts.js"></script>
	<script>
		var $j= jQuery.noConflict();
		$j(document).ready(function() 
			{ 
			   $j('#ReportTable').dataTable( {
					"sPaginationType": "full_numbers",
					"oLanguage": {
					"sEmptyTable": "No records found matching your search criteria. Please change your search criteria and try again."
							  }
				});
			} 
		); 
	</script>
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
<?php
	// Declaring variables..
	$jsonObject='';
	$eventTypesCounter=0;
	$getAllEventTypes  = getEventTypes(); 
	$getEventType	   = array();
	$finalDataResult   = '';
	
	if(isset($_REQUEST['eventType'])) {
		$getEventType    = $_REQUEST['eventType'];
	}
	$eventStartDate='';
	if(isset($_REQUEST['dayOfEventWeigh-inTime'])) {
		$eventStartDate = $_REQUEST['dayOfEventWeigh-inTime'];
	}
	$eventEndDate='';
	if(isset($_REQUEST['dayOfEventWeigh-inTime_'])) {
		$eventEndDate = $_REQUEST['dayOfEventWeigh-inTime_'];
	}
	
	$nationalChampionshipEvent='';
	if($_REQUEST['nationalChampionshipEvent']== '0' || $_REQUEST['nationalChampionshipEvent']== '1') {
		$nationalChampionshipEvent = $_REQUEST['nationalChampionshipEvent'];
	}
	
	$state='';
	if(isset($_REQUEST['state'])) {
		$state = $_REQUEST['state'];
	}
	
	$eventZipCode='';
	if(isset($_REQUEST['eventZipCode'])){
		$eventZipCode = $_REQUEST['eventZipCode'];
	}
	
	$fromCreatedDate='';
	if(isset($_REQUEST['fromCreatedDate'])) {
		$fromCreatedDate = $_REQUEST['fromCreatedDate'];
	}
	
	$toCreatedDate='';
	if(isset($_REQUEST['toCreatedDate'])) {
		$toCreatedDate = $_REQUEST['toCreatedDate'];
	}
	
	$contactMail='';
	if(isset($_REQUEST['contactMail'])) {
		$contactMail = $_REQUEST['contactMail'];
	}
	
	$fromLastEdit='';
	if(isset($_REQUEST['fromLastEdit'])) {
		$fromLastEdit = $_REQUEST['fromLastEdit'];
	}
	$toLastEdit='';
	if(isset($_REQUEST['toLastEdit'])){
		$toLastEdit = $_REQUEST['toLastEdit'];
	}
	
	// condition for form validation
	if(isset($_REQUEST['btnSearch']))
	{
		if(!empty($_REQUEST['contactMail'])) // validate email address
			if (!filter_var($_REQUEST['contactMail'], FILTER_VALIDATE_EMAIL)) 
				$errors[] = "Please enter valid Contact email";	
		
		
		// validation part: checking invalid date and comparing from data and to data
		if(!empty($_REQUEST['dayOfEventWeigh-inTime']) && !isValidDate($_REQUEST['dayOfEventWeigh-inTime'])) 
			$errors[] = " Invalid Event start date from";
			
		if(!empty($_REQUEST['dayOfEventWeigh-inTime_']) && !isValidDate($_REQUEST['dayOfEventWeigh-inTime_'])) 
			$errors[] = " Invalid Event start date to";
		
		if(!empty($_REQUEST['dayOfEventWeigh-inTime'])&&isValidDate($_REQUEST['dayOfEventWeigh-inTime'])&&!empty($_REQUEST['dayOfEventWeigh-inTime_'])&&isValidDate($_REQUEST['dayOfEventWeigh-inTime_']))
		{
			if ((strtotime($_REQUEST['dayOfEventWeigh-inTime'])) > (strtotime($_REQUEST['dayOfEventWeigh-inTime_']))){
				$errors[] = "Event start date to field can not be less than Event start date from.";
			}
		}
		
		if(!empty($_REQUEST['fromCreatedDate']) && !isValidDate($_REQUEST['fromCreatedDate'])) 
			$errors[] = " Invalid Event creation date from";
			
		if(!empty($_REQUEST['toCreatedDate']) && !isValidDate($_REQUEST['toCreatedDate'])) 
			$errors[] = " Invalid Event creation date to";
			
					
		if(!empty($_REQUEST['fromCreatedDate']) && isValidDate($_REQUEST['fromCreatedDate']) && !empty($_REQUEST['toCreatedDate']) && isValidDate($_REQUEST['toCreatedDate']))
		{
			if ((strtotime($_REQUEST['fromCreatedDate'])) > (strtotime($_REQUEST['toCreatedDate']))){
				$errors[] = "Event creation date to field can not be less than Event creation date from.";
			}
		} 
		
		if(!empty($_REQUEST['fromLastEdit']) && !isValidDate($_REQUEST['fromLastEdit'])) 
			$errors[] = " Invalid Last edited date from";
			
		if(!empty($_REQUEST['toLastEdit']) && !isValidDate($_REQUEST['toLastEdit'])) 
			$errors[] = " Invalid Last edited date to";
				
		if(!empty($_REQUEST['fromLastEdit'])&&isValidDate($_REQUEST['fromLastEdit']) && !empty($_REQUEST['toLastEdit'])&&isValidDate($_REQUEST['toLastEdit']))
		{
			if ((strtotime($_REQUEST['fromLastEdit'])) > (strtotime($_REQUEST['toLastEdit']))){
				$errors[] = "Last edited date to field can not be less than Last edited date from.";
			}
		}  
	}
	// End validation part
?>

<div class="mainDiv">
	<div class="mainInnerDiv">
		<div class="contentDiv">
			<div class="adminSearch">
				<div class="createEventHeading">Event Search - Admin</div>
				<?php
				// Display error messages..
				if(!empty($errors))
				{  ?>		
					<div class="error">
					<?php foreach($errors as $val)
							echo $val.'<br>';	?>
					</div><?php
				}	?>
				<form name="adminSearch" method="post" id="radiusSearchFormId">
					<div class="createEventSection">
						<div class="createEventRow">
							<div class="createEventCell">Event Start Date</div><!-- /.createEventCell -->
							<div class="createEventCell2">
								<input type="text" name="dayOfEventWeigh-inTime" readonly="readonly" id="dayOfEventWeigh-inTime" value="<?php echo isset($_REQUEST['dayOfEventWeigh-inTime']) != ''? $_REQUEST['dayOfEventWeigh-inTime'] : ''; ?>" placeholder="From" class="timeTextBox">
								<input type="button" id="idClear" onclick="clearData('dayOfEventWeigh-inTime','')" title="Clear" class="clear"  name="" >
								<input type="text" name="dayOfEventWeigh-inTime_" readonly="readonly" id="dayOfEventWeigh-inTime_" value="<?php echo isset($_REQUEST['dayOfEventWeigh-inTime_']) != ''? $_REQUEST['dayOfEventWeigh-inTime_'] : ''; ?>" placeholder="To" class="timeTextBox">
								<input type="button" id="idClear" onclick="clearData('dayOfEventWeigh-inTime_','')" title="Clear" class="clear"  name="" >
								<script> 
								$( "#dayOfEventWeigh\\-inTime" ).datepicker(); 
								$( "#dayOfEventWeigh\\-inTime_" ).datepicker(); 
								</script>
							</div><!-- /.createEventCell2 -->
						</div><!-- /.createEventRow -->
			
						<div class="createEventRow">
							<div class="createEventCell">
								National / World Championship Event
							</div><!-- /.createEventCell -->
							<div class="createEventCell2">
								<select name="nationalChampionshipEvent">
									<option value='' <?php echo $_REQUEST['nationalChampionshipEvent'] == '' ? "selected='selected'" : '' ; ?>>All</option>
									<option value='0' <?php echo $_REQUEST['nationalChampionshipEvent'] == '0' && isset($_REQUEST['nationalChampionshipEvent'])  ? "selected='selected'" : '' ; ?>>No</option>
									<option value='1' <?php echo $_REQUEST['nationalChampionshipEvent'] == '1' ? "selected='selected'" : '' ; ?>>YES</option>
								</select>
							</div><!-- /.createEventCell2 -->
						</div><!-- /.createEventRow -->
			
						<div class="createEventRow">
							<div class="createEventCell">
								Event State
							</div><!-- /.createEventCell -->
							<div class="createEventCell2">
								<?php stateDropDown(isset($_REQUEST['state']) != ''? $_REQUEST['state'] : '',"state");?>
							</div><!-- /.createEventCell2 -->
						</div><!-- /.createEventRow -->
			
						<div class="createEventRow">
							<div class="createEventCell">
								Event Zip
							</div><!-- /.createEventCell -->
							<div class="createEventCell2">
								<input type="text" name="eventZipCode" value="<?php echo isset($_REQUEST['eventZipCode']) != ''? $_REQUEST['eventZipCode'] : ''; ?>" placeholder="Enter Zip" 
									onKeyPress="return onlynum(event)" />
							</div><!-- /.createEventCell2 -->
						</div><!-- /.createEventRow -->
			
						<div class="createEventRow">
							<div class="createEventCell">
								Event Category
							</div><!-- /.createEventCell -->
							<div class="createEventCell2">
							<?php $temp =''; ?>
							<select name="eventType[]" id="eventType" multiple="multiple"  class="dropDown"> 
							
							 <?php foreach($getAllEventTypes as $filterEvents)
							 { ?>
								 <option value='<?php echo $getAllEventTypes[$eventTypesCounter] ?>'
								 <?php  
								 foreach ($getEventType as $multiselCategory) {
								 if($multiselCategory == $getAllEventTypes[$eventTypesCounter]) 
								 { echo "selected= 'selected'"; }
								 }
								 ?> >
								 <?php echo $getAllEventTypes[$eventTypesCounter] ?></option>
							 <?php     
								 $eventTypesCounter++;
							 }
							 ?>
						 </select>
							</div><!-- /.createEventCell2 -->
						</div><!-- /.createEventRow -->
			
						<div class="createEventRow">
							<div class="createEventCell">
								Event Creation Date
							</div><!-- /.createEventCell --> 
							<div class="createEventCell2">
								<input type="text" name="fromCreatedDate" readonly="readonly" id="fromCreatedDate" value="<?php echo isset($_REQUEST['fromCreatedDate']) != ''? $_REQUEST['fromCreatedDate'] : ''; ?>" placeholder="From" class="timeTextBox">
								<input type="button" id="idClear" onclick="clearData('fromCreatedDate','')" title="Clear" class="clear"  name="" >
								<input type="text" name="toCreatedDate" readonly="readonly" id="toCreatedDate" value="<?php echo isset($_REQUEST['toCreatedDate']) != ''? $_REQUEST['toCreatedDate'] : '' ;?>" placeholder="To" class="timeTextBox">
								<input type="button" id="idClear" onclick="clearData('toCreatedDate','')" title="Clear" class="clear"  name="" >
								 <script> 
								$( "#toCreatedDate" ).datepicker(); 
								$( "#fromCreatedDate" ).datepicker(); 
								</script>
							</div><!-- /.createEventCell2 -->
						</div><!-- /.createEventRow -->
			
						<div class="createEventRow">
							<div class="createEventCell">
								Contact Email 
							</div><!-- /.createEventCell -->
							<div class="createEventCell2">
								<input type="text" name="contactMail" value="<?php echo isset($_REQUEST['contactMail']) != ''? $_REQUEST['contactMail'] : '' ;?>" placeholder="Enter Email" />
							</div><!-- /.createEventCell2 -->
						</div><!-- /.createEventRow -->
			
						<div class="createEventRow">
							<div class="createEventCell">
								Last Edited Date
							</div><!-- /.createEventCell --> 
							<div class="createEventCell2">
								<input type="text" name="fromLastEdit" readonly="readonly" id="fromLastEdit" value="<?php echo isset($_REQUEST['fromLastEdit']) != ''? $_REQUEST['fromLastEdit'] : '' ;?>" placeholder="From" class="timeTextBox">
								<input type="button" id="idClear" onclick="clearData('fromLastEdit','')" title="Clear" class="clear"  name="" >
								<input type="text" name="toLastEdit" readonly="readonly" id="toLastEdit" value="<?php echo isset($_REQUEST['toLastEdit']) != ''? $_REQUEST['toLastEdit'] : '' ;?>" placeholder="To" class="timeTextBox">
								<input type="button" id="idClear" onclick="clearData('toLastEdit','')" title="Clear" class="clear"  name="" >
								  <script> 
								$( "#toLastEdit" ).datepicker(); 
								$( "#fromLastEdit" ).datepicker(); 
								</script>
							</div><!-- /.createEventCell2 -->
						</div><!-- /.createEventRow -->
			
			
						<div class="createEventButtons">
							<input type="submit" name="btnSearch" value="Search" class="eventbutton" title="Search Event" />
							<input type="button" name="printButton"  onclick="PrintElem('ReportTable')" value="Print" class="formbutton buttonClass" title="Print Page"/>
							<input type="button" id="exportToExcel" onClick="exportTable11()" name="exportToExcel" value="Export to Excel" class="formbutton buttonClass" title="Export Data To Excel File"/>
						</div><!-- /.createEventButtons -->
					</div><!-- /.createEventSection -->
				</form>
			</div>
		</div>
 <?php

 	// Start search condition..
	if(isset($_REQUEST['btnSearch']))
	{
		$_SESSION['searchFilters']=$_REQUEST;
		if(empty($errors))
		{
		
			$selectedState = '';
			foreach($getEventType as $name)
			{
				$selectedState =$selectedState." , '".$name."'";
			}
			$selectedState= trim(substr($selectedState,2));
			
			if(!empty($eventStartDate) && !empty($eventEndDate)){
				$sDate = " AND startDateOfEvent BETWEEN '".changeDateFormat($eventStartDate)."' AND '".changeDateFormat($eventEndDate)."'";
			}elseif	(!empty($eventStartDate)){
				$sDate = " AND startDateOfEvent >= '".changeDateFormat($eventStartDate)."'";
			}
			elseif	(!empty($eventEndDate)){
				$sDate = " AND startDateOfEvent <= '".changeDateFormat($eventEndDate)."'";
			}else
				$sDate="";
			
			
			//if(!empty($nationalChampionshipEvent))
			if($nationalChampionshipEvent == '0' || $nationalChampionshipEvent == '1')
				$championshipEvent = " AND `national/WorldRankingEvent` = '".$nationalChampionshipEvent."'";
			else
				$championshipEvent = "";	
			
			if(!empty($state))
				$stateValue = " AND eventLocationState = '".$state."'";
			else
				$stateValue = "";
				
			if(!empty($eventZipCode))
				$eventZipCode = " AND eventLocationZipCode = '".$eventZipCode."'";
			else
				$eventZipCode = "";
				
				if(!empty($contactMail))
				$contactMail = " AND contactEmailAddress = '".$contactMail."'";
			else
				$contactMail = "";	
			
			
			if(!empty($fromCreatedDate) && !empty($toCreatedDate)){
				$eventCreatedDate_ = " AND eventCreatedDate_ BETWEEN '".changeDateFormat($fromCreatedDate)."' AND '".changeDateFormat($toCreatedDate)."'";
			}elseif	(!empty($fromCreatedDate)){
				$eventCreatedDate_ = " AND eventCreatedDate_ >= '".changeDateFormat($fromCreatedDate)."'";
			}
			elseif	(!empty($toCreatedDate)){
				$eventCreatedDate_ = " AND eventCreatedDate_ <= '".changeDateFormat($toCreatedDate)."'";
			}else
				$eventCreatedDate_="";
				
			if(!empty($fromLastEdit) && !empty($toLastEdit)){
				$lastEditDate = " AND SUBSTRING(LastUpdated_,1, 10) BETWEEN '".changeDateFormat($fromLastEdit)."' AND '".changeDateFormat($toLastEdit)."'";
			}elseif	(!empty($fromLastEdit)){
				$lastEditDate = " AND SUBSTRING(LastUpdated_,1, 10) >= '".changeDateFormat($fromLastEdit)."'";
			}
			elseif	(!empty($toLastEdit)){
				$lastEditDate = " AND SUBSTRING(LastUpdated_,1, 10) <= '".changeDateFormat($toLastEdit)."'";
			}else
				$lastEditDate="";	
			
			
			$query = "SELECT `id`, `nameOfTheEvent`, `eventCategory`, `startDateOfEvent`, `eventLocationState`, `national/WorldRankingEvent`
						FROM newEvents WHERE
						status_=1
						$contactMail 
						$sDate
						$championshipEvent
						$stateValue
						$eventZipCode
						$eventCreatedDate_
						$lastEditDate
						";
			$categoryString ='';			
			foreach ($getEventType as $key => $value)
				$categoryString .= $categoryString =='' ? " (eventCategory LIKE '%$value%'" : " OR eventCategory LIKE '%$value%'" ;
			
			$query .= $categoryString != "" ? " AND $categoryString )" : '';
			$query .= " ORDER BY `nameOfTheEvent`";
	
			$sqlResult = mysql_query($query);
			// Table heading
			$finalDataResult .= "<div class='eventTableClass'>
				<table id='ReportTable' class='tablesorter'>
				<thead><tr>
				<th width='35%'><span>Event Name</span></th>
				<th width='35%'><span>Event Type</span></th>
				<th width='8%'><span>Event Start Date</span></th>
				<th width='8%' class='centerText'><span>N/W EVENT</span></th>
				<th width='8%' class='centerText'><span>STATE</span></th>
				<th width='6%' class='centerText'><span>Edit</span></th>
				</tr></thead><tbody>";
			
			while($row=mysql_fetch_array($sqlResult))
			{
				$nationalChampionshipEvent = $row['national/WorldRankingEvent'] == '0' ? "" : 'Y' ;
				$startDate = $row['startDateOfEvent'] != '0000-00-00' ? dateChange($row['startDateOfEvent']) : '--';
				$eventCategory     = str_replace(',',', ',$row['eventCategory']);
				$id = $row['id'];
				$nameOfTheEvent = $row['nameOfTheEvent'];
				$state = $row['eventLocationState'];
				
				// json array for export to excel option
				$stuff = 
					array('Event Name' => $nameOfTheEvent, 
						'Event Type' => $eventCategory, 
						'Event Start Date' => $startDate, 
						"NAT'L/WORLD EVENT" => $nationalChampionshipEvent, 
						'STATE' => $state
					);
				$jsonObject[] = $stuff;
				// End json array..
				
				$finalDataResult .= "<tr>
					<td><a target='_blank' href='eventDetail.php?id=$id'>$nameOfTheEvent</a></td>
					<td>$eventCategory</td>
					<td class='tdAlignCenter'>$startDate</td>
					<td class='tdAlignCenter'>$nationalChampionshipEvent</td>
					<td class='tdAlignCenter'>$state</td>
					<td class='tdAlignCenter'><a href='editEvent.php?id=".$id."'>Edit</a></td>
				</tr>";
			}
			$finalDataResult .= "</tbody></table></div>";
			
			if($finalDataResult != '')//finalDataResult
			{	
				include("MPDF/mpdf.php");
				$fileName = "PublicSearchResult".time().".pdf";
				$mpdf=new mPDF(); 
				$mpdf->SetDisplayMode('fullpage');
				$stylesheet = file_get_contents('style/pdfPrint.css');
				$mpdf->WriteHTML($stylesheet,1);
				$mpdf->WriteHTML($finalDataResult);
				$mpdf->Output('temp/'.$fileName);
				echo $finalDataResult;
				
				echo "<div class='printContainer' id='printContainerId'>$finalDataResult</div>";
			}
			else
			{
				echo "<h3 class='errorArea'>There are no events matching your criteria. Please change your criteria and try again.</h3>";
			}
		}	
	}// End submit button condition
  ?> 
	<input type="hidden" name='excelDataAdmin' id='excelDataAdmin' value="<?php echo $jsonObject; ?>" >
	</div>
</div>
</div>
<?php 
  // Start footer part
  include("footer.php");
?>

<script type="text/javascript">
/*
	//Author Name     : Frank Sunil
	//Date            : Jan 14, 2013
	//Purpose         : exporting result into excel file.
*/
function exportTable11()
{
	if($('#excelDataAdmin').val()=='')
		{
			alert('No Records to export');
			return false;
		}
		dataString = <?php echo json_encode($jsonObject);?>;
		$.ajax({
				type:'POST',
				url:'adminExportExcel.php',
				data: {data: dataString}, 
        		cache: false,
        		async: false,
        		beforeSend: function() {
              	$("#loading").show();
           		},
				success:function(data)
				{
					window.location.href="http://s376341053.onlinehome.us/sites/GTAS/radiusSearch/exportedFiles/"+data+"";
					//window.location.href="http://gtas.rubicoit.com/exportedFiles/"+data+"";
					$("#loading").hide();
				}				
			});
}	

	$("#eventType").multiselect({header: false}); 
		$("form").bind("submit", function(){   
	}); 
/*
	//Author Name     : Frank Sunil
	//Date            : Jan 14, 2013
	//Purpose         : print option
*/
function PrintElem(id)
{
	if($('.printContainer').length>0)
	{
		window.open("temp/<?php echo $fileName ?>", '_blank');
	}
	else
	{
		alert("No Records to print");
	}
}

</script>




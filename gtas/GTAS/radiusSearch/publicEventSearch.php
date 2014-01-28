<?php
#	@Name		:	publicEventSearch.php
#	@Author		:	Matthew Umesh
#	@Date		:	Jan 15,2014
#	@Purpose	:	to serve public search
ini_set("display_errors","0");
include_once 'commonFunctions.php';
#	@Name			:	getCountryIndex
#	@Author			:	Matthew Umesh	
#	@Date			:	Jan 15,2014
#	@Purpose		:	to get country index
#	@Argument		:	$sCountry -  country
	function getCountryIndex($sCountry) 
	{
		static $aIndexes= array("us" => 1, "ca" => 2);
		return isset($aIndexes[$sCountry])? $aIndexes[$sCountry] : false;
	}
#	@Name			:	getZipName
#	@Author			:	Matthew Umesh	
#	@Date			:	Jan 15,2014
#	@Purpose		:	to get country index
#	@Argument		:	$sCountry -  country	
	function getZipName($sCountry) {
		//echo "called";
		if (!($nIndex= getCountryIndex($sCountry))) return false;
		static $aVals= array(1=> 'zipcode', 2=> 'postalcode'); 
		return $aVals[$nIndex];
	}
	
#	@Name			:	getInfoByZip
#	@Author			:	Matthew Umesh	
#	@Date			:	Jan 15,2014
#	@Purpose		:	get info by zip
#	@Argument		:	$sCountry -  country | $sZipValue - zipcode
	function getInfoByZip($sCountry, $sZipValue) {
		if (!($sZipName= getZipName($sCountry))) return false;
		$sql= "SELECT 
		                  * 
		       FROM       
			              $sCountry 
			   WHERE 
			              $sZipName ='$sZipValue' 
			   LIMIT 1";
		if (!($h_res= mysql_query($sql)) || !mysql_num_rows($h_res)) return false;
		$b_ok= ($a_row= mysql_fetch_assoc($h_res)) && count($a_row);
		mysql_free_result($h_res);
		return $b_ok? $a_row : false;
	}
	
#	@Name			:	getCoordsByZip
#	@Author			:	Matthew Umesh	
#	@Date			:	Jan 15,2014
#	@Purpose		:	get info by zip
#	@Argument		:	$sCountry -  country | $sZipValue - zipcode
	function getCoordsByZip($sCountry, $sZipValue) {
		if (!($sZipName= getZipName($sCountry))) return false;
		$sql= "SELECT 
		                  `longitude`, `latitude` 
			   FROM 
			               $sCountry 
	           WHERE 
			               $sZipName = '$sZipValue' 
			   LIMIT 1";
		if (!($h_res= mysql_query($sql)) || !mysql_num_rows($h_res)) return false;
		$b_ok= ($a_row= mysql_fetch_row($h_res)) && count($a_row) == 2;
		mysql_free_result($h_res);
		return $b_ok? $a_row : false;
	}
	
#	@Name			:	getZipsByRadius
#	@Author			:	Matthew Umesh	
#	@Date			:	Jan 15,2014
#	@Purpose		:	get zip by radius
#	@Argument		:	$sCountry -  country | $sZipValue - zipcode
	define('F_KMPERMILE', 1.609344	);
	function getZipsByRadius($sRadius, $sCountry, $sZipValue, $sLatitude, $sLongitude) {
		if (!($nIndex= getCountryIndex($sCountry))) return false;
		$fRadius = (float)$sRadius;
		$fLatitude = (float)$sLatitude;
		$fLongitude = (float)$sLongitude;
		$sXprDistance =  "SQRT(POWER(($fLatitude-latitude)*110.7,2)+POWER(($fLongitude-longitude)*75.6,2))";
		static $aVals= array(1=> ", statecode AS areacode", 2=> ", provincecode AS areacode");
		$sXtraFields= $aVals[$nIndex];
		$sql = "SELECT 
		                   `zipcode`, $sXprDistance AS distance $sXtraFields 
				FROM 
				            $sCountry 
			    WHERE 
				            $sXprDistance <= '$fRadius' 
							ORDER BY distance ASC";
		if (!($h_res= mysql_query($sql)) || !mysql_num_rows($h_res)) return false;
		$a_ret= array();
		while ($a_row= mysql_fetch_assoc($h_res)) {
		
			if (count($a_row)) $a_ret[$a_row['zipcode']]= $a_row['distance'];
		}
		mysql_free_result($h_res);
		return count($a_ret)? $a_ret : false;
	}
	
	
#	@Name			:	sqr
#	@Author			:	Matthew Umesh	
#	@Date			:	Dec 30,2013
#	@Purpose		:	sqr the value
#	@Argument		:	NA
	function sqr($x) {
		return $x * $x;
	}
	
#	@Name			:	getEventInfo
#	@Author			:	Matthew Umesh	
#	@Date			:	Dec 30,2013
#	@Purpose		:	To get event info
#	@Argument		:	$zipArray,$eventTypeInfo,$stDate='',$endDate='',$distanceArray, &$getResultForPrintValue
function getEventInfo($zipArray,$eventTypeInfo,$stDate='',$endDate='',$distanceArray, &$getResultForPrintValue,$state,$nationalEvent)
{	  
	$getFinalresult   = '';
	if(!empty($zipArray))
	{
		if(is_array($zipArray))
			$getZipcode = implode(",",$zipArray);
		else
			$getZipcode = $zipArray;
			
		$andEventCondition = " AND eventLocationZipCode IN ($getZipcode)";
	}
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
	  
	  if(!empty($state))
	  	$andEventCondition.= " AND eventLocationState = '".$state."'";
	   /* conversion of date according to database ends */	
		
		if($nationalEvent == '0' || $nationalEvent == '1')
			$andEventCondition.= " AND `national/WorldRankingEvent`= '".$nationalEvent."'";  

 	if($stDate!='' && $endDate=='')
		$andEventCondition.= " AND startDateOfEvent >='".$stDate."'";
	 if($endDate!='' && $stDate=='')
		$andEventCondition.= " AND startDateOfEvent <='".$endDate."'";
	 if($stDate!='' && $endDate!='')
	 	$andEventCondition.= " AND (startDateOfEvent >='".$stDate."' AND startDateOfEvent <='".$endDate."')";
		
		 if(!empty($eventTypeInfo))
		 {
		 	foreach ($eventTypeInfo as $key => $value)
			{
				if($key==0)
					$andEventCondition .= " AND  (eventCategory LIKE '%$value%'";
				else
					$andEventCondition .= " OR  eventCategory LIKE '%$value%'";
			}
		 	$andEventCondition .=")";
		 }
		 unset($sql);
		$sql= "SELECT 
						id,
	                     startDateOfEvent,
						 eventLocationZipCode,
						 eventId_,
						 nameOfTheEvent,
						 eventLocationState,
						 if(`national/WorldRankingEvent` = 1,'Y','') as nationalEvent,
						 eventCategory
                FROM 
		                 newEvents
                WHERE 
		                 status_=1 $andEventCondition
				ORDER BY nameOfTheEvent";	
						
						 	  
	$result            = mysql_query($sql); 

	while($resultArray = mysql_fetch_assoc($result))
	{
		if($resultArray['nameOfTheEvent'] != '')
		{ 
			

			$eventName         = $resultArray['nameOfTheEvent']; 
			$eventId           = $resultArray['id'];
			$eventZip          = $resultArray['eventLocationZipCode'];
			
			if(!empty($distanceArray))
				$temp = ($distanceArray[$eventZip]=='') ? 0 : round($distanceArray[$eventZip]/F_KMPERMILE,1);
			else
				$temp=0;
				
			$eventSDate        = $resultArray['startDateOfEvent'];
			$worldChamp		= $resultArray['nationalEvent'];
			list($year,$month,$date)=split('[-]',$eventSDate);
			$eventSDate = $month.'/'.$date.'/'.$year;
			
			$eventCategory     = str_replace(',',', ',$resultArray['eventCategory']);
			$linkUrl           = "eventDetail.php?id=$eventId";
			$eventName         = "<a href='$linkUrl'  target='_blank'>$eventName</a>";
			$getFinalresult .=   " <tr>
						<td>$eventName</td>
						<td>$eventCategory</td>
						<td>$eventSDate</td>
						<td class='tdAlignCenter'>".$worldChamp."</td>
						<td class='tdAlignCenter'>".$resultArray['eventLocationState']."</td>
						<td class='centerText'>".$temp."</td>
				   </tr> ";
			$getResultForPrintValue[]=array($eventName,$eventCategory,$eventSDate,$worldChamp,$resultArray['eventLocationState'],$temp);
			unset($temp);
		}
	}  
		return $getFinalresult ;
}

$getAllEventTypes  = getEventTypes(); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Event Search</title>

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
$("#eventCategory").multiselect({header: false}); 
	    } 
	    
	); 
	$(window).load(function() {
    $('#loading').hide();
  });
	</script>
    
<style>
.eventTableClass {
	margin-top: 0 !important;
	padding: 0px;
	width: 96%;
	margin-top: 10px;
	margin-left: auto;
	margin-right: auto;
}
.createEventSection {
	margin: 0px 0px 0px 85px;
}
.categorySpan {
margin-left:317px;
margin-top:-17px;
}
</style>		 
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
	if(isset($_POST['submit']))
	{
		if(!empty($_REQUEST['dateFrom']) && !isValidDate($_REQUEST['dateFrom'])) 
			$errors[] = " Invalid Date from";
		if(!empty($_REQUEST['dateTo']) && !isValidDate($_REQUEST['dateTo'])) 
			$errors[] = " Invalid Date to";	
		if($_POST['worldChampionship']== '' || $_POST['worldChampionship']== '0' || $_POST['worldChampionship']== '1')
			$worldChampionshipVall = '1';
			
		if($_POST['zipcode']=="" && $_POST['radius']=="" && $_POST['state']=="" && empty($_POST['eventCategory']) && empty($worldChampionshipVall) && empty($_POST['dateFrom']) && empty($_POST['dateTo']))
			$errors[] = "Please enter a value in at least one field to search";
		elseif($_POST['zipcode']!="" && $_POST['radius']=="")
			$errors[] = "Please enter a Radius selection from your entered a ZIP";
		elseif($_POST['zipcode']=="" && $_POST['radius']!="")
			$errors[] = "Please enter a ZIP for your selected Radius";
		elseif(!empty($_REQUEST['dateFrom']) && isValidDate($_REQUEST['dateFrom']) && !empty($_REQUEST['dateTo'])&& isValidDate($_REQUEST['dateTo']))
		{
			if ((strtotime($_REQUEST['dateFrom'])) > (strtotime($_POST['dateTo'])))
				$errors[] = "Date to field can not be less than Date from.";

		}
		
			$sZipCode = $_REQUEST['zipcode'];
			$sCountry = $_REQUEST['country'];
			$sRadius = $_REQUEST['radius'];
			$getStDate=$_POST['dateFrom'];
			$getEndDate=$_POST['dateTo'];
			
			$getselectedEventType = $_POST['eventCategory'];
			$fRadius = (float)$sRadius;
			$sUnit = (isset($_REQUEST['unit']) && $_REQUEST['unit'] == "km")? "km" : "miles";
			/* Radius is converted into kilometer as getZipsByRadius is expecting radius in km   */
			if ($bUnitMiles = $sUnit=="miles") $fRadius = $fRadius * F_KMPERMILE;
			
			/* ----------------------------- */
			/* Get Info for ZIP Code */
			/* ----------------------------- */
			if($sZipCode!='') #check if zip code is not empty
			{
				if (!($a_info = getInfoByZip($sCountry, $sZipCode)))
					$errors[] ="Zipcode not found.";
				 
				$sCity = $a_info["city"];
				$sLongitude = $a_info["longitude"];
				$sLatitude = $a_info["latitude"];
				$fLatitude = (float)$sLatitude;
				$fLongitude = (float)$sLongitude;
				if ($sCountry == "us") {
					$sAreacode = $a_info["statecode"];
				}
				else {
					$sAreacode = $a_info["provincecode"];
				}
				$sMaptxt = "$sRadius $sUnit around $sZIPName<br/>$sZIPCode $sCity";
				
					/* Get Info for ZIP Code */
				if (!($a_result = getZipsByRadius($fRadius, $sCountry, $sZipCode, $sLatitude, $sLongitude)))
					$errors[] ="Zipcode not found.";
					
				$keyArray = array_keys($a_result);
				$keyArray[]=$sZipCode;	
			}
	}
?>
<div class="mainDiv">
<div class="mainInnerDiv">
	<div class="contentDiv">
	<div class="adminSearch">
	<div class="createEventHeading">Event Search</div>  <?php
    if(!empty($errors))
	{  ?>		
    	<div class="error">
        <?php foreach($errors as $val)
				echo $val.'<br>';	?>
        </div><?php
	}	?>
    <form method="post" name="searchForm" id="radiusSearchFormId">
     
	<div class="createEventSection">
		<div class="createEventRow">
			<div class="createEventCell">
				5-Digit ZIP Code
			</div><!-- /.createEventCell -->
			<div class="createEventCell2">
				<input type="text" placeholder="Enter ZIP" value="<?php echo $_POST['zipcode']?>" onKeyPress="return onlynum(event)" name="zipcode"/>
				<span class="note">(Search from Here)</span>
			</div><!-- /.createEventCell2 -->
		</div><!-- /.createEventRow -->
		<div class="createEventButtons">
        <a href="https://tools.usps.com/go/ZipLookupAction!input.action" class="clsAnchor" target="_blank">
			<input type="button" name="" value="Find ZIP using address" class="eventbutton" title="" />
            </a>
		</div><!-- /.createEventButtons -->
		
		<div class="createEventRow">
			<div class="createEventCell">
				Radius
			</div><!-- /.createEventCell -->
			<div class="createEventCell2">
				<select name="radius">
                    <option></option>
                    <option value="25" <?php  if($_POST['radius'] == '25')  { echo "selected= 'selected'"; }?> >25</option>  
                    <option value="50" <?php if($_POST['radius'] == '50') { echo "selected= 'selected'"; }?> >50</option>
                    <option value="100" <?php if($_POST['radius'] == '100') { echo "selected= 'selected'"; }?> >100</option>
                    <option value="250" <?php if($_POST['radius'] == '250') { echo "selected= 'selected'"; }?> >250</option>
                    <option value="500" <?php if($_POST['radius'] == '500') { echo "selected= 'selected'"; }?> >500</option>
				</select>
				<span class="note">(If ZIP entered, please select <br> desired radius (in Miles) for search)</span>
			</div><!-- /.createEventCell2 -->
		</div><!-- /.createEventRow -->
		
		<div class="createEventRow">
			<div class="createEventCell">
				State
			</div><!-- /.createEventCell -->
			<div class="createEventCell2">
				<?php echo stateDropDown($_POST['state'],'state'); ?>
			</div><!-- /.createEventCell2 -->
		</div><!-- /.createEventRow -->
		
		<div class="createEventRow">
			<div class="createEventCell">
				National / World Championship Event
			</div><!-- /.createEventCell -->
			<div class="createEventCell2">
                <select name="worldChampionship">
                	<option value='' <?php echo $_POST['worldChampionship'] == '' ? "selected='selected'" : '' ; ?>>All</option>
                    <option value='0' <?php echo $_POST['worldChampionship'] == '0' && isset($_POST['worldChampionship'])  ? "selected='selected'" : '' ; ?>>No</option>
                    <option value='1' <?php echo $_POST['worldChampionship'] == '1' ? "selected='selected'" : '' ; ?>>YES</option>
                </select>
			</div><!-- /.createEventCell2 -->
		</div><!-- /.createEventRow -->
		
		<div class="createEventRow">
			<div class="createEventCell">
				Event Type
			</div><!-- /.createEventCell -->
			<div class="createEventCell2">
            	 <select name="eventCategory[]" id="eventCategory" multiple="multiple"    class="dropDown"> <?php 
					foreach($getAllEventTypes as $filterEvents)
					{ 
						$temp = '';
						if(!empty($_POST['eventCategory']))
							if(in_array($filterEvents,$_POST['eventCategory']))
							$temp = "selected='selected'";   ?>
							<option value='<?php echo $filterEvents?>' <?php echo $temp?>> 
								<?php echo $filterEvents?>
							</option><?php
					}  ?>
				</select>
				<span class="note categorySpan">(Optional)</span>
			</div><!-- /.createEventCell2 -->
		</div><!-- /.createEventRow -->
		
		<div class="createEventRow">
			<div class="createEventCell">
				Date From
			</div><!-- /.createEventCell -->
			<div class="createEventCell2">
				<input type="text" placeholder="Enter Start Date Range" readonly="readonly" value="<?php echo $_POST['dateFrom']?>" name="dateFrom" id="dateFrom"/>
				<input type="button" id="idClear" onclick="clearData('dateFrom','')" title="Clear" class="clear"  name="" >
			</div><!-- /.createEventCell2 -->
		</div><!-- /.createEventRow -->
		
		<div class="createEventRow">
			<div class="createEventCell">
				Date To
			</div><!-- /.createEventCell -->
			<div class="createEventCell2">
				<input type="text" placeholder="Enter End Date Range" readonly="readonly" value="<?php echo $_POST['dateTo']?>" name="dateTo" id="dateTo"/>
				<input type="button" id="idClear" onclick="clearData('dateTo','')" title="Clear" class="clear"  name="" >
			</div><!-- /.createEventCell2 -->
		</div><!-- /.createEventRow -->
		<script> $( "#dateFrom" ).datepicker(); 
		 $( "#dateTo" ).datepicker();
        </script>
		<div class="createEventButtons">
        <input type="hidden" name="country" value="us" />
    <input type="hidden" name="unit" value="miles" />
			<input type="submit" name="submit" value="Search" class="eventbutton" title="" />
			<input type="button" name="print" value="Print" onclick="PrintElem('ReportTable')" class="eventbutton" title="" />
			<input type="button" name="exporttoExcel" value="Export to Excel" onClick="exportTable()"  class="eventbutton" title="" />
		</div><!-- /.createEventButtons -->
	</div><!-- /.createEventSection -->
    </form>
	</div>
	
    </div>
    <?php 
	if(isset($_POST['submit']))
	{
		if(empty($errors))
		{

			$finalDataResult .= "<div class='eventTableClass'><table id='ReportTable' class='tablesorter'><thead><tr><th width='35%'><span>Event Name</span></th><th width='35%'><span>Event Type</span></th><th width='8%'><span>Event Start Date</span></th><th width='8%'><span>N/W EVENT</span></th><th width='6%'><span>State</span></th><th width='8%' class='centerText'><span>Distance (miles)</span></th></tr></thead><tbody>";
			$sResultlist  = ''; 
			$sCondition   = ''; 
			$eventCounter = 0 ;
			$getResultForPrintValue=array();
			$getResultForPrint = ''; 
			
			$finalDataResult .=  getEventInfo($keyArray,$getselectedEventType,$getStDate,$getEndDate,$a_result,$getResultForPrintValue,$_POST['state'],$_POST['worldChampionship']);
			$finalDataResult .= "</tbody></table></div>";	
			$allVal=array_filter($getResultForPrintValue);
			if($finalDataResult != '')//finalDataResult
			{	
				include("MPDF/mpdf.php");
				$fileName = "searchResult".time().".pdf";
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
			// Store result in session for getting result in print and excel pages.
			$_SESSION['getPrintData'] = $getResultForPrintValue;
		}
	}
	
	?>
  
    </div>
    </div>
<?php 
  // Start footer part
  include("footer.php");
?>
<script type="text/javascript">
function PrintElem(id)
{
    	if($('.printContainer').length>0)
		{
        	//window.print();
			window.open("temp/<?php echo $fileName ?>", '_blank');
			//window.location.href = ;
		}	
        else
        {
			$('.printData').remove();
			alert('Please search a zip code before click on print.');
			//$('.contentDiv').append("<h3 class='errorArea marginError printData'>Please search a zip code before click on print.</h3>");
        }
}
function notnum(evt)
{
	evt = (evt) ? evt : window.event;
	var charCode = (evt.which) ? evt.which : evt.keyCode;
	 
	if(charCode>=48 && charCode<=57)
		return false;
}
function onlynum(evt)
{		
		evt = (evt) ? evt : window.event
		var charCode = (evt.which) ? evt.which : evt.keyCode 

			/* for enabling enter, backspace, delete and arrow keys */
		if((charCode==13) ||(charCode==8) ||(charCode==46) ||(charCode==116)|| (charCode > 36) && (charCode < 41))
			return true;

		/* for enabling arrow keys and enter  ends here*/
		if((charCode < 48) || (charCode > 57) && (charCode > 32))
		{
			alert('You can only enter numbers here');
				return false;
		}
		return true;
}

function exportTable()
{
	var totalRecord = <?php echo count($allVal);?>;
	if(totalRecord == 0)
	{
		alert('No Records to export');
		return false;
	}
	dataString = <?php echo json_encode($allVal);?>;
	$.ajax({
			type:'POST',
			url:'getTableExcel.php',
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
</script>
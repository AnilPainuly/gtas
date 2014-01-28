
<?php 
error_reporting(E_ALL);
ini_set("display_errors", 0); 
session_start();  
//****************************************************************************************
    //Page Name       : toolRadius.php
    //Author Name     : Alan Anil
    //Date            : July 30 2013
    //Input Parameters:
    //Purpose         : ** ZIP Codes in a Radius in USA and Canada
	/*					**
						** This PHP Script requires 4 GET parameters: zipcode, country (us/ca), radius, unit (miles/km)
						** Plus the database tables us and ca containing the ZIP Code-Lon/Lat data.
						**
						** Example call: tools_radius.php?zipcode=90210&country=us&radius=10&unit=miles
						** 
						**   2012 http://www.zipcodesoft.com, All Rights Reserved
						**======================================================================
	*///| Ref No.	|  Author name	| Date	         |  Modification description
	//		1		Parker Prashant		20-Aug-2013		Changes in ui
	//		2		Parker Prashant		23-Aug-2013		added print and export
	//		3		Parker Prashant		25-Aug-2013		added date from and to
//***************************************************************************************************
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<!--[if IE]>
	<link rel="stylesheet" href="style/ie.css" media="screen" type="text/css" />
	<![endif]-->
<link rel="stylesheet" type="text/css" href="selectListLib/jquery.multiselect.css" />
<link rel="stylesheet" type="text/css" href="selectListLib/style.css" />
<link rel="stylesheet" type="text/css" href="selectListLib/jquery-ui.css" />
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Droid+Sans:400,700%7CUbuntu:400,500italic,400italic,700&amp;subset=latin,latin">
<script src="https://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js" type="text/javascript" async=""></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js"></script>
<script type="text/javascript" src="selectListLib/src/jquery.multiselect.js"></script>


	<link rel="stylesheet" type="text/css" media="print" href="style/print.css">
		<script type="text/javascript" src="js/browserselector.js"></script> 
	<!-- script for table dort plugin -->

	<script type="text/javascript" src="js/tablesort/jquery.js"></script> 
	<script type="text/javascript" src="js/tablesort/jquery.dataTables.min.js"></script> 
	<!-- script for table dort plugin -->
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
	$(window).load(function() {
    $('#loading').hide();
  });
	</script>
	

<title>Event Search</title>
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
session_start();
require 'generalFunction.php'; 
$getAllEventTypes  = getEventTypes(); 
$getRadius         = '';
$getFinalresult    = '';
$getResult         = '';
$printDataValues   = '';
$eventTypesCounter = 0 ;
$finalDataResult   = '';
$finalDataCheck    = '';
$getZipcodeValue   = '';
$getStDate 		   = '';
$getEndDate		   = '';
$getEventType	   = array();
$allVal='';
// Checking user have entered radius and zipcode.


/* exporting data in excel file */
if(isset($_REQUEST['radius'])) 
{
	$getRadius       = $_REQUEST['radius'];
}
if(isset($_REQUEST['zipcode'])) 
{
	$getZipcodeValue = $_REQUEST['zipcode'];
}
if(isset($_REQUEST['eventType'])) 
{
	$getEventType    = $_REQUEST['eventType'];
}
if(isset($_REQUEST['dateFrom'])) 
{
	$getStDate    = $_REQUEST['dateFrom'];
}
if(isset($_REQUEST['dateTo'])) 
{
	$getEndDate    = $_REQUEST['dateTo'];
}
?> 	
	

<div class="mainDiv">
<div class="mainInnerDiv">
	<div class="contentDiv">
<div class="spTitleBar topHeading">Radius Event Search</div>
<div id='loading'><img src='style/images/loader2.gif'></div>
<form method="post" name="radiusSearchForm" id="radiusSearchFormId" action="">

<div class="zipCodeSearch">
	<div class="zipCodeElements">
    <span class="requiredStar">* </span><span class="requiredText">Required Field</span>
    </div>
    <div class="zipCodeElements">
    <input type="hidden" name="country" value="us" />
    <input type="hidden" name="unit" value="miles" />
    <div class="radiusSearchContent">
    	<span class="innerText">5-Digit ZIP Code</span><span class="requiredStar"> *</span>
    	</div>
    	<input type="text" maxlength="5" name="zipcode" 
	<?php if($getZipcodeValue != '') { echo "value= '$getZipcodeValue'"; } ?> placeholder="Enter ZIP" class='width290 margin4' onKeyPress="return onlynum(event)"/>
    </div>
    <div class="zipCodeElements">
    <div class="radiusSearchContent"><span class="innerText">Radius</span><span class="requiredStar"> *</span></div><select name="radius" id="radiusId1" class="dropDown">
                         <option value="25" <?php  if($getRadius == '25')  { echo "selected= 'selected'"; }?> >25</option>  
                         <option value="50" <?php if($getRadius == '50') { echo "selected= 'selected'"; }?> >50</option>
                         <option value="100" <?php if($getRadius == '100') { echo "selected= 'selected'"; }?> >100</option>
                         <option value="250" <?php if($getRadius == '250') { echo "selected= 'selected'"; }?> >250</option>
                         <option value="500" <?php if($getRadius == '500') { echo "selected= 'selected'"; }?> >500</option>
                         </select>  (Miles)
    </div>
     <div class="zipCodeElements">
    <div class="radiusSearchContent"><span class="innerText">Event Type</span></div>
         <select name="eventType[]" id="eventTypeValueId" multiple="multiple"  class="dropDown"> 
         	 
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
         <span class="boxRightText">(Optional)</span>
    </div>
    <div class="zipCodeElements">
    <div class="radiusSearchContent"><span class="innerText">Date From</span>    	
    </div>
    <input type="text"  name="dateFrom"  id="dateFrom" class='width290 margin4' placeholder="Enter Start Date Range" <?php if($getStDate != '') { echo "value= '$getStDate'"; } ?>/>
    </div>
     <div class="zipCodeElements">
    <div class="radiusSearchContent"><span class="innerText">Date To</span>
    </div>
    	<input type="text"  name="dateTo" id="dateTo" class='width290 margin4' placeholder="Enter End Date Range" <?php if($getEndDate != '') { echo "value= '$getEndDate'"; } ?>/>
    </div>
    
   <div class="bottomButtons">
	     <div class="submitButton">
	    <input type="submit" name="calculate" value="Search" class="formbutton buttonClass"  title="Search Event"/>
	    </div>
	    
			<div class="printBtn">
			<input type="button" name="printButton"  onclick="PrintElem('ReportTable')" value="Print" class="formbutton buttonClass" title="Print Page"/>
			</div>
			<div class="exportBtn">
	     	<input type="button" id="exportToExcel" onClick="exportTable()" name="exportToExcel" value="Export to Excel" class="formbutton buttonClass" title="Export Data To Excel File"/>
	    	</div>
	 	
 	</div>
</div>   </form>

  <script type="text/javascript">
$("#eventTypeValueId").multiselect();
$("#radiusId").multiselect({ multiple:false });

$("form").bind("submit", function(){   
}); 

function PrintElem(id)
    {
    	if($('.printContainer').length>0)
    	{
        window.print();
        }
        else
        {
        $('.printData').remove();
        $('.contentDiv').append("<h3 class='errorArea marginError printData'>Please search a zip code before click on print.</h3>");
        }
    	
    }
    
function notnum(evt)
{

	evt = (evt) ? evt : window.event;
	var charCode = (evt.which) ? evt.which : evt.keyCode;
	 
	if(charCode>=48 && charCode<=57)
	{		
		return false;
	}
}
function onlynum(evt)
{		
		evt = (evt) ? evt : window.event
		var charCode = (evt.which) ? evt.which : evt.keyCode 
		//alert(charCode);
			/* for enabling enter, backspace, delete and arrow keys */
		if((charCode==13) ||(charCode==8) ||(charCode==46) ||(charCode==116)|| (charCode > 36) && (charCode < 41))
		{
			return true;
				
		}
		/* for enabling arrow keys and enter  ends here*/
		if((charCode < 48) || (charCode > 57) && (charCode > 32))
		{
			alert('You can only enter numbers here');
				return false;
		}
		return true;
}




  
</script>
<link rel="stylesheet" href="style/jquery-uiDatepicker.css" />
  <script type="text/javascript" src="js/jquery-1.9.1.js"></script>
  <script type="text/javascript" src="js/jquery-ui.js"></script>
  <script>
  var $jp= jQuery.noConflict();
   $jp(function() {
    $jp( "#dateFrom" ).datepicker({ dateFormat: 'mm/dd/yy' }).val();
    $jp( "#dateTo" ).datepicker({ dateFormat: 'mm/dd/yy' }).val();
  });
</script>

<?php 
// Check user press search button and zipcode is not blank.
if(isset($_REQUEST['calculate']))
{
	if(isset($_REQUEST['calculate']) && $_REQUEST['zipcode'] == '')
	{
		die("<h3 class='errorArea marginError'>Please enter zip code for search criteria.</h3>");
	}
	if(isset($_REQUEST['dateFrom']) && isset($_REQUEST['dateTo']))
	{
		if ((strtotime($_REQUEST['dateFrom'])) > (strtotime($_POST['dateTo'])))
		{
		 die("<h3 class='errorArea marginError'>Date to field can not be less than Date from.</h3>");
		}
	}

	$sZIPName = '';
	$sZIPCode = '';
	// Get the value of eventType.
    $getselectedEventType = $_REQUEST['eventType'];
	function getCountryIndex($sCountry) {
		static $aIndexes= array("us" => 1, "ca" => 2);
		return isset($aIndexes[$sCountry])? $aIndexes[$sCountry] : false;
	}
	
	function getZipName($sCountry) {
		//echo "called";
		if (!($nIndex= getCountryIndex($sCountry))) return false;
		static $aVals= array(1=> 'zipcode', 2=> 'postalcode'); 
		return $aVals[$nIndex];
	}
	
	/* ----------------------------- */
	/* Get info for a given ZIP Code value */
	/* ----------------------------- */
	function getInfoByZip($sCountry, $sZipValue) {
		if (!($sZipName= getZipName($sCountry))) return false;
		//echo $sZipName."fdsfds"."<br>";
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
		//echo $b_ok? $a_row : false;
		return $b_ok? $a_row : false;
	}
	
	/* ----------------------------- */
	/* Get coordinates for a given ZIP Code value */
	/* ----------------------------- */
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
	
	/* ----------------------------- */
	/* Get all ZIP Codes within a given Radius from a given ZIP Code */
	/* ----------------------------- */
	function getZipsByRadius($sRadius, $sCountry, $sZipValue, $sLatitude, $sLongitude) {
		if (!($nIndex= getCountryIndex($sCountry))) return false;
		$fRadius = (float)$sRadius;
		$fLatitude = (float)$sLatitude;
		$fLongitude = (float)$sLongitude;
		$sXprDistance =  "SQRT(POWER(($fLatitude-latitude)*110.7,2)+POWER(($fLongitude-longitude)*75.6,2))";
		static $aVals= array(1=> ", statecode AS areacode", 2=> ", provincecode AS areacode");
		$sXtraFields= $aVals[$nIndex];
		$sql = "SELECT 
		                   `city`, `longitude`, `latitude`, `zipcode`, $sXprDistance AS distance $sXtraFields 
				FROM 
				            $sCountry 
			    WHERE 
				            $sXprDistance <= '$fRadius' 
							ORDER BY distance ASC";
		if (!($h_res= mysql_query($sql)) || !mysql_num_rows($h_res)) return false;
		$a_ret= array();
		while ($a_row= mysql_fetch_assoc($h_res)) {
		
			if (count($a_row)) $a_ret[]= $a_row;
		}
		mysql_free_result($h_res);
		return count($a_ret)? $a_ret : false;
	}
	
	define('F_KMPERMILE', 1.609344	);
	
	function sqr($x) {
		return $x * $x;
	}
	
	/* ----------------------------- */
	/* Start of Script */
	/* Get parameters */
	/* ----------------------------- */
	$b_ok= isset($_REQUEST['zipcode']) && isset($_REQUEST['country']) && isset($_REQUEST['radius']);
	if (!$b_ok)
		die("<h3 class='errorArea marginError'>Parameters are missed.</h3>");
		 
 	$sZipCode = $_REQUEST['zipcode'];
    $sCountry = $_REQUEST['country'];
 	$sRadius = $_REQUEST['radius'];
 	$fRadius = (float)$sRadius;
	$sUnit = (isset($_REQUEST['unit']) && $_REQUEST['unit'] == "km")? "km" : "miles";
	/* Radius is converted into kilometer as getZipsByRadius is expecting radius in km   */
	if ($bUnitMiles = $sUnit=="miles") $fRadius = $fRadius * F_KMPERMILE;
	
	/* ----------------------------- */
	/* Get Info for ZIP Code */
	/* ----------------------------- */
	if (!($a_info = getInfoByZip($sCountry, $sZipCode)))
		die("<h3 class='errorArea marginError'>Zipcode not found.</h3>");
	 
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
		die("<h3 class='errorArea marginError'>Zipcode not found.</h3>"); 
	?>

	<?php
	/* ----------------------------- */
	/* Creating result list */
	/* ----------------------------- */
	$finalDataResult .= "<div class='eventTableClass'><table id='ReportTable' class='tablesorter'><thead><tr><th width='30%'><span>Event Name</span></th><th width='40%'><span>Event Type</span></th><th width='15%'><span>Event Start Date</span></th><th width='15%' class='centerText'><span>Distance (miles)</span></th></tr></thead><tbody>";
	$sResultlist  = ''; 
	$sCondition   = ''; 
	$eventCounter = 0 ;
	$getResultForPrint = ''; 
	$finalDataResult .=       $getResult           = getEventInfo($sZipCode,$getselectedEventType,$getStDate,$getEndDate);
				$getEventsValues     = getEventInfoForPrint($sZipCode,$getselectedEventType,$getStDate,$getEndDate);
		
				if($getEventsValues != '')
				{
					$finalDataCheck = 'Value Exist';
					foreach($getEventsValues as $arr)
					{
						$getResultForPrintValue[] = $arr;
					}
				 
				}
	
	foreach ($a_result as $i=> $a_row) {
		$sZipCode= $a_row["zipcode"];
		$sCity= $a_row["city"];
		$sAreacode= $a_row["areacode"];
		if ($i==0) {
			$sDistance = " (0  $sUnit)";
			$sResultlist = "$sZipCode $sCity, $sAreacode$sDistance<br>";
			$sCondition .= "'$sZipCode'";
			continue;
		}
		if (strpos($sCondition, "'$sZipCode'")!==false) continue;
		$sCondition .= ", '$sZipCode'";
		$fLatDiff = $fLatitude - (float)$a_row["latitude"];
		$fLonDiff = $fLongitude - (float)$a_row["longitude"];
		if ($bUnitMiles)
			{
				$sDistance = " (". Round(sqrt(sqr($fLatDiff*110.7)+sqr($fLonDiff*75.6))/F_KMPERMILE,1). "  ". $sUnit. ")";
				$sDisWdoutUnit=Round(sqrt(sqr($fLatDiff*110.7)+sqr($fLonDiff*75.6))/F_KMPERMILE,1);
			}
		else
			{
				$sDistance = " (". Round(sqrt(sqr($fLatDiff*110.7)+sqr($fLonDiff*75.6)),1). "  ". $sUnit. ")";
				$sDisWdoutUnit=Round(sqrt(sqr($fLatDiff*110.7)+sqr($fLonDiff*75.6))/F_KMPERMILE,1);
			}
			$sResultlist .= "$sZipCode $sCity, $sAreacode$sDistance<br />";
			
			if(!empty($sZipCode))
			{
			$finalDataResult     .= $getResult  = getEventInfo($sZipCode,$getselectedEventType,$getStDate,$getEndDate,$sDisWdoutUnit);
				$getEventsValuesOne             = getEventInfoForPrint($sZipCode,$getselectedEventType,$getStDate,$getEndDate,$sDisWdoutUnit);
				
				if($getEventsValuesOne != '')
				{
					foreach($getEventsValuesOne as $arr)
					{
						$finalDataCheck = 'Value Exist';
						$getResultForPrintValue[] = $arr;
					}
				 
				}
			}
	} 
	
	$finalDataResult .= "</tbody></table></div>";	
	$allVal=array_filter($getResultForPrintValue);
	if($finalDataResult != '')//finalDataResult
	{	
		echo $finalDataResult;
		
		echo "<div class='printContainer' id='printContainerId'>$finalDataResult</div>";
	}
	else
	{
		echo "<h3 class='errorArea'>There are no events matching your criteria. Please change your criteria and try again.</h3>";
	}
	// Store result in session for getting result in print and excel pages.
	$_SESSION['getPrintData'] = $getResultForPrintValue;
	//echo $sResultlist;
	//$sCondition = " zipcode IN ($sCondition)";  
} 
?>
<?php
//$allVal=json_encode($allVal);

?>
<input type="hidden" name='excelData' id='excelData' value="<?php echo $allVal;?>">
</div>
</div>
</div>
</div>
<div id="Copyright">
			Membership Software Powered by <a href="http://www.yourmembership.com/">YourMembership.com<span style="font-size:11px">&reg;</span></a> &nbsp;::&nbsp; <a href="https://gtallsports.site-ym.com/ams/legal-privacy.htm">Legal/Privacy</a><!-- Copyright (c) 1998-2013 YourMembership.com Inc. All Rights Reserved. Copyright: Certain elements of this website are: Copyright (c) 1998-2013, YourMembership.com, Incorporated. YourMembership.com, Incorporated provides a limited license to use its Copyrights to the entity from whose web page you are viewing. Certain elements of this website may also be copyrighted by that entity; please see its Terms of Use or contact the organization for more information. General information about copyright laws can be found at: http://www.copyright.gov/. For more specific information, please consult an attorney. -->
</div>
</body>
</html>
<script type="text/javascript">
	function exportTable()
	{
		if($('#excelData').val()=='')
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
					$("#loading").hide();
					
				}				
			});
		
	}

</script>

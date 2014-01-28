<?php
#	@Name		:	createEvent
#	@Author		:	Matthew Umesh
#	@Date		:	Dec 24,2013
#	@Purpose	:	to create new event
ini_set("display_errors",'0');
define('PHONEVALIDATION',"/^([0-1]-)?[0-9]{3}-[0-9]{3}-[0-9]{4}$/i");
include_once 'commonFunctions.php';
$status=0;
if(isset($_POST['submit'])) # check if form has submitted
{
	$postArray = $_POST;
	$valArray='';
	$fieldArray='';
	$errors= array();
	
		
		# Validation block start
		if($postArray['enterEventDetails']=='<br>')
			$postArray['enterEventDetails']='';
		
		foreach($requiredFields as $key => $val)
		{
			
			if($key == 'eventCategory')  // check event category field
			{
				if(count($postArray[$key])==0)
				$errors[]=$val;
			}
			elseif(trim($postArray[$key])=='')  // check empty fields
				$errors[]=$val;
			
			if($key == 'contactEmailAddress' && $postArray[$key]!= '') // validate email address
				if (!filter_var($postArray[$key], FILTER_VALIDATE_EMAIL)) 
				{	$errors[]="Please Enter Valid Contact Email Address";	}
		}
		if($_POST['contactPhoneNumber-admin'] != '')
		{
			if( !preg_match(PHONEVALIDATION, $_POST['contactPhoneNumber-admin']) ) {
				$errors[]="Please enter a valid Contact Phone Number-Admin. (example: 123-123-1234)";
			}
		}
		if($_POST['contactPhoneNumber-public'] != '')
		{
			if( !preg_match(PHONEVALIDATION, $_POST['contactPhoneNumber-public']) ) {
				$errors[]="Please enter a valid Contact Phone Number-Public. (example: 123-123-1234)";
			}
		}
		
		if($_POST['registrationLink'] != '') // check url fields
		{
			if(!filter_var($_POST['registrationLink'], FILTER_VALIDATE_URL))  // validate url
			   $errors[]= "Please Enter valid url for Registration" ;

		}
		if($_POST['linkToWebsite'] != '') // check url fields
		{
			if(!filter_var($_POST['linkToWebsite'], FILTER_VALIDATE_URL))  // validate url
			   $errors[]= "Please Enter valid url for Website";
		}
		
		if( intval($postArray['firstNumber']) + intval($postArray['secondNumber']) != intval($postArray['captcha']) ) // check Captcha value
			$errors[] = "Captcha value is wrong."; 
		# Validation block end
		if(empty($errors)) # if error not found
		{
			foreach($postArray as $key => $val)
			{
				if($key == 'submit' || $key == 'reset'|| $key == 'firstNumber' || $key == 'secondNumber' || $key == 'captcha' || $key == 'copy_' ) #discard extra fields
					continue;
				
				if($key == 'eventCategory') #check if key is event category
				{ 
					$temp = is_array($val) ? implode(",",$val) : $val ;
					unset($val);
					$val = $temp;
				}
				if($key == 'national/WorldRankingEvent')
					$valArray .= $valArray == '' ? "'".mysql_real_escape_string($val)."'" : ",'".mysql_real_escape_string($val)."'" ;
				elseif (isValidTime($val))
					$valArray .= $valArray == '' ? "'".changeTimeFormat($val)."'" : ",'".changeTimeFormat($val)."'" ;
				elseif ( isValidDate($val)) 
					$valArray .= $valArray == '' ? "'".changeDateFormat($val)."'" : ",'".changeDateFormat($val)."'" ;
				else
					$valArray .= $valArray == '' ? "'".mysql_real_escape_string($val)."'" : ",'".mysql_real_escape_string($val)."'" ;
				
				$fieldString .= $fieldString=='' ? "`".$key."`" : ",`".$key."`";
			}
			
			$result = mysql_query("INSERT INTO newEvents(".$fieldString.",eventCreatedDate_) values (".$valArray.",now())");
			if($result) # query executed successfuly 
			{
				$eventId = mysql_insert_id();
				$status = sendEmail($postArray['contactEmailAddress'],$eventId); # send en email 
			}
			else
			{
				$status = QUERYERROR;
			}
			unset($temp);
		}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Event Creation Form</title>

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
		<script type="text/javascript" src="js/scripts.js"></script> 
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
			<div class="createEventHeading">Event Creation Form</div>


<?php 
if($status == QUERYERROR)
{
	echo "There is some Problem with database. We are not able to save your event at this moment. Please try later. ";
}
elseif($status == EVENTCREATED)
{
	echo "Thank you for completing your event information. An activation email has beed sent to your email account. Please activate your event.";
}
else
{		
	if(!empty($errors))
	{  ?>		
    	<div class="error">
        <?php foreach($errors as $val)
				echo $val.'<br>';	?>
        </div><?php
	}	?>
<form method="post" name="eventForm">

<div class="createEventSection">
<div class="eventRequiredText"><span>*</span> Required Field</div>
<?php

$query="SHOW COLUMNS FROM newEvents";
//$query="SHOW COLUMNS FROM event";  for testing at local
$result = mysql_query($query);
$getAllEventTypes  = getEventTypes(); 


while($row=mysql_fetch_object($result))
{ 
	if($row->Field == 'id')
		continue;
		
	$temp = substr($row->Field, -1); // extract last word from field name
	
	if($temp == '_')  //check if last word is '_', it will hide the fields on form
		continue;
	
	if($row->Field == "eventCategory")
	{	?>
        <div class="createEventRow">
            <div class="createEventCell"><?php
				echo formCaption($row->Field,$requiredFields);   ?>
            </div>
            <div class="createEventCell2">
                    <select name="<?php echo $row->Field; ?>[]" id="<?php echo $row->Field; ?>" multiple="multiple"    class="dropDown"> 
    <?php 		 		foreach($getAllEventTypes as $filterEvents)
                        { 
                            $temp = '';
                            if(!empty($_POST[$row->Field]))
                                if(in_array($filterEvents,$_POST[$row->Field]))
                                $temp = "selected='selected'";   ?>
                                <option value='<?php echo $filterEvents?>' <?php echo $temp?>> 
                                    <?php echo $filterEvents?>
                                </option><?php
						}  ?>
					</select>
            </div>
        </div><?php 
	}
	elseif($row->Field == "eventLocationState")
	{ ?>
        <div class="createEventRow">
            <div class="createEventCell"><?php
				echo formCaption($row->Field,$requiredFields);     ?>
            </div>
            <div class="createEventCell2"><?php 
				stateDropDown($_POST[$row->Field],$row->Field); ?>
            </div>
        </div><?php       
	}
	elseif($row->Type == "date") // show date picker if data type is date
	{ ?>
        <div class="createEventRow">
            <div class="createEventCell"><?php
				echo formCaption($row->Field,$requiredFields);     ?>
            </div>
            <div class="createEventCell2"><input type="text" name="<?php echo $row->Field; ?>" id="<?php echo $row->Field; ?>" value="<?php echo $_POST[$row->Field]?>" readonly="readonly" placeholder="mm/dd/yyyy"/>
                <script> $( "#<?php echo $row->Field; ?>" ).datepicker(); </script>
				<?php if($row->Field !="startDateOfEvent")
				{ ?>
					<input type="button" id="idClear" onclick="clearData('<?php echo $row->Field; ?>','')" title="Clear" class="clear" name="" >
					<?php 
				}	?>	
            </div>
        </div><?php 
	}
	elseif($row->Type == "time") // show time picker if data type is time
	{ 
		if($row->Field =="earlyWeigh-inTime" || $row->Field =="dayOfEventWeigh-inTime")
		{	?>
            <div class="createEventRow">
                <div class="createEventCell"><?php		
					echo formCaption($row->Field,$requiredFields); 	  	?>
                </div>
                <div class="createEventCell2">
                <input type="text" name="<?php echo $row->Field; ?>" id="<?php echo $row->Field; ?>" value="<?php echo $_POST[$row->Field]?>" placeholder="Start Time" readonly="readonly" class="timeTextBox"/>
                <input type="button" id="idClear" onclick="clearData('<?php echo $row->Field; ?>','')" title="Clear" class="clear" name="" >
				
				<input type="text" name="<?php echo $row->Field; ?>_" id="<?php echo $row->Field; ?>_" value="<?php echo $_POST[$row->Field."_"]?>" placeholder="End Time" readonly="readonly" class="timeTextBox" />
                <input type="button" id="idClear" onclick="clearData('<?php echo $row->Field; ?>_','')" title="Clear" class="clear" name="" >
				<script>
				$('#<?php echo $row->Field; ?>').timepicker({ timeFormat: 'hh:mm tt' }); 
                $('#<?php echo $row->Field; ?>_').timepicker({ timeFormat: 'hh:mm tt' }); 
                </script>
                </div>
            </div><?php	
		}
		else
		{ ?>
            <div class="createEventRow">
                <div class="createEventCell"><?php		
                   echo formCaption($row->Field,$requiredFields);  	?>
                </div>
                <div class="createEventCell2">
                	<input type="text" name="<?php echo $row->Field; ?>" id="<?php echo $row->Field; ?>" value="<?php echo $_POST[$row->Field]?>" placeholder="xx:xx AM/PM" readonly="readonly" />
                	<script> 	$('#<?php echo $row->Field; ?>').timepicker({ timeFormat: 'hh:mm tt' }); </script>
					<?php if($row->Field =="endTimeOfEvent")
					{ ?>
						<input type="button" id="idClear" onclick="clearData('<?php echo $row->Field; ?>','')" title="Clear" class="clear" name="" >
						<?php 
					}	?>
                </div>
            </div><?php
		}
	}
	elseif($row->Type == "text") // show the text editor if data type is text
	{ 
		?>
        <div class="createEventRow">
            <div class="createEventCell"><?php
                echo formCaption($row->Field,$requiredFields);    ?>
            </div>
            <div class="createEventCell2">
            	<textarea name="<?php echo $row->Field; ?>" class="textEditor" id="<?php echo $row->Field; ?>"><?php echo stripslashes($_POST[$row->Field])?></textarea>
			</div>
        </div><?php
	}
	else
	{ 
		$tempArray=array();
		$tempArray=explode('(',$row->Type);
		$rest = '';
		if($tempArray[0] == 'varchar') // show text box if data type is varchar
		{ 	
			$rest = substr($tempArray[1], 0, -1);
			
			if($row->Field == "eventImage/logoUpload")
			{ ?>
                <div id="example2" class="createEventRow">
                    <div class="createEventCell"><?php
						echo formCaption($row->Field,$requiredFields);    ?>
                    </div>
                    <div class="createEventCell2">
                    	<input type="button" id="button2" name="button2" value="Upload">
                        <div class="files">
                       <?php if(!empty($_POST[$row->Field]))
					   			echo "<div><img src='./uploads/".$_POST[$row->Field]."' height='50'>
								<a href='javascript:;' onclick='removeFile(this, \"".$_POST[$row->Field]."\",2)'>Remove</a></div>";   ?>
                        </div>
                        <input type="hidden" name="<?php echo $row->Field; ?>" id="<?php echo $row->Field; ?>" value="<?php echo $_POST[$row->Field] ?>">
						<div id="imageLoaderLogo"></div>
                    </div>
					
                </div><?php
			}
			elseif($row->Field == "uploadFlyerForEvent")
			{ ?>
                <div id="example3" class="createEventRow">
                    <div class="createEventCell"><?php
						echo formCaption($row->Field,$requiredFields);  ?>
                    </div>
                    <div class="createEventCell2">
                    	<input type="button" id="button3" name="button3" value="Upload">
                        <div class="files">
                        <?php if(!empty($_POST[$row->Field]))
								{
									if(strpos($_POST[$row->Field],',')===false)
										$str = '<div>'.$_POST['fileName_']." <a href='javascript:;' onclick='removeFile(this,\"".$_POST[$row->Field]."\",1)'>Remove</a></div>";
									else
									{
										$tempArray= explode(',',$_POST[$row->Field]);
										$tempFileArray= explode(',',$_POST['fileName_']);
										$counter=0;
										foreach($tempArray as $val)
										{
											$str .= "<div>".$tempFileArray[$counter]." <a href='javascript:;' onclick='removeFile(this,\"".$val."\",1)'>Remove</a></div>";
											$counter++;
										}
									}
									echo $str;
									unset($str);
								}   ?>
                        </div>
                        <input type="hidden" name="<?php echo $row->Field; ?>" id="<?php echo $row->Field; ?>" value="<?php echo $_POST[$row->Field]?>">
                        <input type="hidden" name="fileName_" id="fileName_" value="<?php echo $_POST['fileName_']?>">
						<div id="imageLoader"></div>  
                    </div> 
                </div><?php
			}
			else
			{ ?>
                <div class="createEventRow">
                    <div class="createEventCell"><?php
						echo formCaption($row->Field,$requiredFields);  ?>
                    </div>
                    <div class="createEventCell2">
                    	<?php
                        if($row->Field =="eventLocationAddress")
						{ 		?>
                        	<textarea name="<?php echo $row->Field; ?>" id="<?php echo $row->Field; ?>" placeholder="Enter Address" cols="2"><?php echo trim($_POST[$row->Field])?></textarea><?php 
						} 
						else
						{	
							$event="";
							if($row->Field == "eventLocationZipCode")
								$event = "onKeyPress='return onlynum(event)'";
							elseif($row->Field == "contactPhoneNumber-public" || $row->Field == "contactPhoneNumber-admin")
								$event = "onKeyPress='return numberDot(event)'";
								
						?>
							<input type="text" name="<?php echo $row->Field; ?>" id="<?php echo $row->Field; ?>" <?php echo $event; ?> maxlength="<?php echo $rest; ?>" value="<?php echo $_POST[$row->Field]?>" placeholder="<?php echo placeHolderMessage($row->Field); ?>"/><?php		
							if($row->Field == "contactPhoneNumber-public" || $row->Field == "contactPhoneNumber-admin")
                			{
								$str = ($row->Field == "contactPhoneNumber-public") ? '<span class="note">(Optional - number public can call for info)</span>' : '<span class="note">(Required -  for use by GTAS Admin only)</span><div class="copyPublicPhone">
<input type="checkbox" name="copy_" id="copy_" style="width: 20px;"> 
Copy to public phone
</div>';
								echo $str;
								unset($str);
							}
						}
						?>  
                        
                    </div>
                </div>
	<?php		if($row->Field == "eventLocationZipCode")
                {?>
                    <div class="createEventButtons">
                        <a href="https://tools.usps.com/go/ZipLookupAction!input.action" class="clsAnchor" target="_blank">
                            <input type="button" title="" class="eventbutton" value="Find ZIP using address" name="">
                        </a>
                    </div>
<?php			} 
				unset($tempStr,$pieces);
			}
		}
		elseif($tempArray[0]=='tinyint') //show radio if data type is tinyint
		{ ?>
            <div class="createEventRow">
                <div class="createEventCell">
                <?php
               echo formCaption($row->Field,$requiredFields);
                ?>
            	</div>
                <div class="createEventCell2"> 
                    <select name="<?php echo $row->Field; ?>">
                    <option value='0' <?php echo $_POST[$row->Field] == 0 ? "selected='selected'" : '' ; ?>>No</option>
                    <option value='1' <?php echo $_POST[$row->Field] == 1 ? "selected='selected'" : '' ; ?>>YES</option>
                    </select>
               </div>
            </div>
<?php	}
		else  
		{ 	?>
            <div class="createEventRow">
                <div class="createEventCell">
<?php				$pieces = preg_split('/(?=[A-Z])/',$row->Field);
					$tempStr=implode(' ',$pieces);
					echo ucwords($tempStr);
					if(array_key_exists($row->Field,$requiredFields))
						echo '<span> *</span>';
					unset($tempStr,$pieces);        ?>
                </div>
                <div class="createEventCell2"><input type="text" name="<?php echo $row->Field; ?>" id="<?php echo $row->Field; ?>" value="<?php echo $_POST[$row->Field]?>" /></div>
            </div>
<?php	}
	}
} ?>
		<div class="createEventButtons">
           <input id="firstNumber" class="sum" type="text" name="firstNumber" value="<?php echo rand(1,4) ?>" readonly="readonly" /> +
<input id="secondNumber" class="sum" type="text" name="secondNumber" value="<?php echo rand(5,9) ?>" readonly="readonly" /> =
<input id="captcha" class="captcha" type="text" name="captcha" maxlength="2" />
<span id="spambot">(Are you human, or spambot?)</span>
        </div>
		<div class="createEventButtons">
            <input type="submit" name="submit" class="eventbutton"  value="Create Event">
            <input type="reset" name="reset" value="Reset" onclick="resetall()" class="eventbutton" title="" />
            
        </div>

</div>
</form>
<?php
unset($temp,$tempArray);
} ?>
</div>
</div><!-- contentDiv END -->
</div><!-- mainInnerDiv END -->
</div><!-- mainDiv END -->
<div id="Copyright">
			Membership Software Powered by <a href="http://www.yourmembership.com/">YourMembership.com<span style="font-size:11px">&reg;</span></a> &nbsp;::&nbsp; <a href="https://gtallsports.site-ym.com/ams/legal-privacy.htm">Legal/Privacy</a>
</div>
</body>

</html>
<script type="text/javascript">
 
</script>
<script type="text/javascript" src="./js/jsFunctions.js"></script>
<script type="text/javascript" src="./js/nicEdit.js"></script>
<script type="text/javascript" src="./js/ajaxupload.js"></script>
<script type= "text/javascript">/*<![CDATA[*/
	bkLib.onDomLoaded(function() {
	var myEditor = new nicEditor({buttonList : ['bold','italic','underline','left','center','right','justify','ol','ul','fontSize','fontFamily','fontFormat','subscript','superscript','strikethrough','removeformat','indent','outdent','hr','forecolor','bgcolor','link','unlink']}).panelInstance('enterEventDetails');
	myEditor.addEvent('blur',function() {
	var summarytemp = myEditor.instanceById('enterEventDetails').getContent();
	 var countOfEnter = jQuery('.nicEdit-main').text().length;

	 if(countOfEnter > 500)
	 {	jQuery('.nicEdit-main').text(jQuery('.nicEdit-main').text().substr(0,500)); }
	});
	  });
	  $('#copy_').click(function(){

    if($(this).is(':checked'))
    {
        $('#contactPhoneNumber-public').val( $('#contactPhoneNumber-admin').val() );
        $("#contactPhoneNumber-public").attr('readonly','readonly');
    }
    else
    {
    	$("#contactPhoneNumber-public").removeAttr("readonly");
    }
    });
    
  
    	$('#contactPhoneNumber-admin').keyup(function(){
			if($('#copy_').is(':checked')){
    		$('#contactPhoneNumber-public').val( $('#contactPhoneNumber-admin').val() );
    		}
  		});
function resetall()
{
	var img = $("#eventImage\\/logoUpload").val();
	if(img!='')
	{
		removeFiles(img);
		$("#eventImage\\/logoUpload").val('');
		$('#example2 .files').html('');	
	}
	var flyer = $("#uploadFlyerForEvent").val();
	if(flyer !='')
	{
		removeFiles(flyer);
		$("#fileName_").val('');
		$("#uploadFlyerForEvent").val('');
		$('#example3 .files').html('');	
	}
}
</script> 
<?php 
if($_POST['eventImage/logoUpload']!='')
{	 ?>
	<script>
    $(document).ready(function(){
    $("#button2").attr("disabled", "disabled");
    });
    
    
});
    </script><?php 
} ?>
<style>
#imageLoaderLogo {
	display: block;
	clear: both;
	width: 30px;
	height: 20px;
	background-image:url('style/images/ajax-loader.gif');
	background-repeat:no-repeat;
	display:none;
}

#imageLoader {
	display: block;
	clear: both;
	width: 30px;
	height: 20px;
	background-image:url('style/images/ajax-loader.gif');
	background-repeat:no-repeat;
	display:none;
}
</style>


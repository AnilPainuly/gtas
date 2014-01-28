// JavaScript Document
$(document).ready(function(){
 

	/* example 2 */
	new AjaxUpload('#button2', {
		action: 'ajaxFunctions.php',
		data : {
			'type' : "UPLOAD"
		},
		  onChange: function(file, extension){
			  	if($("#eventImage\\/logoUpload").val() != '')
					return false;
			  },
		onSubmit : function(file , ext){
			if (ext && /^(jpg|png|jpeg|gif)$/.test(ext)){
			 $("#button2").attr("disabled", "disabled");
			 $("#imageLoaderLogo").css('display','block');
			} else {
				// extension is not allowed
				alert("Only jpg/png/gif files are allowed");
				//$('#example2 .files').text('Error: only images are allowed');
				// cancel upload
				return false;				
			}
		},
			onSuccess : function(msg){
		},
		
		onComplete: function(file, response) {
		var rmLink = '<div><img src="./uploads/'+ response +'" height="50">' + '  <a href="javascript:;" onclick=removeFile(this,\"'+ response + '\",2)>Remove</a></div>';
			$('#example2 .files').html(rmLink);
			$("#eventImage\\/logoUpload").val(response);
			$("#imageLoaderLogo").css('display','none');
		  }
			
	});
	
/* example 2 */
	new AjaxUpload('#button3', {
		action: 'ajaxFunctions.php',
		data : {
			'type' : "UPLOAD"
		},
		onSubmit : function(file , ext){
			if (ext && /^(pdf)$/.test(ext)){
				$("#imageLoader").css('display','block');
			
			} else { // extension is not allowed
				alert("Only pdf files are allowed");
				//$('#example3 .files').text('Error: only PDF files are allowed');
				return false; // cancel upload
			}
		},
		onChange: function(file, extension){
				var temp = $("#uploadFlyerForEvent").val() ;
				var tempArray = temp.split(",");
		
				if(tempArray.length >= 10)
				{
					$("#button3").attr("disabled", "disabled");
					alert("you can not upload files more than 10.");
					return false;
				}
					
			  },
			  
		onSuccess : function(msg){
		},
		
		onComplete: function(file, response) {
		
		var rmLink = '<div>'+ file + '  <a href="javascript:;" onclick=removeFile(this,\"'+ response + '\",1)>Remove</a></div>';
		var temp = ($('#example3 .files').html() == '') ? rmLink : $('#example3 .files').html() + rmLink  ;
				$('#example3 .files').html(temp);	
		
		if($("#fileName_").val() != "")		
			$("#fileName_").val($("#fileName_").val() + ',' + file   );
		else
			$("#fileName_").val(file);
				
		var temp = ($("#uploadFlyerForEvent").val() == '') ? response : $("#uploadFlyerForEvent").val() + "," + response ;
		var tempArray = temp.split(",");
		
			if(tempArray.length >= 10)
				$("#button3").attr("disabled", "disabled");
				
		  $("#uploadFlyerForEvent").val(temp);	
		$("#imageLoader").css('display','none');
		  }
	});
	
});/*]]>*/
function removeFile(obj, fileName, type)
{
	if(type == 1)
	{
		var temp = $("#uploadFlyerForEvent").val();

		var temparray = temp.split(",");
		
		var filename = $("#fileName_").val();
		var filenamearray = filename.split(",");
		
		var str ='';
		var strFile=''
		for(i=0;i<temparray.length;i++)
		{
			if(fileName !=	temparray[i])
			{
				str = str=='' ? temparray[i] : str + ',' + temparray[i];
				strFile= strFile=='' ? filenamearray[i] : strFile + ',' + filenamearray[i];
			}
		}
		
		if(parseInt(temparray.length) - 1 < 10)
				$("#button3").removeAttr("disabled");
				
		$("#uploadFlyerForEvent").val(str);
		$("#fileName_").val(strFile);
	}
	$.ajax({
			type: "POST",
			url: "./ajaxFunctions.php",
			data: "type=UNLINK&fileName=" + fileName,
			success: function(msg)
			{
				if(msg == 0)
					alert("Due to some problem not able to delete this file please try again.");
				else
				{	
					$(obj).parent().remove();
					if(type== 2)
					{
						$('#button2').removeAttr('disabled');
						$("#eventImage\\/logoUpload").val('');
					}
				}
			},
			async: false
		});
}
function removeFiles(fileName)
{
	$.ajax({
			type: "POST",
			url: "./ajaxFunctions.php",
			data: "type=UNLINKALL&fileName=" + fileName,
			success: function(msg)
			{
				if(msg == 0)
					alert("Due to some problem not able to delete this file please try again.");
			},
			async: false
		});
}
	
$("#eventCategory").multiselect({header: false}); 
$("form").bind("submit", function(){   
}); 
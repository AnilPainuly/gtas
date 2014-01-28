// JavaScript Document
$(document).ready(function(){
 
 
	/* example 2 */
	new AjaxUpload('#button2', {
		action: 'ajaxFunctions.php',
		data : {
			'type' : "UPLOAD"
		},
		onSubmit : function(file , ext){
			if (ext && /^(jpg|png|jpeg|gif)$/.test(ext)){
			 $("#button2").attr("disabled", "disabled");
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
			
			} else { // extension is not allowed
				alert("Only pdf files are allowed");
				//$('#example3 .files').text('Error: only PDF files are allowed');
				return false; // cancel upload
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
		
			if(tempArray.length == 10)
				$("#button3").attr("disabled", "disabled");
				
		  $("#uploadFlyerForEvent").val(temp);	
		
		  }
	});
	
});/*]]>*/
function removeFile(obj, fileName, type)
{
	if(type == 1)
	{
		var temp = $("#uploadFlyerForEvent").val();
		
			//temp.replace(fileName,"");
		var temparray = temp.split(",");
		
		var filename = $("#fileName_").val();
		var filenamearray = filename.split(",");
		
		var str ='';
		var strFile=''
		for(i=0;i<temparray.length;i++)
		{
			if(fileName !=	temparray[i])
			{
				str = str=='' ? temparray[i] : ','+temparray[i];
				strFile= strFile=='' ? filenamearray[i] : ',' + filenamearray[i];
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
					$('#button2').removeAttr('disabled');
				}
			},
			async: false
		});
}
$("#eventCategory").multiselect(); 
$("form").bind("submit", function(){   
}); 
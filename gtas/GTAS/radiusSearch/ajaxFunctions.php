<?php
# Name		:		ajaxFunctions.php
# Author	:		Matthew Umesh
# Date		:		Dec 26,2013
# Purpose	:		to fulfil ajax request


$type = $_REQUEST['type'];
$uploaddir = './uploads/';
if($type=="UPLOAD") // upload file on server
{

	$fileArray = explode(".",$_FILES['userfile']['name']);
	$ext = $fileArray[count($fileArray)-1];
	$newFileName = time().".".$ext;
	$uploadfile = $uploaddir.$newFileName;
	
	if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
	  echo $newFileName;
	} else {
	  // WARNING! DO NOT USE "FALSE" STRING AS A RESPONSE!
	  // Otherwise onSubmit event will not be fired
	  echo "error";
	}
}
elseif($type=="UNLINK") // if user click on remove link
{
	$fileName = $_POST['fileName'];
	unlink($uploaddir.$fileName); // remove the file from server
	if(file_exists($uploaddir.$fileName))
		echo "0";
	else
		echo "1";
}
elseif($type=="UNLINKALL") // if user click on remove link
{
	$fileName = $_POST['fileName'];
	if(strpos($fileName,",") === false)
	{
		unlink($uploaddir.$fileName); // remove the file from server
		if(file_exists($uploaddir.$fileName))
			echo "0";
		else
			echo "1";
	}
	else
	{
		$tempArray = explode(",",$fileName);
		foreach($tempArray as $val)
		{
			unlink($uploaddir.$val); // remove the file from server
			if(file_exists($uploaddir.$val))
				echo "0";
			else
				echo "1";
		}	
	}
}
?>
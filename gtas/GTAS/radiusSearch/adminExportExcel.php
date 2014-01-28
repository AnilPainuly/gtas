<?php

	include("excelwriter.inc.php");
	$name='exportedFile'.time().'.xls';
	$excel=new ExcelWriter('exportedFiles/'.$name);
	$data = $_POST['data'];
	if($excel==false)	
		echo $excel->error;	
	$myArr=array("EVENT NAME","EVENT TYPE","EVENT START DATE","N/W EVENT","STATE");
	$excel->writeLine($myArr);
	foreach ($data as $key => $value) {
			$excel->writeLine($value);
	}
	$excel->close();
	$filename = $name;
 
   echo $filename;

// code to delete the files from the directory which are older than 1 hour
 $path=dirname(__FILE__).'/exportedFiles/';
  if (is_dir("$path") ) 
        { 
           $handle=opendir($path); 
           while (false!==($file = readdir($handle))) { 
               if ($file != "." && $file != "..") {  
                   $Diff = time() - filectime("$path/$file");
                   if ($Diff > 3600) unlink("$path/$file");

               } 
           }
           closedir($handle); 
        }

    // code to delete the files from the directory which are older than 1 day ends here
	 
?>

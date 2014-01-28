<?php
function price($name)
{
	$details= array(
			  'abc'=>100,
			  'xyz'=>200 		
				   );
	foreach($details as $n=>$p)
	{
		if($name==$n)
			$price=$p;
	}			   
	return $price;
} 

function arryCheck()
{
	$details= array(
			  'abc'=>100,
			  'xyz'=>200 		
				   );
	return $details;
}
?>
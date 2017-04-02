<?php 
	
	$variable = "koerakuut";
	$newVariable = "";
	for($i=1; $i<strlen($variable)+1; $i++){
		$newVariable .= $variable[strlen($variable)-$i];
	}
	echo $newVariable;
?>
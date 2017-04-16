<?php
	require_once('head.html');
	$galerii= array( 
		array('src'=>'pildid/nameless1.jpg', 'alt'=>'nimetu 1', 'value' => '1', 'id' => 'p1'), 
		array('src'=>'pildid/nameless2.jpg', 'alt'=>'nimetu 2', 'value' => '2', 'id' => 'p2'), 
		array('src'=>'pildid/nameless3.jpg', 'alt'=>'nimetu 3', 'value' => '3', 'id' => 'p3'), 
		array('src'=>'pildid/nameless4.jpg', 'alt'=>'nimetu 4', 'value' => '4', 'id' => 'p4'), 
		array('src'=>'pildid/nameless5.jpg', 'alt'=>'nimetu 5', 'value' => '5', 'id' => 'p5'), 
		array('src'=>'pildid/nameless6.jpg', 'alt'=>'nimetu 6', 'value' => '6', 'id' => 'p6') 
	);
	
	$leht = "";
	if (!empty($_GET["page"])) {
	    $leht = $_GET["page"];
	} else {
		$leht = "pealeht";
	}
	switch($leht){
		case "pealeht":
			include('pealeht.html');
		break;
		case "galerii":
			include('galerii.html');
		break;		
		case "tulemus":
			include('tulemus.html');
		break;
		case "vote":
			include('vote.html');
		break;
	} 
	require_once('foot.html');
?>
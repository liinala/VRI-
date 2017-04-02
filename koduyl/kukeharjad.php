<?php 
	$kukeharjad= array( 
		array('kukehari'=>'Variegata', 'varvus'=>'kollakasroheline', 'kirjeldus'=>'kiviktaimlasse ja murusse'), 
		array('kukehari'=>'Autumn Joy', 'varvus'=>'punanae', 'kirjeldus'=>'vabakujulistesse lillerühmadesse'), 
		array('kukehari'=>'Thundercloudi', 'varvus'=>'valge', 'kirjeldus'=>'pinnakattetaimena kalmistule'), 
		array('kukehari'=>'Vodoo', 'varvus'=>'punane', 'kirjeldus'=>'püsilillepeenrale äärislilleks'), 
		array('kukehari'=>'Tricolori', 'varvus'=>'kolmevärviline', 'kirjeldus'=>'aiavaasis ja rõdukasti'), 
	);
	
	include 'algus.html';
	foreach ($kukeharjad as $kukehari) {
		include 'kukeharjad.html';
	}
	include 'l6pp.html';
?>
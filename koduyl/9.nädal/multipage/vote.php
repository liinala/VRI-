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
?>
	<h3>Vali oma lemmik :)</h3>

	<form action="tulemus.php" method="GET">

		<?php 
			foreach ($galerii as $pilt) {
				echo "<p>
						<label for=\"".$pilt['id']."\">
							<img src=\"".$pilt['src']."\" alt=\"".$pilt['alt']."\" height=\"100\" />
						</label>
						<input type=\"radio\" value=\"".$pilt['value']."\" id=\"".$pilt['id']."\" name=\"pilt\"/>
					</p>";
			}
		?>

		<br/>
		<input type="submit" value="Valin!"/>
	</form> 


<?php	
	require_once('foot.html');
?>
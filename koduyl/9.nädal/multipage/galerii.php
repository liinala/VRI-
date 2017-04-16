<?php
	require_once('head.html');
?>

	<h3>Fotod</h3>
	<div id="gallery">
		<?php
			
			$galerii= array( 
				array('src'=>'pildid/nameless1.jpg', 'alt'=>'nimetu 1'), 
				array('src'=>'pildid/nameless2.jpg', 'alt'=>'nimetu 2'), 
				array('src'=>'pildid/nameless3.jpg', 'alt'=>'nimetu 3'), 
				array('src'=>'pildid/nameless4.jpg', 'alt'=>'nimetu 4'), 
				array('src'=>'pildid/nameless5.jpg', 'alt'=>'nimetu 5'), 
				array('src'=>'pildid/nameless6.jpg', 'alt'=>'nimetu 6'), 
			);
			foreach ($galerii as $pilt) {
				echo "<img src=\"".$pilt['src']."\" alt=\"".$pilt['alt']."\">";
			}
					
		?>

	</div>

<?php
	require_once('foot.html');
?>
<?php
	require_once('head.html');
	
	if (empty($_GET)) {
	    echo "<p>Valikut ei tehtud. Palun tee oma valik!</p>";
	} else {
		echo "<p>Aitäh, teie valik on kinnitatud!</p>";
	}
	
	require_once('foot.html');
?>

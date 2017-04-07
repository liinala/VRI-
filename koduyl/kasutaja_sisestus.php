<?php 
	$background_color="white";
	if (isset($_POST['background_color']) && $_POST['background_color']!="") {
 	  $background_color=htmlspecialchars($_POST['background_color']);
	} 
	$text_color="black";
	if (isset($_POST['color']) && $_POST['color']!="") {
 	  $text_color=htmlspecialchars($_POST['color']);
	} 
	$border_width='5';
	if (isset($_POST['borderwidth']) && $_POST['borderwidth']!="") {
 	  $border_width=htmlspecialchars($_POST['borderwidth']);
	} 
	$border_style='solid';
	if (isset($_POST['borderstyle']) && $_POST['borderstyle']!="") {
 	  $border_style=htmlspecialchars($_POST['borderstyle']);
	} 
	$border_color='green';
	if (isset($_POST['bordercolor']) && $_POST['bordercolor']!="") {
 	  $border_color=htmlspecialchars($_POST['bordercolor']);
	} 
	$radius='23';
	if (isset($_POST['borderradius']) && $_POST['borderradius']!="") {
 	  $radius=htmlspecialchars($_POST['borderradius']);
	} 
	$userstext="Siin kuvatakse sisestatud tekst";
	if (isset($_POST['text']) && $_POST['text']!="") {
 	  $userstext=htmlspecialchars($_POST['text']);
	} 
	$textsize="30";
	if (isset($_POST['size']) && $_POST['size']!="") {
 	  $textsize=htmlspecialchars($_POST['size']);
	} 
?>

<!DOCTYPE html>
<html>

<head>
  <title>8. nädala ülesanne</title>
  <meta charset="utf-8"> 
  <style type="text/css">
  	#valikud {
		margin: 10px;
		padding: 15px;
		border-style: solid;
		border-color: green;
		border-width: 2px;
	}
	#kasutajasisestus {
		margin: 10px;
		padding: 5px;
		color: <?php echo $text_color; ?>;
		border-style: <?php echo $border_style; ?>;
		border-color: <?php echo $border_color; ?>;
		border-width: <?php echo $border_width; ?>px;
		border-radius: <?php echo $radius; ?>px;
		background-color: <?php echo $background_color; ?>;
		font-size: <?php echo $textsize; ?>px;
	}
	
  </style>
</head>

<body>
	<div id="kasutajasisestus">
		<?php echo $userstext; ?>
	</div>
	<div id="valikud">
		<form action="kasutaja_sisestus.php" method="post">

	 	<textarea name="text">Siia kirjuta kommentaar</textarea><br>
		<br>Taust ja tekst:
		<br>
		<input type="color" name="background_color"> Taustavärv<br>

		<input type="color" name="color"> Tekstivärv<br>

		<input type="number" name="size" min="10" max="72"> Teksti suurus (vahemik 10-72px)<br>
		<br>
		Piirjoon:<br>
		<input type="number" name="borderwidth" min="1" max="20"> Piirjoone laius (vahemik 1-20px)<br>
		<select name="borderstyle">
  			<option>solid</option>
  			<option>double</option>
  			<option>dotted</option>
  			<option>dashed</option>	
		</select>
		<br>
		<input type="color" name="bordercolor"> Piirjoone värv<br>
		<input type="number" name="borderradius" min="0" max="100"> Piirjoone nurga raadius (vahemik 0-100px)<br>
		<input type="submit" value="kuva">
	</form> 
	</div>

	
</body>

</html>

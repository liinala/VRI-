<?php
function connectToDb()
{
	$user = "test";
	$pass = "t3st3r123";
	$db= "test";
	$host= "localhost";
	$link= mysqli_connect($host, $user, $pass, $db) 
	or die("ei saa ühendatud - ".mysqli_error());
	return $link;
}

function getRows($dblink)
{
	$rows = [];
	$sql = "SELECT * FROM sedum_llaumets ORDER BY sordinimi";
	$result = $dblink->query($sql);

	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$rows[] = $row;
		}
	}
	
	return $rows;
}

function addRow($dblink, $row) {
	$row['kasvukorgus'] = (int) $row['kasvukorgus'];
	$row['aiand'] = (int) $row['aiand'];
	
	if (empty($row['aiand'])) {
		throw new Exception('Aiand puudu');
	}
	//var_dump($row);die();
	$sql = sprintf("INSERT INTO sedum_llaumets (kasvukorgus, sordinimi, aiand, kasvuviis, varvus) 
	VALUES (%d, '%s', %d, '%s', '%s')", $row['kasvukorgus'], $row['sordinimi'], $row['aiand'], $row['kasvuviis'], $row['varvus']);

	if ($dblink->query($sql) !== TRUE) {
		throw new Exception('Rea lisamine ebaõnnestus');
	}
}
$error = null;

	$dblink = connectToDb();
	
	try {
		if (isset($_POST) && !empty($_POST)) {
			addRow($dblink, $_POST);
		}
	} catch (Exception $e) {
		$error = $e->getMessage();
	}

	$rows = getRows($dblink);
	$dblink->close();

?> 

<!DOCTYPE html>
<head>
	<meta charset="utf-8"/>
	<style>

	.roomav {
		background: green;
	}
	.püstine {
		background: brown;
	}
	.error {
		color: red;
	}
	</style>
</head>
<body>
<nav>
		<ul>
			<a href="index.html">Home</a>
			<a href="nimekiri.html">Soovide nimekiri</a>
			<a href="gallery.html">Galerii</a>
			<a href="videod.html">Videod</a>
			<a href="viktoriin.html">Viktoriin</a>
			<a href="todo.html">Tööde nimekiri</a>
		</ul>
	</nav>	
	<?php if (!empty($error)) {?> 
		<div class='error'><?php echo $error ?></div>
	
	<?php } ?>

<form method="POST" action="">
	<table border="1">
		<caption>Nimekiri 7. kukeharjaga</caption>
		<tr>
			<th>id</th>
			<th>keskmine kasvukõrgus (cm)</th>
			<th>sordinimi</th><th>aiand</th>
			<th>kasvuviis(R-roomav/P-püstine)</th>
			<th>lehevärvus</th>
		</tr>
		<?php foreach ($rows as $row) { ?>
		<tr class=roomav>
			<td><?php echo $row['id'] ?></td>
			<td><?php echo $row['kasvukorgus'] ?></td>
			<td><?php echo $row['sordinimi'] ?></td>
			<td><?php echo $row['aiand'] ?></td>
			<td><?php echo $row['kasvuviis'] ?></td>
			<td><?php echo $row['varvus'] ?></td>
		</tr>
		<?php } ?>
		<tr>
			<td><input type="submit" value="Sisesta" /></td>
			<td><input type="numeric" name="kasvukorgus" maxlength="4" size="4"/></td>
			<td><input type="text" name="sordinimi" /></td>
			<td><input type="numeric" name="aiand" maxlength="4" size="4"/></td>
			<td><input type="text" name="kasvuviis" maxlength="4" size="4"/></td>
			<td><input type="text" name="varvus" /></td>
		</tr>
	</table>
</form>
</body>
<?php
session_start();
?>

<link rel="stylesheet" type="text/css" href="styles.css">

	<div class="loginDiv2"> <!-- contains everything -->

<?php
echo "Hello, " . "{$_SESSION['id']}" . ". Here are your" . "<span style='color:red;'>". " passwords " . 
"</span>" ." and descriptions:" . "<br/>";

require_once 'DataBaseAdaptor.php';
$passwordArray = $myDatabaseFunctions->getPasswordsArray();
echo '<br>';
foreach ($passwordArray as $data){ 
	$counter = 0;
	?>
	<form action="controller.php" method="post">
	<div class="pass">
	<?php
	foreach ($data as $part){
		if ($counter == 1){
			echo '<font color="red">' . strrev($part) .' </font>'; 
		}else if ($counter > 1){
			echo strrev($part) . " ";
		}
		$counter++;
	}
	?>
	<button type="submit" class="delete" name="passID" value="<?= $data['id']?>">Delete Password</button>
	</div><br>
	</form>
	
	<?php
}
?>
<div class="homeButtons">
<br><br>
<form action="controller.php" method="POST">
	<input class="loginButton3" id="logout" type="submit" name="logout" value="Logout">
</form>

<form action="addPass.html" method="POST">
	<input class="loginButton2" type="submit" name="addPass" value="Add Password">
</form>

</div>
	</div>
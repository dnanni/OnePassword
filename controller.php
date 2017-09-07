<?php
session_start();

require_once 'DataBaseAdaptor.php';
$myDatabaseFunctions -> makeTables(); // makes username & password tables if not made

if (isset($_POST['pass']) && isset($_POST['desc'])) { // add a password and description
	$pass = strrev($_POST ['pass']); 		// reverse input for minor security
	$desc = strrev($_POST ['desc']);		// due to issues with encryption
	$id = $_SESSION ['id'];
	$myDatabaseFunctions->addNewPass($id, $pass, $desc);
	header ( "Location: index.php?mode=homepage" );
} elseif (isset($_POST['regUser']) && isset($_POST['regPass'])){ // register
	$user = $_POST['regUser'];
	$pass = password_hash($_POST['regPass'], PASSWORD_DEFAULT);
	$valid = $myDatabaseFunctions->checkValid($user);
	if ($valid == 'No'){
		$_SESSION["error"] = "<br>Username taken! Please choose another.";
		require_once 'register.html';
		echo $_SESSION["error"];
	}else{
		$myDatabaseFunctions->addUser($user, $pass);
		require_once 'login.html';
		echo "<br>User successfully created!";
	}
} elseif (isset($_POST['logUser']) && isset($_POST['logPass'])){ // login
	$username = $_POST['logUser'];
	$password = $_POST['logPass'];
	$hash = $myDatabaseFunctions->getPass($username);
	if ($hash == 'No'){
		require_once 'login.html';
		echo "<br>Invalid Username or Password. Please try again."; // wrong username
	}else{
		require_once 'login.html';
		$verify = password_verify($password, $hash);
		if (!$verify){
			echo "<br>Invalid Username or Password. Please try again."; // wrong password
		}else{
			$_SESSION['id'] = $username;
			header("Location: index.php?mode=homepage");
		}
	}
} elseif (isset($_POST['logout'])){ // logout
	$myDatabaseFunctions->logout(); 
	require_once 'login.html';
	echo "<br>Logged out successfully.";
} else { // delete password
	$_id = $_POST ['passID'];
	$myDatabaseFunctions->deletePass($_id);
	header ( "Location: index.php?mode=homepage" );
}
?>
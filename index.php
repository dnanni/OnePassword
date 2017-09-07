<html>
<head>
<title>OnePassword Password Manager</title>
<link rel="stylesheet" type="text/css" href="styles.css">
</head>

<body>
<?php
if (isset ( $_GET ['mode'] )) {
  if ($_GET ['mode'] === 'homepage')
    require_once ("homepage.php");
  elseif ($_GET ['mode'] === 'addPass')
    require_once ("./addPass.html");
  elseif ($_GET ['mode'] === 'login')
    require_once ("OnePassword/login.html");
  elseif ($_GET ['mode'] === 'register')
    require_once ("OnePassword/register.html");
} else // default
  require_once ("homepage.php");
?>
</body>
</html>
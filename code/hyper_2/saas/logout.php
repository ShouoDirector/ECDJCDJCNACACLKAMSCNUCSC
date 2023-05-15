<?php
session_start();
unset($_SESSION["user"]);
session_destroy();
session_start();
session_regenerate_id(true);
header("location: index.php");
exit();
?>

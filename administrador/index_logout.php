<?php
session_start();
unset($_SESSION['id']);
unset($_SESSION['EMAIL']); 
unset($_SESSION['LOGIN']);
unset($_SESSION['ID']);
unset($_SESSION['TIPUSU']);
session_destroy();
header("Location: index.php");

?>
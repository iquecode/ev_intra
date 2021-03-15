<?php
session_start();
unset($_SESSION['userId']);
header("location: index.php?class=LoginPage");
?>
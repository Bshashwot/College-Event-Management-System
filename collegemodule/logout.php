<?php
session_start();
unset($_SESSION["college_id"]);
unset($_SESSION["college_name"]);
unset($_SESSION["college_email"]);
header("Location: login.html");
exit();
?>

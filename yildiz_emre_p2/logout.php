<?php
session_start();
$_SESSION = array();
session_destroy();
echo "<script LANGUAGE = 'JavaScript'> window.alert('Log out'); window.location.href = 'index.php' </script>";
exit();
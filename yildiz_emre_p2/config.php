<?php
    define("DB_SERVER", "dijkstra.ug.bcc.bilkent.edu.tr");
    define("DB_USERNAME", "emre.yildiz");
    define("DB_PASSWORD", "cgVmBOl3");
    define("DB_DATABASE", "emre_yildiz");
    $db = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
    if(!$db){
        die("Connection error" . mysqli_connect_error());
    }
?>
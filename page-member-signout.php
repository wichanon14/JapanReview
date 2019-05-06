<?php 
    session_start();
    header('Location: ./page-home.php');
    session_destroy();
?>
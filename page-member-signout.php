<?php 
    session_start();

    require_once('./Service/All_php_function.php');
    recordLogs($conn,$_SESSION['user-id'],'Sign Out');

    header('Location: ./page-home.php');
    session_destroy();
?>
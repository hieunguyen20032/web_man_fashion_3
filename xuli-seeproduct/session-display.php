<?php
/**
 * Created by PhpStorm.
 * User: Minh Hieu
 * Date: 29/11/2023
 * Time: 21:22 PM
 */
session_start();
if(isset($_GET['display'])&& !empty($_GET['display'])){
    $_SESSION['display']= $_GET['display'];
}
else{
    header('location:index.php');
}


?>
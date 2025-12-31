<?php
/**
 * Created by PhpStorm.
 * User: Minh Hieu
 * Date: 29/11/2023
 * Time: 21:22 PM
 */
session_start();
include('../inc/myconnect.php');
include('../inc/function.php');

if(isset($_GET['id']) && !empty($_GET['id'])){
    $id = $_GET['id'];
    $query_product = "SELECT id_san_pham,ten_san_pham,anh_thumb FROM tb_san_pham";
    $result_product = mysqli_query($dbc, $query_product);
    kt_query($query_product, $result_product);
    $data = array();

    while ($product = mysqli_fetch_array($result_product, MYSQLI_ASSOC)) {
        $data[$product['id_san_pham']] = $product;
    }


    if (!isset($_SESSION['seen']) or empty($_SESSION['seen'])) {
        $_SESSION['seen'][$id] = $data[$id];
    } else {

        $_SESSION['seen'][$id] = $data[$id];

    };
}else{
    header('location:../index.php');
}






?>
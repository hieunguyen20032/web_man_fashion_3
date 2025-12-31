<?php
include('inc/myconnect.php');
include('inc/function.php');

if (isset($_GET['id']) && filter_var($_GET['id'], FILTER_VALIDATE_INT, array('min_range' => 1))) {
    $id = $_GET['id'];
    $query = "DELETE FROM tb_nhan WHERE id_nhan={$id}";
    $result = mysqli_query($dbc, $query);
    kt_query($query, $result);
    header("Location: list_label.php");
} else {
    header("Location: list_label.php");
    exit();
}
?>
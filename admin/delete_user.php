

<?php 
	include('inc/myconnect.php');
	include('inc/function.php');
	
	if(isset($_GET['id']) && filter_var($_GET['id'],FILTER_VALIDATE_INT,array('min_range'=>1))){
		$id = $_GET['id'];
		$query="DELETE FROM tb_nguoi_dung WHERE id_nguoi_dung={$id}";
		$result=mysqli_query($dbc,$query);
		kt_query($query,$result);
		header("Location: list_user.php");
	}
	else{
	header("Location: list_user.php");
	exit();		
	}
?>
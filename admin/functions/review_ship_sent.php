<?php
	include('../inc/myconnect.php');
	include('../inc/function.php');
	if ( isset($_GET['code_ship']) ) {
		$code_ship = $_GET['code_ship'];
		$query = "UPDATE tb_giao_hang SET  
					trang_thai_giao_hang = '2'
				WHERE ma_giao_hang = {$code_ship}";
		$result = mysqli_query($dbc, $query);
			

		header('location: ../list_delivery_sent.php');
	} 
	else {
		header('location: ../list_delivery_sent.php');
	}


?>
<?php
	include('../inc/myconnect.php');
	include('../inc/function.php');
	if ( isset($_GET['code_bill']) ) {
		$code_bill = $_GET['code_bill'];
		$query = "UPDATE tb_hoa_don SET  
					trang_thai_hoa_don  = '1'
				WHERE ma_hoa_don = {$code_bill}";
		$result = mysqli_query($dbc, $query);
		

		// tao ship 

		$code_ship = ramdom_code_ship();	
		$query_code_bill = "SELECT id_hoa_don ,id_don_hang FROM tb_hoa_don WHERE ma_hoa_don = {$code_bill}";
		$result_code_bill = mysqli_query($dbc, $query_code_bill);
		while ( $rows = mysqli_fetch_array($result_code_bill, MYSQLI_ASSOC) ) {
			$id_bill = $rows['id_hoa_don'];
			$id_order = $rows['id_don_hang'];
			$query_is_ship = "INSERT INTO `tb_giao_hang`(`ma_giao_hang` , `id_hoa_don`,`id_don_hang`, `trang_thai_giao_hang`) VALUES ('{$code_ship}','{$id_bill}','{$id_order}', 0)";
			$result_is_ship = mysqli_query($dbc, $query_is_ship);
		}
		

		header('location: ../list_bill.php');
	} 
	else {
		header('location: ../list_bill.php');
	}


?>
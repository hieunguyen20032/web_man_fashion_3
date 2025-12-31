<?php
	include('../inc/myconnect.php');
	include('../inc/function.php');
	if ( isset($_GET['code_ship']) ) {
		$code_ship = $_GET['code_ship'];
		$query = "UPDATE tb_giao_hang SET  
					trang_thai_giao_hang = '1'
				WHERE ma_giao_hang = {$code_ship}";
		$result = mysqli_query($dbc, $query);
			

	// tru so luong san pham khi khach hàng đặt sản pham do
	$query = "SELECT tb_don_hang.id_san_pham id_san_pham, tb_don_hang.kich_co_san_pham size_order, tb_don_hang.so_luong_san_pham quantity_order FROM tb_don_hang,tb_giao_hang,tb_hoa_don WHERE tb_giao_hang.id_hoa_don = tb_hoa_don.id_hoa_don  && tb_hoa_don.id_don_hang  = tb_don_hang.id_don_hang &&  ma_giao_hang=$code_ship  ORDER BY tb_don_hang.id_san_pham ASC";
	$result = mysqli_query($dbc, $query);
	while ( $rows =  mysqli_fetch_array($result, MYSQLI_ASSOC) ) {
		$id_san_pham = $rows['id_san_pham'];
		/* lay size cua san pham */
		$query_size = "SELECT kich_co_san_pham FROM tb_san_pham WHERE id_san_pham = $id_san_pham";
		$result_size = mysqli_query($dbc, $query_size);
		extract(mysqli_fetch_assoc($result_size));
		extract( $rows );
		$array_size = unserialize($kich_co_san_pham);
		$array_size[strtolower($size_order)] = $array_size[strtolower($size_order)] - $quantity_order;
		$array_size = Serialize($array_size);
		// UPDATE product 
		$query_product = "UPDATE tb_san_pham SET  
						kich_co_san_pham = '{$array_size}'
					WHERE id_san_pham = '{$id_product}'";
					// echo $query_product;
		$result_product = mysqli_query($dbc, $query_product);

		header('location: ../list_delivery.php');
	}
	
	} 
	else {
		header('location: ../list_delivery.php');
	}


?>
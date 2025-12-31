<?php 
	// session_destroy();
	session_start();

	include('../inc/myconnect.php');
	include('../inc/function.php');

	$id_product = $_GET['id'];
	if (!isset($_SESSION['order'])) {
		$_SESSION['order'] = array();

	}

	
	$array_product = array();
	$query = "SELECT * FROM tb_san_pham WHERE id_san_pham = $id_product";
	$result = mysqli_query($dbc, $query);
	extract(mysqli_fetch_array($result, MYSQLI_ASSOC));

	$array_product['id_san_pham'] = $id_product;
	$array_product['ma_san_pham'] = $ma_san_pham;
	$array_product['ten_san_pham'] = $ten_san_pham;
	$array_product['anh_san_pham'] = $anh_san_pham;
	$array_product['kich_co_san_pham'] = unserialize($kich_co_san_pham);
	$array_product['gia_khuyen_mai'] = number_format($gia_khuyen_mai, 0, ',', '.');
	$array_product['tong_gia'] = number_format($gia_khuyen_mai*1, 0, ',', '.');

	/* Lấy size đầu tiên làm mặc định */
	// $size_default = '';
	// foreach ($array_product['size_product'] as $key => $value) {
	// 	$size_default = $key;
	// 	break;
	// }
	/* Kết thúc lấy size đầu tiên làm mặc định */

	/* Tạo SESSION sản phẩm */
	$_SESSION['order'][$id_product] = $array_product;
	// $_SESSION['order'][$id_product]['quanlity'] = array($size_default => '1');
	/* Kết thúc tạo SESSION sản phẩm*/
	
	
	
	

	/*$array_product['code_product'] = $code_product;
	$array_product['code_product'] = $code_product;*/
	// echo "<pre>";
	// print_r($_SESSION['order']);
	// echo "</pre>";
	echo json_encode($array_product);
?>
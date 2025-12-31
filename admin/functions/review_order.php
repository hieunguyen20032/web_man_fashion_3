<?php
include('../inc/myconnect.php');
include('../inc/function.php');
if ( isset($_GET['id_order']) ) {
	/* id_order that ra là ma don hàng */
	$id_order = $_GET['id_order'];
	$query = "UPDATE tb_don_hang SET  
				trang_thai_don_hang = '1'
			WHERE ma_don_hang = {$id_order}";
	$result = mysqli_query($dbc, $query);
	// Tạo hóa đơn

	$code_bill = ramdom_code();
	$query_code_order = "SELECT id_don_hang FROM tb_don_hang WHERE ma_don_hang = {$id_order}";
	$result_code_order = mysqli_query($dbc, $query_code_order);
	while ( $rows = mysqli_fetch_array($result_code_order, MYSQLI_ASSOC) ) {
		$id_order = $rows['id_don_hang'];
		$query_bill = "INSERT INTO `tb_hoa_don`(`ma_hoa_don`, `trang_thai_hoa_don`, `id_don_hang`) VALUES ('{$code_bill}',0,'{$id_order}')";
		$result_bill = mysqli_query($dbc, $query_bill);
	}
	

	
	// tru so luong san pham khi khach hàng đặt sản pham do
	// $query = "SELECT tb_order.id_product id_product, tb_order.size_product size_order, tb_order.quantity_product quantity_order, tb_product.size_product array_size FROM tb_product, tb_order WHERE tb_product.id_product=tb_order.id_product && code_order=$code_order  GROUP BY tb_order.id_product ORDER BY tb_order.id_product ASC";
	// $result = mysqli_query($dbc, $query);

	// extract(mysqli_fetch_array($result, MYSQLI_ASSOC));
	// $array_size = unserialize($array_size);

	// $array_size[strtolower($size_order)] = $array_size[strtolower($size_order)] - $quantity_order;

	// $array_size = Serialize($array_size);

	header('location: ../list_order.php');

} 
else {
		header('location: ../list_order.php');
}
?>
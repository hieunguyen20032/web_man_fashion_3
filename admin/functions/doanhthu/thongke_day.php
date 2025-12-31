<?php include('../../inc/myconnect.php');?>
<?php include('../../inc/function.php');?>
<?php
	$array_date = $_GET['date'];
	$thongke = array();
	foreach ($array_date as  $value) {
		$date= date_create($value);
		$date =  date_format($date,"d");
		$query = "SELECT SUM(so_luong_san_pham*gia_khuyen_mai) tongtien FROM  tb_don_hang, tb_san_pham WHERE tb_don_hang.id_san_pham = tb_san_pham.id_san_pham && ngay_don_hang BETWEEN '".$value." 0:0:0' AND '".$value." 23:59:59'";
		$result = mysqli_query($dbc, $query);
		extract(mysqli_fetch_assoc($result));
		$thongke[] = array($date => $tongtien);	
	}
	
	echo json_encode($thongke);
	die;
	?>
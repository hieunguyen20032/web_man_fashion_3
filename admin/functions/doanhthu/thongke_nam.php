<?php include('../../inc/myconnect.php');?>
<?php include('../../inc/function.php');?>
<?php 
	$now = getdate(); // thoi gian hien tai
	$thongke = array();
	for ($i=1; $i <=12 ; $i++) { 
		$query = "SELECT SUM(so_luong_san_pham*gia_khuyen_mai) tongtien FROM  tb_don_hang, tb_san_pham WHERE tb_don_hang.id_san_pham = tb_san_pham.id_san_pham && ngay_don_hang BETWEEN '".$now["year"]."-".$i."-01 0:0:0' AND '".$now["year"]."-".$i."-31 23:59:59'";
		// echo $query;
		$result = mysqli_query($dbc, $query);
		extract(mysqli_fetch_assoc($result));
		$thongke['thang'.$i] = $tongtien;
	}
	echo json_encode($thongke);
	die;
	?>
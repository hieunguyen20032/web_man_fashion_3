<?php include('../../inc/myconnect.php');?>
<?php include('../../inc/function.php');?>
<?php
	$date = $_GET['date']; // tong so ngay cua thang truoc
	$now = getdate(); // thoi gian hien tai
	$month_ago = $now["mon"] -1;
	$year = $now["year"];
	if($month_ago == 0) {
		$month_ago =12;
		$year = $now["year"] - 1;
	}
	$thongke = array();
	for ($i=1; $i <= $date; $i++) { 
		$query = "SELECT SUM(so_luong_san_pham*gia_khuyen_mai) tongtien FROM  tb_don_hang, tb_san_pham WHERE tb_don_hang.id_san_pham = tb_san_pham.id_san_pham && ngay_don_hang BETWEEN '".$year."-".$month_ago."-".$i." 0:0:0' AND '".$year."-".$month_ago."-".$i." 23:59:59'";
		$result = mysqli_query($dbc, $query);
		extract(mysqli_fetch_assoc($result));
		$thongke['ngay'.$i] = $tongtien;
	}
	echo json_encode($thongke);
	die;
	?>
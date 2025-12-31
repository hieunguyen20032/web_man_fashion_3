<?php
	session_start();
	include('../inc/myconnect.php');
	include('../inc/function.php');
	
	if(isset($_SESSION['order']) or !empty($_SESSION['order']) && isset($_GET['name']) && isset($_GET['email'])  && isset($_GET['sdt']) && isset($_GET['sonha']) &&  isset($_GET['code_order']) && isset($_GET['quan_huyen']) ){
		$code_order = $_GET['code_order'];
    	/* Xoa don hang cu */
    	$query = "DELETE FROM tb_don_hang WHERE ma_don_hang={$code_order}";
    	$result = mysqli_query($dbc, $query);


		$name = $_GET['name'];
		$email = $_GET['email'];
		$sdt = $_GET['sdt'];
		$address_customer =  $_GET['sonha'];
		$id_district = $_GET['quan_huyen'];
		// date_default_timezone_set("Asia/HO_CHI_MINH");
		$order_day = $_GET['date'];
			foreach ($_SESSION['order'] as $value) {
				$id_product = $value['id_san_pham'];
				foreach ($value['quanlity'] as $key_sl => $value_sl) {
					print_r($value['quanlity']);
					$size_product =  $key_sl;
					$quantity_product = $value_sl;
					$query= "INSERT INTO tb_don_hang(
											ma_don_hang,
											trang_thai_don_hang,
											id_san_pham,
											kich_co_san_pham,
											so_luong_san_pham,
											ten_khach_hang, 
											dien_thoai_khach_hang,
											dia_chi_khach_hang,
											email_khach_hang,
											ngay_don_hang,
											id_quan_huyen
										) VALUES(
										'{$code_order}',
										'0',
										'{$id_product}', 
										'{$size_product}', 
										'{$quantity_product}', 
										'{$name}', 
										'{$sdt}', 
										'{$address_customer}', 
										'{$email}',
										'{$order_day}',
										'{$id_district}'
									)";
									echo $query;
				$result =  mysqli_query($dbc,$query);
				}
				

				// header('location:../gui-hang-thanh-cong.php');
			}	
			unset($_SESSION['order']);
		// header('location:../gui-hang-thanh-cong.php');
	}
?>
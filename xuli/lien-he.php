<?php
	include('../inc/myconnect.php');
	include('../inc/function.php');

		if(isset($_GET['name']) && isset($_GET['email']) &&  isset($_GET['sdt']) && isset($_GET['address']) && isset( $_GET['content'])){
			$name = $_GET['name'];
			$email = $_GET['email'];
			$sdt = $_GET['sdt'];
			$address = $_GET['address'];
			$content = $_GET['content'];

			$query ="INSERT INTO tb_lien_he(
											ho_ten,
											email,
											so_dien_thoai, 
											dia_chi,
											noi_dung,
											trang_thai
										) VALUES(
										'{$name}',
										'{$email}', 
										'{$sdt}', 
										'{$address}', 
										'{$content}',
										'0'
									)";
			$result =  mysqli_query($dbc,$query);
		}
?>
<?php
	session_start();
	include('../inc/myconnect.php');
	include('../inc/function.php');
	require_once __DIR__ . '/../vendor/autoload.php';
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
	
	if(isset($_SESSION['cart']) && !empty($_SESSION['cart']) && isset($_GET['name']) && isset($_GET['email']) && isset($_GET['sdt']) && isset($_GET['tinh']) && isset( $_GET['quan']) && isset($_GET['sonha']) && isset($_GET['phuong'])){
		$name = $_GET['name'];
		$email = $_GET['email'];
		$sdt = $_GET['sdt'];
		$tinh = $_GET['tinh'];
		$quan = $_GET['quan'];
		$sonha = $_GET['sonha'];
		$phuong = $_GET['phuong'];
		$payment_type = $_GET['payment_type'];
		$code_order = ramdom_code();
		$address_customer = $sonha . ", ". $phuong;

		$_SESSION['name'] = $_GET['name'];
		$_SESSION['email'] = $_GET['email'];
		$_SESSION['sdt'] = $_GET['sdt'];
		$_SESSION['tinh'] = $_GET['tinh'];
		$_SESSION['quan'] = $_GET['quan'];
		$_SESSION['sonha'] = $_GET['sonha'];
		$_SESSION['phuong'] = $_GET['phuong'];
		$_SESSION['payment_type'] = $_GET['payment_type'];
		$_SESSION['address_customer'] = $_SESSION['sonha'] . ", " . $_SESSION['phuong'];
		$mail = new PHPMailer(true);                           
		try {
			//Server settings                            
			$mail->isSMTP();                                     
			$mail->Host = 'smtp.gmail.com';                       
			$mail->SMTPAuth = true;                                
			$mail->Username = 'hn683292@gmail.com'; 
			$mail->Password = 'mwiyndlorzgqtrxc';                   
			$mail->SMTPSecure = 'tls';                             
			$mail->Port = 587;                                     
			$mail->CharSet = "UTF-8";
			$mail->SMTPOptions = array(
				'ssl' => array(
					'verify_peer' => false,
					'verify_peer_name' => false,
					'allow_self_signed' => true
				)
			);
			//Recipients
			$mail->setFrom('hn683292@gmail.com', 'Mail Liên hệ');
			// $mail->addAddress($khachhangRow['kh_email']);   
			$mail->addAddress($email);              

			$mail->isHTML(true);                            

			// Tiêu đề Mail
			$mail->Subject = "[Vui lòng xác nhận đơn hàng] - Mã đơn hàng $code_order";

			// Nội dung Mail
			// Lưu ý khi thiết kế Mẫu gởi mail
			// - Chỉ nên sử dụng TABLE, TR, TD, và các định dạng cơ bản của CSS để thiết kế
			// - Các đường link/hình ảnh có sử dụng trong mẫu thiết kế MAIL phải là đường dẫn WEB có thật, ví dụ như logo,banner,...
			$templateDonHang = '<ul>';
			$templateDonHang .= '<li>Họ tên khách hàng: ' . $name . '</li>';
			$templateDonHang .= '<li>Địa chỉ khách hàng: ' . $sonha . ', ' . $phuong . ', ' . $quan . ', ' . $tinh . '</li>';
			$templateDonHang .= '<ul>';

			$stt = 1;
			$templateChiTietDonHang = '<table border="1" width="100%">';
			$templateChiTietDonHang .= '<tr>';
			$templateChiTietDonHang .= '<td>STT</td>';
			$templateChiTietDonHang .= '<td>Sản phẩm</td>';
			$templateChiTietDonHang .= '<td>Số lượng</td>';
			$templateChiTietDonHang .= '<td>Giá</td>';
			$templateChiTietDonHang .= '<td>Thành tiền</td>';
			$templateChiTietDonHang .= '</tr>';

			if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
				foreach ($_SESSION['cart'] as $item) {
					// Get the total quantity for the current product
					$soluong_product = array_sum($item['quantity']);
					$gia = $item['gia_khuyen_mai'];
					$thanh_tien = $soluong_product * $gia;

					$templateChiTietDonHang .= '<tr>';
					$templateChiTietDonHang .= '<td>' . $stt . '</td>';
					$templateChiTietDonHang .= '<td>' . htmlspecialchars($item['ten_san_pham']) . '</td>';
					$templateChiTietDonHang .= '<td>' . $soluong_product . '</td>';
					$templateChiTietDonHang .= '<td>' . number_format($gia, 0, ',', '.') . '</td>';
					$templateChiTietDonHang .= '<td>' . number_format($thanh_tien, 0, ',', '.') . '</td>';
					$templateChiTietDonHang .= '</tr>';

					$stt++;
				}
			}

			$templateChiTietDonHang .= '</table>';

			$body = <<<EOT
				<table border="1" width="100%">
					<tr>
						<td colspan="2">
							<img src="http://localhost/Iphoneshop/assets/vendor/frontend/img/logo.png" style="width: 100px; height: 100px; border: 1px solid red;" />
						</td>
					</tr>
					<tr>
						<td>Có Đơn hàng vừa thanh toán</td>
						<td>
							<h2>Thông tin đơn hàng</h2>
							$templateDonHang

							<h2>Chi tiết đơn hàng</h2>
							$templateChiTietDonHang

							<p style="text-align: center; margin-top: 20px;">
								<a href="http://localhost/web_man_fashion_3/gui-hang-thanh-cong.php" style="display: inline-block; padding: 10px 20px; font-size: 16px; color: #fff; background-color: #28a745; text-decoration: none; border-radius: 5px;">Xác nhận đơn hàng</a>
							</p>
						</td>
					</tr>
				</table>
			EOT;
			$mail->Body = $body;
			$mail->send();
		} catch (Exception $e) {
			echo 'Lỗi khi gởi mail: ', $mail->ErrorInfo;	
		}	
	}
?>
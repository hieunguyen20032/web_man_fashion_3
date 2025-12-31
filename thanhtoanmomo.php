<?php
	session_start();
	include('inc/myconnect.php');
	include('inc/function.php');
    require_once __DIR__ . '../vendor/autoload.php';
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

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

    $order_day =date("Y-m-d  H:i:s");	
    
    foreach ($_SESSION['cart'] as $value) {
        $id_product = $value['id_san_pham'];
        foreach ($value['quantity'] as $key_sl => $value_sl) {
            $size_product = $key_sl;
            $quantity_product = $value_sl;
            $code_order = ramdom_code();

            $query = "INSERT INTO tb_don_hang (
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
                            id_quan_huyen,
                            hinh_thuc
                        ) VALUES (
                            '{$code_order}',
                            '1',
                            '{$id_product}', 
                            '{$size_product}', 
                            '{$quantity_product}', 
                            '{$_SESSION['name']}', 
                            '{$_SESSION['sdt']}', 
                            '{$_SESSION['address_customer']}', 
                            '{$_SESSION['email']}',
                            '{$order_day}',
                            '{$_SESSION['quan']}',
                            '{$_SESSION['payment_type']}'
                        )";

            $result = mysqli_query($dbc, $query);
        }
    }

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
								<a href="" style="display: inline-block; padding: 10px 20px; font-size: 16px; color: #fff; background-color: #28a745; text-decoration: none; border-radius: 5px;">Đặt đơn hàng thành công</a>
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

    initiateMoMoPayment();
    function execPostRequest($url, $data) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data))
        );
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    function initiateMoMoPayment() {
        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
        $partnerCode = 'MOMOBKUN20180529';
        $accessKey = 'klm05TvNBzhg7h7j';
        $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';
        $orderId = time() . "";
        $orderInfo = "Thanh toán qua MoMo";
        $redirectUrl = "http://localhost/web_man_fashion_3/thanhtoanthanhcong.php";
        $ipnUrl = "http://localhost/web_man_fashion_3/thanhtoanthanhcong.php";
        $extraData = "";
        $requestId = time() . "";
        $requestType = "payWithATM";
        $amount = $_SESSION['total_price'];
    
        $rawHash = "accessKey=$accessKey&amount=$amount&extraData=$extraData&ipnUrl=$ipnUrl&orderId=$orderId&orderInfo=$orderInfo&partnerCode=$partnerCode&redirectUrl=$redirectUrl&requestId=$requestId&requestType=$requestType";
        $signature = hash_hmac("sha256", $rawHash, $secretKey);
    
        $data = array(
            'partnerCode' => $partnerCode,
            'partnerName' => "Test",
            "storeId" => "MomoTestStore",
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => $requestType,
            'signature' => $signature
        );
    
        $result = execPostRequest($endpoint, json_encode($data));
        $jsonResult = json_decode($result, true);
        
        if (isset($jsonResult['payUrl'])) {
            header("Location: " . $jsonResult['payUrl']);
            exit();
        } else {
            echo "Payment initiation failed.";
        }
    }
?>
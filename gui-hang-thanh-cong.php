<?php
/**
 * Created by PhpStorm.
 * User: Minh Hieu
 * Date: 29/11/2023
 * Time: 21:22 PM
 */
?>

<!DOCTYPE html>
<html>
<head>
    <title>Quần áo nam đẹp, quần áo hàng hiệu, cao cấp kiểu 2023</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/style-main.css">
    <style type="text/css">
      #wrapper-giaohang{text-align: center;font-family: "Myriad Pro"; font-size:24px;margin-top: 20px;font-weight: bold;color: #444;margin-bottom: 25px}

      
    </style>


</head>
<body style="margin-top: -20px;overflow-x: hidden;">
<?php
session_start();
include('inc/myconnect.php');
include('inc/function.php');
include('include/header.php');
if (isset($_SESSION['cart'])) {
    $order_day =date("Y-m-d  H:i:s");	
    foreach ($_SESSION['cart'] as $value) {
        $id_product = $value['id_san_pham'];
        foreach ($value['quantity'] as $key_sl => $value_sl) {
            $size_product = $key_sl;
            $quantity_product = $value_sl;
            $code_order = ramdom_code();
            if(isset($_SESSION['id'])){
                $query = "INSERT INTO tb_don_hang (
                            ma_don_hang,
                            id_nguoi_dung,
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
                            '{$_SESSION['id']}',
                            '0',
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
            else{
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
                    '0',
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
    }

    // Unset only the session variables you want to clear
    unset($_SESSION['cart']);
    unset($_SESSION['name']);
    unset($_SESSION['sdt']);
    unset($_SESSION['address_customer']);
    unset($_SESSION['email']);
    unset($_SESSION['quan']);
    unset($_SESSION['payment_type']);

} else {
    echo "No order details found in the session.";
}
?>


<div class="container">
	<div class="row">
		<div class="col-xs-12">
			<div id="wrapper-giaohang">
				<div class="row-1"><i class="glyphicon glyphicon-ok" style="color: #48A4FF;font-size: 24px;margin-right: 5px"></i>Chúc mừng bạn đã gửi đơn hàng thành công. 
				</div>
				<div class="row-2">Cảm ơn bạn đã sử dụng dịch vụ của thương hiệu thời trang nam 3T</div>
				<a href="index.php" class="btn btn-primary" style="font-weight: bold;margin-top: 15px ">Tiếp tục tham gia mua hàng</a>
			</div>
		</div>	
	</div>
</div>


<?php
include('include/footer.php');
?>
</body>
<script src="js/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="js/jquery-main.js"></script>
<script type="text/javascript">

</script>
</html>



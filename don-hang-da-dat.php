<?php
include('inc/myconnect.php');
include('inc/function.php');
//error_reporting(0);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Quần áo nam đẹp, quần áo hàng hiệu, cao cấp kiểu 2023</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/style-main.css">
    <!--    <link rel="stylesheet" type="text/css" href="css/style.css">-->
    <link rel="stylesheet" type="text/css" href="css/slider.css">
    <link rel="stylesheet" type="text/css" href="css/style-body1.css">
</head>

<body>
    <?php
    // session_start();
    include('include/header.php');
    ?>

    <div class="banner hidden-sm hidden-xs ">   
        <div class="container-fluid" style="margin-bottom: 100px;margin-left:50px">
        <div class="row">
            <div class="col-12">
                <h2 style=" color: red">Danh sách đơn đặt hàng
                </h2>
                <table class="table table-striped"> 
                    <thead> 
                        <tr>
                            <th>Mã đơn hàng</th>
                            <th>Họ và tên</th>
                            <th>Số điện thoại</th>
                            <th>Địa chỉ</th>
                            <th>Ngày đặt hàng</th>
                            <th>Xem chi tiết</th>
                            <th>Trạng thái</th>
                            <th>Xóa</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $user_id = $_SESSION['id'];
                        $query = "
                            SELECT ma_don_hang, ten_khach_hang, dien_thoai_khach_hang, dia_chi_khach_hang, ngay_don_hang, id_san_pham, 
                                tb_quan_huyen.ten_quan_huyen, tb_thanh_pho.ten_thanh_pho,trang_thai_don_hang  
                            FROM tb_don_hang 
                            INNER JOIN tb_quan_huyen ON tb_quan_huyen.id_quan_huyen = tb_don_hang.id_quan_huyen
                            INNER JOIN tb_thanh_pho ON tb_quan_huyen.id_thanh_pho = tb_thanh_pho.id_thanh_pho
                            WHERE tb_don_hang.id_nguoi_dung = '$user_id' -- Lọc theo người dùng đăng nhập
                            GROUP BY ma_don_hang 
                            ORDER BY ngay_don_hang DESC";

                        $result = mysqli_query($dbc, $query);
                        kt_query($query, $result);
                        while ($order = mysqli_fetch_array($result, MYSQLI_NUM)) {
                        $check = check_order($order[0]);
                        ?>                    
                        <tr style="<?php echo  ($check) ? 'color: #bd0103' : "b";  ?>">
                            <td><?php echo $order[0]; ?></td>
                            <td><?php echo $order[1]; ?></td>
                            <td><?php echo $order[2]; ?></td>
                            <td><?php echo $order[3]. ", " . $order[6]. ", " . $order[7]; ?></td>
                            <td><?php $date=date_create($order[4]);
                            echo date_format($date,"H:i - d/m/Y"); ?></td>
                            <td>
                                <a href="order_detail.php?code_order=<?php echo $order[0]; ?>" class="btn btn-primary">
                                    Xem chi tiết
                                </a>
                            </td>
                            <td>
                                <?php
                                switch ($order[8]) {
                                    case 0:
                                        echo '<span class="badge bg-warning">Chưa duyệt</span>';
                                        break;
                                    case 1:
                                        echo '<span class="badge bg-info">Đã duyệt</span>';
                                        break;
                                    case 2:
                                        echo '<span class="badge bg-success">Đã giao</span>';
                                        break;
                                    default:
                                        echo '<span class="badge bg-secondary">Không xác định</span>';
                                        break;
                                }
                                ?>
                            </td>
                            <td>
                                <a href="delete_order.php?code_order=<?php echo $order[0]; ?>" 
                                class="btn btn-danger"
                                onClick="return confirm('Bạn thật sự muốn xóa không?');">
                                    <i class="fa fa-trash"></i> Xóa
                                </a>
                            </td>
                            </tr>
                            <?php
                    }
                    ?>
                </tbody>
            </table>
            </div>  
        </div>
    </div>
<?php
include('include/footer.php');
?>
<style>
    .badge{
        font-size: 15px;
    }
</style>
</body>
</html>

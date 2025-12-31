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

<div class="row">
  <div class="col-12" style="margin-left:100px">
    <h2 style=" color: red">Chi Tiết Đơn Đặt Hàng</h2>
    <div class="customer-infomation">

       <!--   <div class="titile">
             Thông tin khách hàng
           </div> -->
           <?php  
         // $code_order = $_GET['code_order'];
         // $query = "SELECT code_order,status_order,name_customer,phone_customer,address_customer,email_customer,order_day ";
         // $result = mysqli_query($dbc, $query); 
           ?>
           <div class="content">
            <div class="row">
              <div class="name"></div>
            </div>
          </div>
        </div>
        <table class="table table-striped"> 
          <thead> 
            <tr><th>STT</th>
              <th>Mã sản phẩm</th>
              <th>Tên Sản Phẩm</th>        
              <th style="padding-left: 55px">Số Lượng</th>
              <th>Giá</th>
              <th>Tình trạng</th>
            </tr>
          </thead>
          <tbody>
            <!--  -->
            <?php 
            $code_order = $_GET['code_order'];
            $query = "SELECT tb_don_hang.id_san_pham, tb_don_hang.kich_co_san_pham, tb_don_hang.so_luong_san_pham, tb_san_pham.ten_san_pham,tb_san_pham.gia_khuyen_mai, tb_san_pham.ma_san_pham FROM tb_san_pham, tb_don_hang WHERE tb_san_pham.id_san_pham=tb_don_hang.id_san_pham && ma_don_hang=$code_order  GROUP BY tb_don_hang.id_san_pham ORDER BY tb_don_hang.id_san_pham ASC";
            $result = mysqli_query($dbc, $query);
            $stt =1;
            $tongsotien = 0;
            while ($order = mysqli_fetch_array($result, MYSQLI_NUM)) {

              $check_product = check_product($order[0],$order[1] , $order[2]);
              ?>
              <tr style="<?php echo ($check_product) ? 'color: #bd0103' : '';?>">
                <th ><?php echo $stt; ?></th>
                <td><?php echo $order[5]; ?></td>
                <td> <?php echo $order[3]; ?></td>
                <td>
                  <?php 
                  $query_quantity =  "SELECT kich_co_san_pham,so_luong_san_pham FROM  tb_don_hang WHERE ma_don_hang=$code_order && id_san_pham = $order[0]";
                  $result_quantity = mysqli_query($dbc, $query_quantity);
                  $saleprice_product = $order[4];
                  $quantity_product =0;
                  while ($order_quantity = mysqli_fetch_array($result_quantity, MYSQLI_NUM)) {
                   $quantity_product += $order_quantity[1];
                   $tongsotien += ($quantity_product*$saleprice_product);
                   ?>                         
                   <div><?php echo "Size " . strtoupper($order_quantity[0]) . " : " . $order_quantity[1]; ?></div>
                   <?php 
                 } 
                 ?>
               </td>
               <td style="font-family: georgia"><?php echo number_format($quantity_product*$saleprice_product, 0, ',', '.') . " đ" ; ?></td>
               <td><?php echo ($check_product) ? 'Không đủ hàng' : 'Còn hàng';  ?></td>
             </tr>
             <?php 
             $stt++;
           } 
           ?>
           <tr>
            <th colspan="1">Tổng tiền :</th>
            <td colspan="3" style="font-family: georgia">
               <?php echo number_format($tongsotien, 0, ',', '.'). " đ"; ?>
             </td>
           </tr>
         </tbody>
       </table>
       <div class="col-xs-12 text-center">
        <a href="list_order.php" class="btn btn-primary">Quay Về Đơn Hàng</a>
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

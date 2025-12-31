
<?PHP 
include('includes/header.php');
include('inc/function.php');
?>
<?php 
$code_ship = $_GET['code_ship'];
$query = "SELECT ten_khach_hang,ma_hoa_don,dien_thoai_khach_hang,dia_chi_khach_hang,ma_don_hang,ma_giao_hang FROM tb_hoa_don a,tb_don_hang b,tb_giao_hang c WHERE a.id_don_hang = b.id_don_hang && a.id_hoa_don  = c.id_hoa_don  && c.ma_giao_hang = '{$code_ship}' GROUP BY c.ma_giao_hang";
$result = mysqli_query($dbc, $query);
extract(mysqli_fetch_assoc($result));

?>
<!-- Thong tin shop  -->
<?php 
$query_ifm ="SELECT ten, gia_tri FROM tb_thong_tin";
$result_ifm = mysqli_query($dbc, $query_ifm);
$array_ifm = array();
while ( $rows = mysqli_fetch_array( $result_ifm, MYSQLI_NUM ))  {
  $array_ifm[$rows[0]] = $rows[1];
}
extract($array_ifm);
?>
<div class="row wrap-print" style="position: relative;">
  <style type="text/css" media="print">
     <?php include('includes/style-detail_bill.php'); ?>   
</style>
  <div class="wrap-bill">
  

    <div class="col-xs-12">
      <div class="row header">
        <div class="col-xs-6 logo">
          <img src="../<?php
          if (isset($logo_header)) {
           echo $logo_header;
         }
         ?>" width="243px" height="70px">
       </div>
       <div class="col-xs-6 ifm-shop text-right">

        <div class="adress">Địa chỉ: <?php
        if (isset($adress)) {
         $array_adress =  explode("$%^$%^", $adress);
         echo $array_adress[0];
       }
       ?></div>
       <div class="phone">Mobile: <?php
       if (isset($phone)) {
         $array_adress =  explode(" ", $phone);
         for ($i=1; $i < count($array_adress); $i++) { 

           if($i == 2){
            echo ' - ' . $array_adress[$i];
            break;
          } else {
            echo $array_adress[$i];
          }
        }
      }
      ?></div>
      <div class="email">Email: <?php
      if (isset($email)) {
       $array_email =  explode(" ", $email);
       for ($i=1; $i < count($array_adress); $i++) { 

         if($i == 2){
          /* cho hien thi 1 email */
           break;
          // echo ' - ' . $array_email[$i];
         
        } else {
          echo $array_email[$i];
        }
      }
    }
    ?></div>
  </div>
</div>
<!-- End thong tin shop  -->
<div class="fm-custommer">
  <div class="title text-center">HÓA ĐƠN BÁN HÀNG <span class="code_bil">Mã hóa đơn: <?php echo $ma_hoa_don; ?></span><span class="code_bil">Mã ship: <?php echo $ma_giao_hang; ?></span></div>
  <div class="name col-xs-12">
    <span class="wrap-title">Tên khách hàng:</span><span class="value"><?php echo $ten_khach_hang; ?></span>
    <hr style="margin-left: 130px;">
  </div>
  <div class="adress-custommmer col-xs-7"> 
    <span class="wrap-title">Địa chỉ :</span><span class="value"><?php echo $dia_chi_khach_hang; ?></span>
    <hr style="margin-left: 67px;">
  </div>
  <div class="phone-custommer col-xs-5">
   <span class="wrap-title">Điện thoại:</span><span class="value"><?php echo $dien_thoai_khach_hang; ?></span>
   <hr style="margin-left: 90px;">
 </div>
</div>
<table class="table table-bordered ifm-product"> 
 <thead> 
  <tr> 
    <th>STT</th>
    <!-- <th>Mã hàng</th> -->
    <th>Tên hàng</th>
    <th>Size</th>
    <th>Số lượng</th>
    <th>Đơn giá</th>
    <th>Thành tiền</th> 
  </tr>
</thead>
<tbody>
  <?php 
  $stt = 0;
  $query_product = "SELECT  ma_san_pham, ten_san_pham, gia_khuyen_mai, b.so_luong_san_pham, b.kich_co_san_pham FROM tb_hoa_don a,tb_don_hang b, tb_san_pham c WHERE a.id_don_hang = b.id_don_hang && b.id_san_pham = c.id_san_pham && a.ma_hoa_don = $ma_hoa_don";
  $result_product = mysqli_query($dbc, $query_product);
  while ($rows = mysqli_fetch_assoc($result_product) ) {
    $stt++;
    ?>
    <tr> 
      <th scope="row"><?php echo $stt; ?></th>
      <!-- <td><?php echo $rows['ma_san_pham']; ?></td> -->
      <td><?php echo $rows['ten_san_pham']; ?></td>
      <td><?php echo $rows['kich_co_san_pham']; ?></td>
      <td><?php echo $rows['so_luong_san_pham']; ?></td>
      <td><?php echo number_format($rows['gia_khuyen_mai'], 0, ',', '.'); ?></td>
      <td><?php echo number_format($rows['so_luong_san_pham']*$rows['gia_khuyen_mai'], 0, ',', '.') . ' VND'; ?></td>
    </tr>
    <?php
  }
  for($i = $stt; $i <= 7; $i++) {
    ?>


    <tr> 
      <th scope="row">&nbsp;</th>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <tr>
        <?php
      }
      ?>
      <tr>
        <td colspan="6">
          Tổng Cộng: <?php 
            $sum_money = 0;
            $query_product = "SELECT  ma_san_pham, ten_san_pham, gia_khuyen_mai, b.so_luong_san_pham, b.kich_co_san_pham FROM tb_hoa_don a,tb_don_hang b, tb_san_pham c WHERE a.id_don_hang = b.id_don_hang && b.id_san_pham = c.id_san_pham && a.ma_hoa_don = $ma_hoa_don";
            $result_product = mysqli_query($dbc, $query_product);
             while ($rows = mysqli_fetch_assoc($result_product) ) {
               $sum_money += $rows['so_luong_san_pham']*$rows['gia_khuyen_mai'];
              } 
              echo number_format( $sum_money , 0, ',', '.') . ' VND';
          ?>
        </td>
      </tr>
      <tr>
        <td colspan="6">
          Bằng chữ: <?php  echo ''.convert_number_to_words( $sum_money).''; ?>
        </td>
      </tr>
    </tbody>
  </table>

</div>
  <!-- <div class="row bottom">
    <div class="col-xs-6 left text-center">
      <div class="title-left">
        Người mua hàng
        <div class="text-title-left">
          (Ký và ghi rõ họ tên)
        </div>
      </div>
    </div>
    <div class="col-xs-6 right text-center">
      <div class="date">
        <?php 
        date_default_timezone_set("Asia/HO_CHI_MINH");
        ?>
        <span class="title">Ngày</span>
        <span class="value"><?php echo date("d"); ?></span>
        <span class="title">Tháng</span>
        <span class="value"><?php echo date("m"); ?></span>
        <span class="title">Năm</span>
        <span class="value"><?php echo date("Y"); ?></span>
      </div>
      <div class="title-right">
        Người bán hàng
        <div class="text-title-right">
          (Ký và ghi rõ họ tên)
        </div>
      </div>
    </div>
  </div> -->
</div> 

</div>
<div class="print"><i class="glyphicon glyphicon-print"></i></div> 


<?PHP 
include('includes/footer.php');
?>

<script type="text/javascript">
  $('.print').click(function(){ 
    var generator = window.open("in");
    generator.document.write($('.wrap-print').html());
    generator.print();
    generator.close();
  })
</script>
<script type="text/javascript">
    $('.kinh-doanh .collapse').addClass('in');
    $('.kinh-doanh .hoadon').css({'background-color': '#e1e1e1'});
</script>
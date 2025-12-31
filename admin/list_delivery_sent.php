<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 9/15/2017
 * Time: 4:40 PM
 */
?>
<?PHP 
    include('includes/header.php');
    include('inc/function.php');
?>
    <div class="row">
        <div class="col-xs-12">
            <h3 style=" color: red;">Danh sách giao hàng đã gửi</h3> 
            <table class="table table-striped"> 
                <thead> 
                    <tr>
                        <th>Mã hóa đơn</th>
                        <th>Mã ship</th>
                        <th>Họ và tên</th>
                        <th>Số điện thoại</th>
                        <th>Địa chỉ</th>
                        <th>Ngày đặt hàng</th>
                        <th class="text-center">Xem chi tiết</th>
                        <th class="text-center">Đã nhận</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $query = "SELECT ma_giao_hang, ten_khach_hang, dien_thoai_khach_hang,dia_chi_khach_hang,ngay_don_hang,ma_hoa_don,trang_thai_giao_hang, tb_quan_huyen.ten_quan_huyen , tb_thanh_pho.ten_thanh_pho FROM tb_don_hang,tb_hoa_don,tb_giao_hang, tb_quan_huyen, tb_thanh_pho WHERE tb_quan_huyen.id_thanh_pho = tb_thanh_pho.id_thanh_pho && tb_quan_huyen.id_quan_huyen = tb_don_hang.id_quan_huyen && tb_don_hang.id_don_hang = tb_hoa_don.id_don_hang && tb_hoa_don.id_hoa_don = tb_giao_hang.id_hoa_don &&  trang_thai_giao_hang = '1'  GROUP BY ma_giao_hang";
                        $result = mysqli_query($dbc,$query);
                        kt_query($query, $result);
                        while ($order = mysqli_fetch_array($result, MYSQLI_NUM)) {
                        ?>                    
                    <tr>
                         <td><?php echo $order[5]; ?></td>
                        <td><?php echo $order[0]; ?></td>
                        <td><?php echo $order[1]; ?></td>
                        <td><?php echo $order[2]; ?></td>
                        <td><?php echo $order[3]. ", " . $order[6]. ", " . $order[7]; ?></td>
                        <td><?php $date=date_create($order[4]);
                            echo date_format($date,"H:i - d/m/Y"); ?></td>
                        <td class="text-center"><a href="delivery_detail.php?code_ship=<?php echo $order[0]; ?>"><i class="fa fa-eye" aria-hidden="true"></i></a></td>
                        <td class="text-center"><a onClick="return confirm('Hóa đơn đã được gửi');" href="functions/review_ship_sent.php?code_ship=<?php echo $order[0]; ?>"><i class="glyphicon glyphicon-ok"></i></a></td>
                    </tr>
                    <?php
                        }
                    ?>
                </tbody>
            </table>
    </div>  



<?PHP 
    include('includes/footer.php');
?>

<script language="JavaScript">
    function chkallClick(o) {
        var form = document.frmForm;
        for (var i = 0; i < form.elements.length; i++) {
            if (form.elements[i].type == "checkbox" && form.elements[i].name!="chkall") {
                form.elements[i].checked = document.frmForm.chkall.checked;
            }
        }
    }
</script>
<script type="text/javascript">
    $('.giao-hang .collapse').addClass('in');
    $('.giao-hang .giaohang').css({'background-color': '#e1e1e1'});
</script>
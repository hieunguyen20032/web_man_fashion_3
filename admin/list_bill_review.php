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
        <div class="col-12">
            <h3 style=" color: red">Danh sách hóa đơn đã duyệt</h3>
            <table class="table table-striped"> 
                <thead> 
                    <tr>
                        <th>Mã hóa đơn</th>
                        <th>Họ và tên</th>
                        <th>Số điện thoại</th>
                        <th>Địa chỉ</th>
                        <th>Ngày đặt hàng</th>
                        <th class="text-center">Xem chi tiết</th>
                        <!-- <th>Duyệt</th> -->
                    </tr>
                </thead>
                <tbody>
                    <?php 
                         $query = "SELECT ma_hoa_don, ten_khach_hang, dien_thoai_khach_hang,dia_chi_khach_hang, ngay_don_hang, id_san_pham, tb_hoa_don.trang_thai_hoa_don , tb_quan_huyen.ten_quan_huyen , tb_thanh_pho.ten_thanh_pho  FROM tb_don_hang,tb_hoa_don, tb_quan_huyen, tb_thanh_pho WHERE tb_quan_huyen.id_thanh_pho = tb_thanh_pho.id_thanh_pho && tb_quan_huyen.id_quan_huyen = tb_don_hang.id_quan_huyen && tb_hoa_don.id_don_hang  = tb_don_hang.id_don_hang && tb_hoa_don.trang_thai_hoa_don  = '1'  GROUP BY ma_hoa_don ORDER BY trang_thai_hoa_don";
                         $result = mysqli_query($dbc,$query);
                        $result = mysqli_query($dbc,$query);
                        kt_query($query, $result);

                        while ($bill = mysqli_fetch_array($result, MYSQLI_NUM)) {              
                        ?>                    
                    <tr >
                        <td><?php echo $bill[0]; ?></td>
                        <td><?php echo $bill[1]; ?></td>
                        <td><?php echo $bill[2]; ?></td>
                        <td><?php echo $bill[3]. ", " . $bill[7]. ", " . $bill[8]; ?></td>
                        <td><?php $date=date_create($bill[4]);
                            echo date_format($date,"H:i - d/m/Y"); ?></td>
                        <td class="text-center"><a href="bill_detail.php?code_bill=<?php echo $bill[0]; ?>"><i class="fa fa-eye" aria-hidden="true"></i></a></td>
                        <?php 
                            if ( $bill[6] == "0" ) {
                         ?>

                      <!--  <td class="text-center"><a onClick="return confirm('Bạn muốn chuyển đơn hàng này qua bên giao hàng ?');" href="functions/review_bill.php?code_bill=<?php echo $bill[0]; ?>"><i class="glyphicon glyphicon-ok"></i></a></td> -->
                        <?php 
                            }
                        ?>
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
    $('.kinh-doanh .collapse').addClass('in');
    $('.kinh-doanh .hoadon').css({'background-color': '#e1e1e1'});
</script>
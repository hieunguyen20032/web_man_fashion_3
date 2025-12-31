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
        <h2 style=" color: red">Danh sách đơn đặt hàng đã duyệt
        </h2>
        <table class="table table-striped"> 
            <thead> 
                <tr>
                    <th>Mã đơn hàng</th>
                    <th>Họ và tên</th>
                    <th>Số điện thoại</th>
                    <th>Địa chỉ</th>
                    <th>Ngày đặt hàng</th>
                    <th class="text-center">Xem chi tiết</th>
                 <!--    <th>Chỉnh sửa</th>
                    <th>Duyệt</th> -->
                </tr>
            </thead>
            <tbody>
                <?php 
                 $query = "SELECT ma_don_hang ,ten_khach_hang, dien_thoai_khach_hang,dia_chi_khach_hang,ngay_don_hang,id_san_pham, tb_quan_huyen.ten_quan_huyen , tb_thanh_pho.ten_thanh_pho  FROM tb_don_hang, tb_quan_huyen, tb_thanh_pho WHERE tb_quan_huyen.id_thanh_pho = tb_thanh_pho.id_thanh_pho && tb_quan_huyen.id_quan_huyen = tb_don_hang.id_quan_huyen && trang_thai_don_hang = '1'  GROUP BY ma_don_hang ORDER BY ngay_don_hang DESC";
                $result = mysqli_query($dbc,$query);
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
                    <td class="text-center"><a href="order_detail.php?code_order=<?php echo $order[0]; ?>"><i class="fa fa-eye" aria-hidden="true"></i></a></td>
                    <!-- <td><a href="edit_order.php?code_order=<?php echo $order[0]; ?>"><i class="fa fa-fw fa-pencil" style="font-size: 20px; color:#1b926c;"></i></a></td> -->
                    <?php if($check){
                        ?>
                        <!-- <td style="color: #bd0103;text-align: center;"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i></td> -->
                        <?php 
                    } else {

                        ?>
                        <!-- <td class="text-center"><a onClick="return confirm('Bạn muốn chuyển đơn hàng này qua bên hóa đơn?');" href="functions/review_order.php?id_order=<?php echo $order[0]; ?>"><i class="glyphicon glyphicon-ok"></i></a></td> -->
                    </tr>
                    <?php
                }
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
    $('.kinh-doanh .dathang').css({'background-color': '#e1e1e1'});
</script>
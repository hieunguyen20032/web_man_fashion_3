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
        <h2 style="color: red">
            Danh sách liên hệ
        </h2>
        <table class="table table-striped"> 
            <thead> 
                <tr>
                    <th>STT</th>
                    <th >Họ và tên</th>
                    <th>Số điện thoại</th>
                    <th>Email</th>
                    <th >Địa chỉ</th>
                    <!-- <th style="color: #bd0103;">Nội dung</th> -->
                    <th>Trạng thái</th>
                    <th>Xem</th>              
                    <th>Xóa</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $query = "SELECT * FROM tb_lien_he ORDER BY trang_thai";
                $result = mysqli_query($dbc,$query);
                kt_query($query, $result);
                $stt = 0;
                while ($rows = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                    $stt++;
                    ?>                    
                    <tr>
                        <td><?php echo $stt ?></td>
                        <td><?php echo $rows['ho_ten']; ?></td>
                        <td><?php echo $rows['so_dien_thoai']; ?></td>
                        <td><?php echo $rows['email']; ?></td>
                        <td><?php echo $rows['dia_chi']; ?></td>
                        <!--                       <td><?php echo $rows['noi_dung']; ?></td> -->
                        <td style="font-weight: 700;"><?php echo $rows['trang_thai'] == 1 ? 'Đã xem' : 'Chưa xem' ?></td>
                        <td><a href="contact_detail.php?id=<?php echo $rows['id_lien_he']; ?>"><i class="fa fa-eye" aria-hidden="true"></i></a></td>
                        
                        <td class=""><a onClick="return confirm('Bạn thật sự muốn xóa không ?');" href="delete_contact.php?id=<?php echo $rows['id_lien_he']; ?>"><i class="fa fa-fw fa-trash"></i></a></td>

                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>  
</div>


<?PHP 
include('includes/footer.php');
?>
<script type="text/javascript">
   $('.lien-he .collapse').addClass('in');
    $('.lien-he .thongtin').addClass('active-hover');
</script>

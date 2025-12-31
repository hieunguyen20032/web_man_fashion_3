<?php 
include('includes/header.php');
include('inc/myconnect.php');
include('inc/function.php');

if(isset($_GET['id']) && filter_var($_GET['id'],FILTER_VALIDATE_INT,array('min_range'=>1))){
	$id = $_GET['id'];
	$query_ud="UPDATE tb_lien_he SET trang_thai='1' WHERE id_lien_he='{$id}'";
	$result_ud=mysqli_query($dbc,$query_ud);
	kt_query($query_ud,$result_ud);

	$query = "SELECT * FROM tb_lien_he WHERE id_lien_he='{$id}'";
	$result = mysqli_query($dbc, $query);
	kt_query($query,$result);
	extract(mysqli_fetch_array($result, MYSQLI_ASSOC));
	?>

	<div class="row" style="height: 100%">
		<div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">.
			<h3 style="color: red">Chi tiết liên hệ</h3>
			
			<div class="row">
				<div class="name col-xs-4"><label>Họ và tên: </label> <?php echo $ho_ten; ?></div>
				<div class="phone col-xs-4"><label>Số diện thoại: </label> <?php echo $so_dien_thoai; ?></div>
			</div>
			<div class="row">
				<div class="email col-xs-4"><label>Email </label> <?php echo $email; ?></div>
				<div class="address col-xs-6"><label>Địa chỉ: </label> <?php echo $dia_chi; ?></div>
			</div>
			<div class="row"  style="padding:30px 0 0 15px">
				<label class="col-xs-12">Nội dung liên hệ: </label>
				<div class="col-xs-12"><?php echo $noi_dung; ?></div>
			</div>
			<div class="" style="padding-top: 25px;">
				<a href="list_contact.php"><div class="btn btn-primary">Quay về</div></a>
			</div>
		</div>

	</div>
	<?php
	include('includes/footer.php');
}
else{
	header("Location: list_contact.php");
	exit();		
}
?>
<script type="text/javascript">
    $('.danh-muc .collapse').addClass('in');
    $('.danh-muc .lienhe').css({'background-color': '#e1e1e1'});
</script>
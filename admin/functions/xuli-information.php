<?php include('../inc/myconnect.php');?>
<?php include('../inc/function.php');?>
<?php

	switch ($_POST['field']) {
		case 'name':
			$value = $_POST['value'];
			$query_kt = "SELECT * FROM tb_thong_tin WHERE ten='name'";
			$result_kt = mysqli_query($dbc, $query_kt);
			if (mysqli_num_rows($result_kt) > 0) {
				$query_kt = "UPDATE tb_thong_tin SET  
				gia_tri = '{$value}'
				WHERE ten='name'";
				$result_kt = mysqli_query($dbc, $query_kt);
				echo 1;
			} else {
				$query_kt = "INSERT INTO  tb_thong_tin(ten,gia_tri)
				VALUES('ten','{$value}')";
				$result_kt = mysqli_query($dbc, $query_kt);
				echo 1;
			}
			break;
		case 'description':
			$value = $_POST['value'];
			$query_kt = "SELECT * FROM tb_thong_tin WHERE ten='description'";
			$result_kt = mysqli_query($dbc, $query_kt);
			if (mysqli_num_rows($result_kt) > 0) {
				$query_kt = "UPDATE tb_thong_tin SET  
				gia_tri = '{$value}'
				WHERE ten='description'";
				$result_kt = mysqli_query($dbc, $query_kt);
				echo 1;
			} else {
				$query_kt = "INSERT INTO  tb_thong_tin(ten,gia_tri)
				VALUES('description','{$value}')";
				$result_kt = mysqli_query($dbc, $query_kt);
				echo 1;
			}
			break;
		case 'fb':
			$value = $_POST['value'];
			$query_kt = "SELECT * FROM tb_thong_tin WHERE ten='fb'";
			$result_kt = mysqli_query($dbc, $query_kt);
			if (mysqli_num_rows($result_kt) > 0) {
				$query_kt = "UPDATE tb_thong_tin SET  
				gia_tri = '{$value}'
				WHERE ten='fb'";
				$result_kt = mysqli_query($dbc, $query_kt);
				echo 1;
			} else {
				$query_kt = "INSERT INTO  tb_thong_tin(name,value)
				VALUES('fb','{$value}')";
				$result_kt = mysqli_query($dbc, $query_kt);
				echo 1;
			}
			break;	
		case 'logo-header':
			$img = str_replace(" ","",$_FILES['file']['name']);
            $link_img = 'image/' . $img;
            move_uploaded_file($_FILES['file']['tmp_name'], "../../image/" . $img);
            // Lưu vào csdl
            $query_kt = "SELECT * FROM tb_thong_tin WHERE ten='logo_header'";
            echo $query_kt;
			$result_kt = mysqli_query($dbc, $query_kt);
			if (mysqli_num_rows($result_kt) > 0) {
				$query_kt = "UPDATE tb_thong_tin SET  
				gia_tri = '{$link_img}'
				WHERE ten='logo_header'";
				$result_kt = mysqli_query($dbc, $query_kt);
				echo 1;
			} else {
				$query_kt = "INSERT INTO  tb_information(name,value)
				VALUES('logo_header','{$link_img}')";
				$result_kt = mysqli_query($dbc, $query_kt);
				echo 1;
			}
           	
			break;	
		case 'logo-footer':
			$img = str_replace(" ","",$_FILES['file']['name']);
            $link_img = 'image/' . $img;
            move_uploaded_file($_FILES['file']['tmp_name'], "../../image/" . $img);
            // Lưu vào csdl
            $query_kt = "SELECT * FROM tb_thong_tin WHERE ten='logo_footer'";
            echo $query_kt;
			$result_kt = mysqli_query($dbc, $query_kt);
			if (mysqli_num_rows($result_kt) > 0) {
				$query_kt = "UPDATE tb_thong_tin SET  
				gia_tri = '{$link_img}'
				WHERE ten='logo_footer'";
				$result_kt = mysqli_query($dbc, $query_kt);
				echo 1;
			} else {
				$query_kt = "INSERT INTO  tb_thong_tin(ten,gia_tri)
				VALUES('logo_footer','{$link_img}')";
				$result_kt = mysqli_query($dbc, $query_kt);
				echo 1;
			}
           	
			break;	
		case 'phone':
			$value = $_POST['value'];
			$query_kt = "SELECT * FROM tb_thong_tin WHERE ten='phone'";
			$result_kt = mysqli_query($dbc, $query_kt);
			if (mysqli_num_rows($result_kt) > 0) {
				$query_kt = "UPDATE tb_thong_tin SET  
				gia_tri = '{$value}'
				WHERE ten='phone'";
				$result_kt = mysqli_query($dbc, $query_kt);
				echo 1;
			} else {
				$query_kt = "INSERT INTO  tb_thong_tin(ten,gia_tri)
				VALUES('phone','{$value}')";
				$result_kt = mysqli_query($dbc, $query_kt);
				echo 1;
			}
			break;
           	
		case 'email':
			$value = $_POST['value'];
			$query_kt = "SELECT * FROM tb_thong_tin WHERE ten='email'";
			$result_kt = mysqli_query($dbc, $query_kt);
			if (mysqli_num_rows($result_kt) > 0) {
				$query_kt = "UPDATE tb_thong_tin SET  
				gia_tri = '{$value}'
				WHERE ten='email'";
				$result_kt = mysqli_query($dbc, $query_kt);
				echo 1;
			} else {
				$query_kt = "INSERT INTO  tb_thong_tin(ten,gia_tri)
				VALUES('email','{$value}')";
				$result_kt = mysqli_query($dbc, $query_kt);
				echo 1;
			}
			break;
        case 'adress':
			$value = $_POST['value'];
			$query_kt = "SELECT * FROM tb_thong_tin WHERE ten='adress'";
			$result_kt = mysqli_query($dbc, $query_kt);
			if (mysqli_num_rows($result_kt) > 0) {
				$query_kt = "UPDATE tb_thong_tin SET  
				gia_tri = '{$value}'
				WHERE ten='adress'";
				$result_kt = mysqli_query($dbc, $query_kt);
				echo 1;
			} else {
				$query_kt = "INSERT INTO  tb_thong_tin(ten,gia_tri)
				VALUES('adress','{$value}')";
				$result_kt = mysqli_query($dbc, $query_kt);
				echo 1;
			}
			break;   	
		default:
			# code...
			break;
	}
?>

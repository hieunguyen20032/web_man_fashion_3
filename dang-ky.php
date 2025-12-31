<?php
include('inc/myconnect.php');
include('inc/function.php');
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $address = $_POST['address'];
    $password =  md5($_POST['password']);
    $check_email = "SELECT * FROM tb_nguoi_dung WHERE email_nguoi_dung = '$email'";
    $rs_check_email = $dbc ->query($check_email);
    $count = mysqli_num_rows($rs_check_email);
    if($count >= 1){
      echo("<script>alert('Email đã tồn tại');</script>" );
    }
    else{
      $query = "INSERT INTO tb_nguoi_dung (ten_nguoi_dung,email_nguoi_dung,so_dien_thoai_nguoi_dung,dia_chi_nguoi_dung,mat_khau_nguoi_dung,loai_nguoi_dung) VALUE (('$fullname'),('$email'),('$phone_number'),('$address'),('$password'),'2' )";
      $result = $dbc ->query($query);
      if($result){
          echo("<script>alert('Đăng Ký tài khoản thành công');</script>" );
      }
      else echo("<script>alert('Đăng Ký thất bại');</script>");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Đăng ký</title>
</head>
<body>
    <form action="" method="POST">
        <section class="vh-100 bg-image" style="background-image: url('https://mdbcdn.b-cdn.net/img/Photos/new-templates/search-box/img4.webp');">
            <div class="mask d-flex align-items-center h-100 gradient-custom-3">
              <div class="container h-100">
                <div class="row d-flex justify-content-center align-items-center h-100">
                  <div class="col-12 col-md-9 col-lg-7 col-xl-6">
                    <div class="card" style="border-radius: 15px;">
                      <div class="card-body p-5">
                        <h2 class="text-uppercase text-center mb-5">ĐĂNG KÝ TÀI KHOẢN</h2>
          
                        <form>
          
                          <div class="form-outline mb-4">
                            <input type="text"  class="form-control form-control-lg"  placeholder="Nhập Họ và Tên" required style="font-size: 17px;" name="fullname"/>
                            
                          </div>
          
                          <div class="form-outline mb-4">
                            <input type="email"  class="form-control form-control-lg" placeholder="Nhập email" style="font-size: 17px;" required name="email"/>
                            
                          </div>
          
                          <div class="form-outline mb-4">
                            <input type="text" class="form-control form-control-lg" placeholder="Nhập Số Điện Thoại" style="font-size: 17px;" required name="phone_number"/>
                          </div>
          
                          <div class="form-outline mb-4">
                            <input type="text"  class="form-control form-control-lg" placeholder="Nhập Địa Chỉ" style="font-size: 17px;" required name="address"/>
                          </div>
                          <div class="form-outline mb-4">
                            <input type="password"  class="form-control form-control-lg" placeholder="Nhập Mật Khẩu" style="font-size: 17px;" required name="password"/>
                          </div>
          
          
                          <div class="form-check d-flex justify-content-center mb-5">
                            <input
                              class="form-check-input me-2"
                              type="checkbox"
                              value=""
                              id="form2Example3cg" required
                            />
                            <label class="form-check-label" for="form2Example3g">
                              Tôi đồng ý với điều khoản của <a href="#!" class="text-body"><u>Dịch vụ</u></a>
                            </label>
                          </div>
          
                          <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-success btn-block btn-lg gradient-custom-4 text-white">Đăng Ký</button>
                          </div>
          
                          <p class="text-center text-muted mt-5 mb-0">Bạn đã có tài khoản? <a href="dang-nhap.php" class="fw-bold text-infor" style="text-decoration: none;">Đăng Nhập tại đây</a></p>
          
                        </form>
          
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </section>
    </form>
</body>
</html>
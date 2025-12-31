<?php
include('inc/myconnect.php');
include('inc/function.php');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$email = $password = "";
if($_SERVER['REQUEST_METHOD']==='POST'){
    $email = $_POST['email'];
    $password =md5($_POST['password']);
    $sql = "SELECT * FROM tb_nguoi_dung WHERE email_nguoi_dung = '$email' and mat_khau_nguoi_dung = '$password' and loai_nguoi_dung='2'";
    $sql = $dbc->query($sql);
    $count = mysqli_num_rows($sql);
    if($count > 0){    
        $row = mysqli_fetch_array($sql);
        $_SESSION['id'] = $row['id_nguoi_dung'];
        $_SESSION['fullname'] = $row['ten_nguoi_dung'];
        header('Location: index.php');
        exit();
    }
    else{
        echo("<script>alert('Vui lòng nhập đúng tài khoản!!');</script>" );
      }
    
    
}
?>
<!DOCTYPE html>
<html>
   <head>
      <meta charset="utf-8">
      <title>Đăng Nhập</title>
      <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">
   </head>
   <body>
       <form action="dang-nhap.php" method="POST">
       <section class="vh-100">
           <div class="container py-5 h-100">
             <div class="row d-flex justify-content-center align-items-center h-100">
               <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                 <div class="card shadow-2-strong" style="border-radius: 1rem;">
                   <div class="card-body p-5 text-center">
         
                     <h3 class="mb-5">ĐĂNG NHẬP</h3>
         
                     <div class="form-outline mb-4">
                       <input type="email" id="typeEmailX-2" class="form-control form-control-lg" placeholder="email" style="font-size: 17px;" required name="email"/>
                      
                     </div>
         
                     <div class="form-outline mb-4">
                       <input type="password" id="typePasswordX-2" class="form-control form-control-lg"  placeholder="password" style="font-size: 17px;" required name="password"/>
                       
                     </div>
         
                     <!-- Checkbox -->
                     <div class="form-check d-flex justify-content-start mb-4">
                       <input class="form-check-input" type="checkbox" value="" id="form1Example3" />
                       <label class="form-check-label" for="form1Example3"> Nhớ mật khẩu </label>
                     </div>
         
                     <button class="btn btn-primary btn-lg btn-block" type="submit">Đăng Nhập</button>
         
                     <hr class="my-4">
         
                     <button class="btn btn-lg btn-block btn-primary" style="background-color: #dd4b39;"
                       type="submit"><i class="fab fa-google me-2"></i> Sign in with google</button>
                     <button class="btn btn-lg btn-block btn-primary mb-2" style="background-color: #3b5998;"
                       type="submit"><i class="fab fa-facebook-f me-2"></i>Sign in with facebook</button>
         
                   </div>
                 </div>
               </div>
             </div>
           </div>
         </section>
       </form>
   </body>
</html>
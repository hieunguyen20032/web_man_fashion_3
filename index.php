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

    <div class="banner hidden-sm hidden-xs ">
        <div class="container-fluid">
            <div class="row">
                <div class="hidden-sm hidden-xs wap">
                    <div class="slider">
                  <!--   <div id="pre"><img src="pre-icon.png"></div>
                    <div id="next"><img src="next-icon.png"></div> -->
                    <ul id="img">
                     <?php
                     $query_slider = 'SELECT gia_tri FROM tb_thong_tin WHERE ten = "slider"';
                     $result_slider = mysqli_query($dbc, $query_slider);
                     extract( mysqli_fetch_array($result_slider, MYSQLI_ASSOC) );
                     $array_slider = explode(' ', $gia_tri);
                     $stt = 0;
                     $total_slider = count($array_slider);
                     foreach ($array_slider as  $gia_tri) {
                        ?>
                        <li stt="<?php echo $stt; ?>"><img src="<?php echo $gia_tri; ?>" style="height: 500px; width: 100%"></li>
                        <?php
                        $stt++;
                    }
                    ?>
                </ul>
                <ul id="icon">
                    <?php for ($i=0; $i < count($array_slider); $i++) { 
                       ?>

                       <li class='<?php if( $i == 0 ) echo "active" ?>' stt="<?php echo $i ?>"></li>
                       <?php 
                   }
                   ?> 
               </ul>

           </div>
       </div>
   </div>
</div>
</div>
<?php 
  $query = "SELECT * FROM tb_thong_tin";
  $result = mysqli_query($dbc, $query);

  $array_info = array();
  while( $rows = mysqli_fetch_array($result, MYSQLI_ASSOC)){
      $array_info[$rows['ten']] =  $rows['gia_tri']; 
  }
  extract($array_info);
?>
<div id="wapper-body" style="padding-top: 30px;">
    <div id="gioi-thieu" class=" hidden-xs">
        <div class="container">
            <div class="row">
                <div class="column-left col-xs-3">
                    <a href="sp-category.php?category=<?php echo  isset($category_1) ? $category_1 : ''  ?>">
                        <img src="<?php echo  isset($image_1) ? $image_1 : ''  ?>" class="img-responsive" alt="" height="100%">
                    </a>
                </div>
                <div class="column-center col-xs-6">
                    <a href="sp-category.php?category=<?php echo  isset($category_2) ? $category_2 : ''  ?>" class="img-top">
                        <img src="<?php echo  isset($image_2) ? $image_2 : ''  ?>" class="img-responsive" alt="">
                    </a>
                    <a href="sp-category.php?category=<?php echo  isset($category_3) ? $category_3 : ''  ?>" class="img-bottom">
                        <img src="<?php echo  isset($image_3) ? $image_3 : ''  ?>" class="img-responsive" alt="">
                    </a>
                </div>

                <div class="column-right col-xs-3">
                    <a href="sp-category.php?category=<?php echo  isset($category_4) ? $category_4 : ''  ?>">
                        <img src="<?php echo  isset($image_4) ? $image_4 : ''  ?>" class="img-responsive" alt="">
                    </a>
                </div>

            </div>

        </div>

    </div>
    <div class="body">
        <div class="container">
            <div class="row wapper-center">
                <?php
                $query_category_c = "SELECT * FROM tb_danh_muc WHERE id_cha =0";
                $result_category_c = mysqli_query($dbc, $query_category_c);
                kt_query($query_category_c, $result_category_c);
                while ($category_c = mysqli_fetch_array($result_category_c, MYSQLI_ASSOC)) {
                    $id = lay_id($category_c['id_danh_muc']);
                    if (empty($id)) {
                        $id = $category_c['id_danh_muc'];
                    } else {
                        $id .= "," . $category_c['id_danh_muc'];
                    }


                    ?>

                    <div id="<?php echo $category_c['ten_khong_dau_danh_muc']; ?>" class="col-xs-12  product-body themes-product">
                        <div class="row">
                            <div class="title-<?php echo $category_c['ten_khong_dau_danh_muc']; ?> title-product">
                                <div class="left">
                                    <h2>
                                        <a href="sp-category.php?category=<?php echo $category_c['id_danh_muc']; ?>"><?php echo $category_c['ten_danh_muc']; ?></a>
                                    </h2>
                                </div>
                                <div class="right">
                                    <a href="sp-category.php?category=<?php echo $category_c['id_danh_muc']; ?>">Xem tất
                                    cả</a>
                                </div>
                            </div>
                            <div class="page-button">
                                <div class="pre"></div>
                                <div class="next"></div>
                            </div>
                            <div class="col-xs-12 wp" style="margin-top: 20px">
                                <div class="row width" style="overflow: hidden;">
                                    <div class="wapper">
                                        <?php
                                        $query_sp = "SELECT * FROM tb_san_pham WHERE id_danh_muc IN ($id)";
                                        $result_sp = mysqli_query($dbc, $query_sp);
                                        kt_query($query_sp, $result_sp);
                                        while ($product = mysqli_fetch_array($result_sp, MYSQLI_ASSOC)) {
                                            ?>
                                            <div class="item">
                                                <div class="khung-img">
                                                    <div class="item-img">
                                                        <?php
                                                        $img_product = explode(" ", $product['anh_thumb']);
                                                        $i = 0;
                                                        foreach ($img_product as $value) {
                                                            ?>
                                                            <a href="product.php?id=<?php echo $product['id_san_pham']; ?>">
                                                                <img title="<?php echo $product['ten_san_pham']; ?>"
                                                                src="<?php echo $value; ?>"
                                                                class="img<?php if ($i == 1) {
                                                                 echo '_1';
                                                             } ?>">
                                                         </a>

                                                         <?php
                                                         if ($i == 1) {
                                                            break;
                                                        }
                                                        ++$i;
                                                    }
                                                    ?>

                                                </div>
                                                <div class="text-center name">
                                                  <a href="product.php?id=<?php echo $product['id_san_pham']; ?>" ><?php echo $product['ten_san_pham']; ?></a>
                                                </div>
                                                <div class="text-center price"><?php echo number_format($product['gia_khuyen_mai'], 0, ',', '.'); ?></div>
                                                <div class="button-product">
                                                    <a href="product.php?id=<?php echo $product['id_san_pham']; ?>"
                                                       class="cart">
                                                       <i class="glyphicon glyphicon-shopping-cart"></i><span>Mua ngay</span>
                                                   </a>
                                                   <a href="product.php?id=<?php echo $product['id_san_pham']; ?>"
                                                       class="see">
                                                       <i class="glyphicon glyphicon-triangle-right"></i><span>Chi tiết</span>
                                                   </a>
                                               </div>
                                           </div>
                                       </div>
                                       <?php } ?>
                                   </div>
                               </div>
                           </div>
                       </div>
                   </div>
                   <?php

               }
               ?>

           </div>
       </div>
   </div>  
<div class="container">
    <div class="row">
        <br><br><br><br><br>
      
    </div>
</div>
</div>
<?php
include('include/footer.php');
?>
<script src="js/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="js/slider.js"></script>
<script type="text/javascript" src="js/jquery-main.js"></script>
<script src="http://malsup.github.com/jquery.cycle2.js"></script>
<script type="text/javascript">

    $(document).ready(function () {

        
        $('#themeContainer .wrapper-style .oregional-skins  .item-oregional-skins .item-oregional-skins-x').each(function(){
            if($(this).attr("id")=="default"){
                $(this).css("background","#F1F1F1");
            }else{
             $(this).css("background",function(){
                return $(this).attr("id");
            });
         }
     });
        $("#themes").click(function(){
          $(this).animate({"right": -60},500,function(){
              $("#themeContainer").animate({"right": 0},500);
          });
      });
        $("#themeContainer .wrapper-style >h4").click(function(){
            $("#themeContainer").animate({"right": -200},500,function(){
                $("#themes").animate({"right": -24},500);
            });
        });
        var id ="";
        $("#themeContainer .wrapper-style .oregional-skins  .item-oregional-skins .item-oregional-skins-x").click(function(){
            $("#themeContainer .wrapper-style .oregional-skins  .item-oregional-skins .item-oregional-skins-x").css("border-color","#666");
            $(this).css("border-color","#d73814");
            id=$(this).attr("id").toString();
        });

        $("#themeContainer .wrapper-style .oregional-skins  .item-oregional-skins  #default").click(function(){
            $(".header").css("background","#F1F1F1");
            $(".product-body .title-product .left").css("background","white");
            $(".product-body .title-product .left h2").css("background","url(../image/icon/c.gif) no-repeat left center,#fff url(../image/icon/c.gif) no-repeat right center");
            $(".product-body .title-product .right").css("background","white");
        });
        $("#themeContainer .wrapper-style .time  #save-time").click(function(){

            if (id=="") {
                $("#themeContainer .wrapper-style .time #error").text("Hãy chọn màu");
                $("#themeContainer .wrapper-style .time #error").css({"color":"#bd0103","font-weight":700});
            }
            else if(id=="default"){
                $(".header").css("background","#F1F1F1");
                $(".product-body .title-product .left").css("background","white");
                $(".product-body .title-product .left h2").css("background","url(../image/icon/c.gif) no-repeat left center,#fff url(../image/icon/c.gif) no-repeat right center");

                $(".product-body .title-product .right").css("background","white");
            }else{
                var time;
                if(Number($("#themeContainer .wrapper-style .time #minutes").val()) >=0 && Number($("#themeContainer .wrapper-style .time #seconds").val()) >=0){
                    time = (Number($("#themeContainer .wrapper-style .time #minutes").val())*60 + Number($("#themeContainer .wrapper-style .time #seconds").val())) * 1000;

                }
                else{
                    $("#themeContainer .wrapper-style .time #error").text("Lỗi thời gian");
                    $("#themeContainer .wrapper-style .time #error").css({"color":"#bd0103","font-weight":700});

                }
                $(".header").css("background",id);
                $(".product-body .title-product .left").css("background",id);
                $(".product-body .title-product .left h2").css("background",id);
                $(".product-body .title-product .right").css("background",id);
                setTimeout(function(){
                 $(".header").css("background","#F1F1F1");
                 $(".product-body .title-product .left").css("background","white");
                 $(".product-body .title-product .left h2").css("background","url(../image/icon/c.gif) no-repeat left center,#fff url(../image/icon/c.gif) no-repeat right center");
                 $(".product-body .title-product .right").css("background","white");
             },time);
            }



        });

    //
    $('.body .cart').click(function () {

        $.get("xuli-seeproduct/session-display.php", {display: "display:block"});
    });
    var width_body = $('body').width();
    if (width_body < 480) {

        $('.item').css('width', function () {
            return $('.item').parents('.width').width();

        });
    }
    else if (width_body < 768) {

        $('.item').css('width', function () {
            return $('.item').parents('.width').width() * 50 / 100;

        });
    }
    else if (width_body < 1200) {

        $('.item').css('width', function () {
            return $('.item').parents('.width').width() * 33.33333333 / 100;

        });
    }
    else {

        $('.item').css('width', function () {
            return $(this).parents('.width').width() * 25 / 100;

        });
    }


    var width = $('.item').width() + 30;
    $(window).resize(function () {
            // alert('a');
            width_body = $('body').width();
            width = $('.item').width() + 30;
            // alert(width_body);
            if (width_body < 480) {

                $('.item').css('width', function () {
                    return $('.item').parents('.width').width();

                });
            }
            else if (width_body < 768) {

                $('.item').css('width', function () {
                    return $('.item').parents('.width').width() * 50 / 100;

                });
            }
            else if (width_body < 1200) {

                $('.item').css('width', function () {
                    return $('.item').parents('.width').width() * 33.33333333 / 100;

                });
            }
            else {

                $('.item').css('width', function () {
                    return $(this).parents('.width').width() * 25 / 100;

                });
            }
        });
    <?php
    $query_category = "SELECT ten_khong_dau_danh_muc  FROM tb_danh_muc WHERE id_cha=0";
    $result_category = mysqli_query($dbc, $query_category);
    kt_query($query_category, $result_category);

    while ($category = mysqli_fetch_array($result_category, MYSQLI_NUM)) {
        ?>
        $('#<?php echo trim($category[0]); ?>  .next').click(function () {
            width = $('.item').width() + 30;
            $('#<?php echo trim($category[0]); ?> .wapper').css('opacity', 0.75);
            $('#<?php echo trim($category[0]); ?> .wapper').animate({
                'margin-left': -width,
                opacity: 1
            }, 500, function () {

                $('#<?php echo trim($category[0]); ?> .item:first-child').appendTo('#<?php echo trim($category[0]); ?> .wapper');
                $('#<?php echo trim($category[0]); ?> .wapper').css('margin-left', 0);

            });
        });
        $('#<?php echo trim($category[0]); ?> .pre').click(function () {
            width = $('.item').width() + 30;
            $('#<?php echo trim($category[0]); ?> .wapper').css('opacity', 0.45);
            $('#<?php echo trim($category[0]); ?> .wapper ').css('margin-left', -width);
            $('#<?php echo trim($category[0]); ?> .wapper .item:last-child').prependTo('#<?php echo trim($category[0]); ?> .wapper');
            $('#<?php echo trim($category[0]); ?> .wapper').animate({'margin-left': '0', opacity: 1}, 500, function () {

                $('#<?php echo trim($category[0]); ?> .wapper').css('margin-left', 0);
            });
        });
        <?php } ?>


    });

</script>
</body>
</html>

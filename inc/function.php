<?php
// tìm loại của sp có lượt views cao
function category_name($category=array()){
    global $dbc;
     $query_sp_hot = "SELECT a.id_danh_muc,ten_danh_muc FROM tb_san_pham a,tb_danh_muc b  WHERE a.id_danh_muc=b.id_danh_muc ORDER BY luot_xem";
    $result_sp_hot = mysqli_query($dbc, $query_sp_hot);
    kt_query($query_sp_hot, $result_sp_hot);
    while ($product = mysqli_fetch_array($result_sp_hot, MYSQLI_NUM)) {
        if (!in_array($product[0]."-+&" .$product[1], $category)) {
            array_push($category,$product[0]."-+&" .$product[1]);
            if(count($category)<8){
                category_name($category);
            }
            else{
                return $category;
                break;
            }
        }
    }
    return $category;
}

    // tạo code_order ramdom 7 số 
function ramdom_code(){
    global $dbc;
    $rd = rand(0000000,9999999);
    $query = "SELECT ma_don_hang FROM tb_don_hang";
    $result = mysqli_query($dbc,$query);
    $list_code_order = array();
    while ($array_code = mysqli_fetch_array($result,MYSQLI_NUM) ) {
        array_push($list_code_order, $array_code[0]);
    }
    if (in_array($rd, $list_code_order)) {
        ramdom_code();
    }
    else{
        return $rd;
    }
}


/* bỏ dấu tiếng việt và khoảng trắng */
function stripUnicode($str){
    if(!$str) return false;
    $unicode = array(
        'a'=>'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ|À|Á',
        'd'=>'đ',
        'e'=>'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
        'i'=>'í|ì|ỉ|ĩ|ị',
        'o'=>'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
        'u'=>'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
        'y'=>'ý|ỳ|ỷ|ỹ|ỵ',

    );
    foreach($unicode as $nonUnicode=>$uni){
    	$str = preg_replace("/($uni)/i",$nonUnicode,$str);
    }
    return str_replace(" ","", $str);
}
function kt_query($result,$query)
{
    global $dbc;
    if (!$result){
        die("Query {$query} \n<br/> MYSQL Errors : " .mysqli_error($dbc));
    }
}

function show_categories($parent_id=0,$insert_text="")
{
    global $dbc;
    $query = "SELECT * FROM tb_danh_muc WHERE id_cha={$parent_id}";
    $result = mysqli_query($dbc, $query);
    kt_query($result, $query);
    while ($parent = mysqli_fetch_array($result, MYSQL_ASSOC)) {


        echo("<option value='" . $parent['id_danh_muc'] . "'>" . $insert_text . $parent['ten_danh_muc'] . "</option>");
        $query_c = "SELECT * FROM tb_thong_tin WHERE id_cha={$parent['$parent_id']}";
        $result_c = mysqli_query($dbc, $query_c);
        kt_query($result_c, $query_c);
        while ($parent_c = mysqli_fetch_array($result_c, MYSQL_ASSOC)) {

            echo("<option value='" . $parent_c['id_danh_muc'] . "'>" . $insert_text . " - " . $parent_c['ten_danh_muc'] . "</option>");
            $query_c2 = "SELECT * FROM tb_thong_tin WHERE id_cha={$parent_c['$parent_id']}";
            $result_c2 = mysqli_query($dbc, $query_c2);
            kt_query($result_c2, $query_c2);

            while ($parent_c2 = mysqli_fetch_array($result_c2, MYSQL_ASSOC)) {
                echo("<option value='" . $parent_c2['id_danh_muc'] . "'>" . $insert_text . " -- " . $parent_c2['name_category'] . "</option>");
            }

        }

    }
}

function ctrSelect($name,$class){
    global $dbc;

    echo "<select name='$name' class='$class' style='padding:5px 10px;border-radius:4px;display:block'>";
    echo "<option value='0'>Danh mục cha</option>";
    show_categories();
    echo "</select>";
}

function lay_id($parent_id)
{

    global $dbc;

    $query="SELECT id_danh_muc FROM tb_danh_muc WHERE id_cha={$parent_id}";
    $result= mysqli_query($dbc,$query);
    kt_query($result,$query);
    $save="";
    $save.=$parent_id;
    if(mysqli_num_rows($result) >0){

        while($parent=mysqli_fetch_array($result,MYSQLI_NUM)){
            $save .= ",".$parent[0];
        }
        $query_c="SELECT id_danh_muc FROM tb_danh_muc WHERE id_cha IN ($save)";
        $result_c= mysqli_query($dbc,$query_c);
        kt_query($result_c,$query_c);

        while($parent_c=mysqli_fetch_array($result_c,MYSQLI_NUM)) {
            $save .= ",".$parent_c[0];
        }
        return $save;
    }
//    kết thúc if
}
function kt_category($id_category,$name_category,$parent_id){
    global $dbc;
    if(settype($parent_id,"int")!=0){
        $query_category_c1 = "SELECT id_danh_muc,ten_danh_muc,id_cha FROM tb_danh_muc WHERE id_danh_muc={$parent_id}";
        $result_category_c1 = mysqli_query($dbc, $query_category_c1);
        kt_query($query_category_c1, $result_category_c1);
        list($id_category_c1,$name_category_c1,$parent_id_c1)= mysqli_fetch_array($result_category_c1,MYSQLI_NUM);
        echo  "<li  style='text-transform: capitalize'><a href='sp-category.php?category=$id_category_c1'>$name_category_c1</a></li>";

    }
    echo  "<li  style='text-transform: capitalize'><a href='sp-category.php?category=$id_category'>$name_category</a></li>";


}

function updateView($id){

}
  /* template echo city */
  function echo_city(){
      global $dbc;
      $query = "SELECT * FROM tb_thanh_pho ORDER BY ma_thanh_pho";
      $result = mysqli_query($dbc, $query);
      while ( $rows = mysqli_fetch_assoc($result) ) {
        ?>
        <option value="<?php echo $rows['id_thanh_pho'] ?>"> <?php echo $rows['ten_thanh_pho'] ?> </option>
        <?php
      }
      
  }

  function check_order($code_order){
    global $dbc;
    $query = "SELECT tb_don_hang.kich_co_san_pham size, tb_don_hang.so_luong_san_pham quantity, tb_san_pham.kich_co_san_pham array_size FROM tb_don_hang,tb_san_pham WHERE tb_don_hang.id_san_pham = tb_san_pham.id_san_pham && tb_don_hang.ma_don_hang = $code_order";
    $result = mysqli_query($dbc,$query);
    while($rows = mysqli_fetch_array($result)){
      extract($rows);
      $array_size = unserialize($array_size);
      if( array_key_exists($size, $array_size) || array_key_exists(strtolower($size), $array_size) ){
        if ($array_size[strtolower($size)] < $quantity) {
         return true;
       } else {
        // echo "hihi";
       } 
     } 
  
  
  
   }
  }

  function check_product($id_product,$size, $quantity_order){
    global $dbc;
    $query = "SELECT kich_co_san_pham FROM tb_san_pham WHERE id_san_pham=$id_product";
    $result = mysqli_query($dbc,$query);
    extract(mysqli_fetch_array($result, MYSQLI_ASSOC));
    $size_product = unserialize($kich_co_san_pham);
    if ($size_product[strtolower($size)] <  $quantity_order) {
      return true;
    }
}

?>




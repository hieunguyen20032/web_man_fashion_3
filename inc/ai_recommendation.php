<?php
/**
 * AI Product Recommendation System
 * Gợi ý sản phẩm dựa trên hành vi mua sắm bằng trí tuệ nhân tạo
 * 
 * Thuật toán:
 * 1. Collaborative Filtering: Dựa trên những người dùng tương tự
 * 2. Content-Based Filtering: Dựa trên danh mục, giá, đặc điểm sản phẩm
 * 3. Purchase History: Dựa trên lịch sử mua hàng của người dùng
 * 4. Trending Products: Sản phẩm trending (nhiều lượt xem, mua gần đây)
 */

/**
 * Lấy sản phẩm gợi ý thông minh dựa trên AI
 * 
 * @param int $user_id - ID người dùng (có thể null nếu chưa đăng nhập)
 * @param int $current_product_id - ID sản phẩm hiện tại đang xem (có thể null)
 * @param int $limit - Số lượng sản phẩm gợi ý
 * @return array - Danh sách sản phẩm gợi ý
 */
function get_ai_recommendations($user_id = null, $current_product_id = null, $limit = 8) {
    global $dbc;
    
    $recommendations = array();
    $excluded_ids = array();
    
    // Loại trừ sản phẩm hiện tại
    if ($current_product_id) {
        $excluded_ids[] = $current_product_id;
    }
    
    // 1. Gợi ý dựa trên lịch sử mua hàng (nếu đã đăng nhập)
    if ($user_id) {
        $purchase_based = get_purchase_based_recommendations($user_id, $excluded_ids, ceil($limit / 2));
        $recommendations = array_merge($recommendations, $purchase_based);
        $excluded_ids = array_merge($excluded_ids, array_column($purchase_based, 'id_san_pham'));
    }
    
    // 2. Gợi ý dựa trên sản phẩm đã xem (từ session)
    if (isset($_SESSION['seen']) && !empty($_SESSION['seen'])) {
        $seen_based = get_viewed_based_recommendations($_SESSION['seen'], $excluded_ids, ceil($limit / 3));
        $recommendations = array_merge($recommendations, $seen_based);
        $excluded_ids = array_merge($excluded_ids, array_column($seen_based, 'id_san_pham'));
    }
    
    // 3. Gợi ý dựa trên sản phẩm cùng danh mục (nếu đang xem chi tiết)
    if ($current_product_id) {
        $category_based = get_category_based_recommendations($current_product_id, $excluded_ids, ceil($limit / 3));
        $recommendations = array_merge($recommendations, $category_based);
        $excluded_ids = array_merge($excluded_ids, array_column($category_based, 'id_san_pham'));
    }
    
    // 4. Gợi ý từ Collaborative Filtering (người dùng tương tự)
    if ($user_id || (isset($_SESSION['seen']) && !empty($_SESSION['seen']))) {
        $collaborative = get_collaborative_recommendations($user_id, $excluded_ids, ceil($limit / 4));
        $recommendations = array_merge($recommendations, $collaborative);
        $excluded_ids = array_merge($excluded_ids, array_column($collaborative, 'id_san_pham'));
    }
    
    // 5. Bổ sung bằng sản phẩm trending nếu chưa đủ
    $remaining = $limit - count($recommendations);
    if ($remaining > 0) {
        $trending = get_trending_products($excluded_ids, $remaining);
        $recommendations = array_merge($recommendations, $trending);
    }
    
    // Loại bỏ trùng lặp và giới hạn số lượng
    $unique_recommendations = array();
    $seen_ids = array();
    foreach ($recommendations as $product) {
        if (!in_array($product['id_san_pham'], $seen_ids)) {
            $unique_recommendations[] = $product;
            $seen_ids[] = $product['id_san_pham'];
            if (count($unique_recommendations) >= $limit) {
                break;
            }
        }
    }
    
    // Tính điểm AI score cho mỗi sản phẩm
    $unique_recommendations = calculate_ai_scores($unique_recommendations, $user_id, $current_product_id);
    
    return $unique_recommendations;
}

/**
 * Gợi ý dựa trên lịch sử mua hàng
 */
function get_purchase_based_recommendations($user_id, $excluded_ids = array(), $limit = 4) {
    global $dbc;
    
    // Lấy danh mục sản phẩm đã mua
    $query = "SELECT DISTINCT sp.id_danh_muc, COUNT(*) as purchase_count 
              FROM tb_don_hang dh 
              INNER JOIN tb_san_pham sp ON dh.id_san_pham = sp.id_san_pham 
              WHERE dh.id_nguoi_dung = " . intval($user_id) . " 
              GROUP BY sp.id_danh_muc 
              ORDER BY purchase_count DESC 
              LIMIT 5";
    
    $result = mysqli_query($dbc, $query);
    if (!$result || mysqli_num_rows($result) == 0) {
        return array();
    }
    
    $category_ids = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $category_ids[] = $row['id_danh_muc'];
    }
    
    // Lấy sản phẩm từ các danh mục yêu thích
    $excluded_str = !empty($excluded_ids) ? implode(',', array_map('intval', $excluded_ids)) : '0';
    $category_str = implode(',', $category_ids);
    
    $query = "SELECT sp.*, dm.ten_danh_muc,
              (sp.luot_xem * 0.3 + COALESCE(order_count.cnt, 0) * 0.7) as popularity_score
              FROM tb_san_pham sp 
              LEFT JOIN tb_danh_muc dm ON sp.id_danh_muc = dm.id_danh_muc
              LEFT JOIN (
                  SELECT id_san_pham, COUNT(*) as cnt 
                  FROM tb_don_hang 
                  WHERE ngay_don_hang >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                  GROUP BY id_san_pham
              ) order_count ON sp.id_san_pham = order_count.id_san_pham
              WHERE sp.id_danh_muc IN ($category_str) 
              AND sp.id_san_pham NOT IN ($excluded_str)
              AND sp.trang_thai_san_pham = 1
              ORDER BY popularity_score DESC, RAND()
              LIMIT " . intval($limit);
    
    $result = mysqli_query($dbc, $query);
    $products = array();
    
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $row['recommendation_type'] = 'purchase_history';
            $row['recommendation_reason'] = 'Dựa trên lịch sử mua hàng của bạn';
            $products[] = $row;
        }
    }
    
    return $products;
}

/**
 * Gợi ý dựa trên sản phẩm đã xem
 */
function get_viewed_based_recommendations($seen_products, $excluded_ids = array(), $limit = 4) {
    global $dbc;
    
    if (empty($seen_products)) {
        return array();
    }
    
    // Lấy danh mục và khoảng giá từ sản phẩm đã xem
    $seen_ids = array_keys($seen_products);
    $seen_str = implode(',', array_map('intval', $seen_ids));
    
    $query = "SELECT id_danh_muc, AVG(gia_khuyen_mai) as avg_price 
              FROM tb_san_pham 
              WHERE id_san_pham IN ($seen_str) 
              GROUP BY id_danh_muc";
    
    $result = mysqli_query($dbc, $query);
    if (!$result || mysqli_num_rows($result) == 0) {
        return array();
    }
    
    $categories = array();
    $total_avg_price = 0;
    $count = 0;
    while ($row = mysqli_fetch_assoc($result)) {
        $categories[] = $row['id_danh_muc'];
        $total_avg_price += $row['avg_price'];
        $count++;
    }
    $avg_price = $count > 0 ? $total_avg_price / $count : 500000;
    
    // Tìm sản phẩm tương tự
    $excluded_ids = array_merge($excluded_ids, $seen_ids);
    $excluded_str = !empty($excluded_ids) ? implode(',', array_map('intval', $excluded_ids)) : '0';
    $category_str = implode(',', $categories);
    
    $price_min = $avg_price * 0.5;
    $price_max = $avg_price * 1.5;
    
    $query = "SELECT sp.*, dm.ten_danh_muc,
              ABS(sp.gia_khuyen_mai - $avg_price) as price_diff
              FROM tb_san_pham sp 
              LEFT JOIN tb_danh_muc dm ON sp.id_danh_muc = dm.id_danh_muc
              WHERE sp.id_danh_muc IN ($category_str) 
              AND sp.id_san_pham NOT IN ($excluded_str)
              AND sp.trang_thai_san_pham = 1
              AND sp.gia_khuyen_mai BETWEEN $price_min AND $price_max
              ORDER BY price_diff ASC, sp.luot_xem DESC
              LIMIT " . intval($limit);
    
    $result = mysqli_query($dbc, $query);
    $products = array();
    
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $row['recommendation_type'] = 'viewed_products';
            $row['recommendation_reason'] = 'Dựa trên sản phẩm bạn đã xem';
            $products[] = $row;
        }
    }
    
    return $products;
}

/**
 * Gợi ý dựa trên danh mục sản phẩm hiện tại
 */
function get_category_based_recommendations($product_id, $excluded_ids = array(), $limit = 4) {
    global $dbc;
    
    // Lấy thông tin sản phẩm hiện tại
    $query = "SELECT id_danh_muc, gia_khuyen_mai FROM tb_san_pham WHERE id_san_pham = " . intval($product_id);
    $result = mysqli_query($dbc, $query);
    
    if (!$result || mysqli_num_rows($result) == 0) {
        return array();
    }
    
    $current_product = mysqli_fetch_assoc($result);
    $category_id = $current_product['id_danh_muc'];
    $price = $current_product['gia_khuyen_mai'];
    
    $excluded_ids[] = $product_id;
    $excluded_str = implode(',', array_map('intval', $excluded_ids));
    
    // Tìm sản phẩm cùng danh mục, ưu tiên giá tương đương
    $query = "SELECT sp.*, dm.ten_danh_muc,
              ABS(sp.gia_khuyen_mai - $price) as price_diff,
              (sp.luot_xem * 0.4 + COALESCE(order_count.cnt, 0) * 0.6) as popularity_score
              FROM tb_san_pham sp 
              LEFT JOIN tb_danh_muc dm ON sp.id_danh_muc = dm.id_danh_muc
              LEFT JOIN (
                  SELECT id_san_pham, COUNT(*) as cnt 
                  FROM tb_don_hang 
                  WHERE ngay_don_hang >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                  GROUP BY id_san_pham
              ) order_count ON sp.id_san_pham = order_count.id_san_pham
              WHERE sp.id_danh_muc = $category_id 
              AND sp.id_san_pham NOT IN ($excluded_str)
              AND sp.trang_thai_san_pham = 1
              ORDER BY popularity_score DESC, price_diff ASC
              LIMIT " . intval($limit);
    
    $result = mysqli_query($dbc, $query);
    $products = array();
    
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $row['recommendation_type'] = 'same_category';
            $row['recommendation_reason'] = 'Sản phẩm cùng danh mục';
            $products[] = $row;
        }
    }
    
    return $products;
}

/**
 * Collaborative Filtering: Gợi ý từ người dùng tương tự
 */
function get_collaborative_recommendations($user_id = null, $excluded_ids = array(), $limit = 4) {
    global $dbc;
    
    $excluded_str = !empty($excluded_ids) ? implode(',', array_map('intval', $excluded_ids)) : '0';
    
    // Lấy sản phẩm mà người dùng đã xem hoặc mua
    $user_product_ids = array();
    
    if ($user_id) {
        $query = "SELECT DISTINCT id_san_pham FROM tb_don_hang WHERE id_nguoi_dung = " . intval($user_id);
        $result = mysqli_query($dbc, $query);
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $user_product_ids[] = $row['id_san_pham'];
            }
        }
    }
    
    // Thêm sản phẩm đã xem từ session
    if (isset($_SESSION['seen']) && !empty($_SESSION['seen'])) {
        $user_product_ids = array_merge($user_product_ids, array_keys($_SESSION['seen']));
    }
    
    if (empty($user_product_ids)) {
        return array();
    }
    
    $user_product_str = implode(',', array_map('intval', array_unique($user_product_ids)));
    
    // Tìm người dùng tương tự (đã mua cùng sản phẩm)
    $query = "SELECT DISTINCT dh2.id_san_pham, COUNT(*) as similarity_score
              FROM tb_don_hang dh1
              INNER JOIN tb_don_hang dh2 ON dh1.id_nguoi_dung = dh2.id_nguoi_dung
              WHERE dh1.id_san_pham IN ($user_product_str)
              AND dh2.id_san_pham NOT IN ($user_product_str)
              AND dh2.id_san_pham NOT IN ($excluded_str)
              GROUP BY dh2.id_san_pham
              ORDER BY similarity_score DESC
              LIMIT " . intval($limit * 2);
    
    $result = mysqli_query($dbc, $query);
    if (!$result || mysqli_num_rows($result) == 0) {
        return array();
    }
    
    $recommended_ids = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $recommended_ids[] = $row['id_san_pham'];
    }
    
    if (empty($recommended_ids)) {
        return array();
    }
    
    $recommended_str = implode(',', $recommended_ids);
    
    // Lấy thông tin sản phẩm
    $query = "SELECT sp.*, dm.ten_danh_muc
              FROM tb_san_pham sp 
              LEFT JOIN tb_danh_muc dm ON sp.id_danh_muc = dm.id_danh_muc
              WHERE sp.id_san_pham IN ($recommended_str)
              AND sp.trang_thai_san_pham = 1
              ORDER BY FIELD(sp.id_san_pham, $recommended_str)
              LIMIT " . intval($limit);
    
    $result = mysqli_query($dbc, $query);
    $products = array();
    
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $row['recommendation_type'] = 'collaborative';
            $row['recommendation_reason'] = 'Người mua cùng sở thích đề xuất';
            $products[] = $row;
        }
    }
    
    return $products;
}

/**
 * Lấy sản phẩm trending (nhiều lượt xem, mua gần đây)
 */
function get_trending_products($excluded_ids = array(), $limit = 8) {
    global $dbc;
    
    $excluded_str = !empty($excluded_ids) ? implode(',', array_map('intval', $excluded_ids)) : '0';
    
    $query = "SELECT sp.*, dm.ten_danh_muc,
              (sp.luot_xem * 0.3 + COALESCE(recent_orders.cnt, 0) * 0.5 + COALESCE(total_orders.cnt, 0) * 0.2) as trending_score
              FROM tb_san_pham sp 
              LEFT JOIN tb_danh_muc dm ON sp.id_danh_muc = dm.id_danh_muc
              LEFT JOIN (
                  SELECT id_san_pham, COUNT(*) as cnt 
                  FROM tb_don_hang 
                  WHERE ngay_don_hang >= DATE_SUB(NOW(), INTERVAL 7 DAY)
                  GROUP BY id_san_pham
              ) recent_orders ON sp.id_san_pham = recent_orders.id_san_pham
              LEFT JOIN (
                  SELECT id_san_pham, COUNT(*) as cnt 
                  FROM tb_don_hang 
                  GROUP BY id_san_pham
              ) total_orders ON sp.id_san_pham = total_orders.id_san_pham
              WHERE sp.id_san_pham NOT IN ($excluded_str)
              AND sp.trang_thai_san_pham = 1
              ORDER BY trending_score DESC, sp.luot_xem DESC
              LIMIT " . intval($limit);
    
    $result = mysqli_query($dbc, $query);
    $products = array();
    
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $row['recommendation_type'] = 'trending';
            $row['recommendation_reason'] = 'Đang được ưa chuộng';
            $products[] = $row;
        }
    }
    
    return $products;
}

/**
 * Tính điểm AI score cho sản phẩm
 */
function calculate_ai_scores($products, $user_id = null, $current_product_id = null) {
    foreach ($products as &$product) {
        $score = 0;
        
        // Điểm dựa trên loại gợi ý
        switch ($product['recommendation_type']) {
            case 'purchase_history':
                $score += 40;
                break;
            case 'collaborative':
                $score += 35;
                break;
            case 'viewed_products':
                $score += 30;
                break;
            case 'same_category':
                $score += 25;
                break;
            case 'trending':
                $score += 20;
                break;
        }
        
        // Điểm dựa trên lượt xem (chuẩn hóa)
        $views = isset($product['luot_xem']) ? intval($product['luot_xem']) : 0;
        $score += min(30, $views / 10);
        
        // Điểm dựa trên trạng thái còn hàng
        if (isset($product['trang_thai_san_pham']) && $product['trang_thai_san_pham'] == 1) {
            $score += 10;
        }
        
        // Random factor để đa dạng hóa (5-15%)
        $score += rand(5, 15);
        
        $product['ai_score'] = min(100, round($score));
        $product['ai_confidence'] = $score >= 50 ? 'high' : ($score >= 30 ? 'medium' : 'normal');
    }
    
    // Sắp xếp theo điểm AI
    usort($products, function($a, $b) {
        return $b['ai_score'] - $a['ai_score'];
    });
    
    return $products;
}

/**
 * Lấy sản phẩm gợi ý sau khi mua hàng
 */
function get_post_purchase_recommendations($purchased_product_ids, $limit = 8) {
    global $dbc;
    
    if (empty($purchased_product_ids) || !is_array($purchased_product_ids)) {
        return get_trending_products(array(), $limit);
    }
    
    $purchased_str = implode(',', array_map('intval', $purchased_product_ids));
    
    // Lấy danh mục và giá trung bình của sản phẩm đã mua
    $query = "SELECT id_danh_muc, AVG(gia_khuyen_mai) as avg_price 
              FROM tb_san_pham 
              WHERE id_san_pham IN ($purchased_str) 
              GROUP BY id_danh_muc";
    
    $result = mysqli_query($dbc, $query);
    $categories = array();
    $total_price = 0;
    $count = 0;
    
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $categories[] = $row['id_danh_muc'];
            $total_price += $row['avg_price'];
            $count++;
        }
    }
    
    if (empty($categories)) {
        return get_trending_products(array_map('intval', $purchased_product_ids), $limit);
    }
    
    $avg_price = $count > 0 ? $total_price / $count : 500000;
    $category_str = implode(',', $categories);
    
    // Tìm sản phẩm bổ sung (upsell/cross-sell)
    $query = "SELECT sp.*, dm.ten_danh_muc,
              CASE 
                WHEN sp.id_danh_muc IN ($category_str) THEN 0.8
                ELSE 0.5
              END as category_match,
              (sp.luot_xem * 0.3 + COALESCE(order_count.cnt, 0) * 0.7) as popularity
              FROM tb_san_pham sp 
              LEFT JOIN tb_danh_muc dm ON sp.id_danh_muc = dm.id_danh_muc
              LEFT JOIN (
                  SELECT id_san_pham, COUNT(*) as cnt 
                  FROM tb_don_hang 
                  WHERE ngay_don_hang >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                  GROUP BY id_san_pham
              ) order_count ON sp.id_san_pham = order_count.id_san_pham
              WHERE sp.id_san_pham NOT IN ($purchased_str)
              AND sp.trang_thai_san_pham = 1
              ORDER BY category_match DESC, popularity DESC, RAND()
              LIMIT " . intval($limit);
    
    $result = mysqli_query($dbc, $query);
    $products = array();
    
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $row['recommendation_type'] = 'post_purchase';
            $row['recommendation_reason'] = 'Bạn có thể thích sản phẩm này';
            $products[] = $row;
        }
    }
    
    return calculate_ai_scores($products, null, null);
}
?>

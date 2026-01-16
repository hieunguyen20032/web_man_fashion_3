<?php
/**
 * AI Product Recommendation Component
 * Component hiển thị sản phẩm gợi ý bằng AI
 * 
 * Sử dụng:
 * <?php include('include/ai_recommendation_component.php'); ?>
 * <?php render_ai_recommendations($user_id, $current_product_id, $title, $limit); ?>
 */

/**
 * Render component gợi ý sản phẩm AI
 */
function render_ai_recommendations($user_id = null, $current_product_id = null, $title = 'GỢI Ý DÀNH RIÊNG CHO BẠN', $limit = 8, $section_id = 'ai-recommendations') {
    global $dbc;
    
    // Include AI recommendation logic nếu chưa có
    if (!function_exists('get_ai_recommendations')) {
        include_once(__DIR__ . '/../inc/ai_recommendation.php');
    }
    
    // Lấy danh sách sản phẩm gợi ý
    $recommendations = get_ai_recommendations($user_id, $current_product_id, $limit);
    
    if (empty($recommendations)) {
        return;
    }
    ?>
    
    <div id="<?php echo htmlspecialchars($section_id); ?>" class="ai-recommendation-section">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="ai-recommendation-header">
                        <div class="ai-badge">
                            <span class="ai-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 2L2 7L12 12L22 7L12 2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M2 17L12 22L22 17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M2 12L12 17L22 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>
                            <span class="ai-text">AI Powered</span>
                        </div>
                        <h2 class="ai-title">
                            <span class="sparkle">✨</span>
                            <?php echo htmlspecialchars($title); ?>
                            <span class="sparkle">✨</span>
                        </h2>
                        <p class="ai-subtitle">Được cá nhân hóa dựa trên hành vi mua sắm của bạn</p>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-xs-12">
                    <div class="ai-products-carousel">
                        <button class="carousel-btn carousel-prev" onclick="slideAIProducts('prev', '<?php echo $section_id; ?>')">
                            <i class="glyphicon glyphicon-chevron-left"></i>
                        </button>
                        
                        <div class="ai-products-wrapper">
                            <div class="ai-products-track" id="<?php echo $section_id; ?>-track">
                                <?php foreach ($recommendations as $index => $product): ?>
                                    <?php 
                                    $img_product = explode(" ", $product['anh_thumb']);
                                    $first_image = isset($img_product[0]) ? $img_product[0] : '';
                                    $second_image = isset($img_product[1]) ? $img_product[1] : $first_image;
                                    
                                    $confidence_class = isset($product['ai_confidence']) ? $product['ai_confidence'] : 'normal';
                                    $ai_score = isset($product['ai_score']) ? $product['ai_score'] : 0;
                                    $reason = isset($product['recommendation_reason']) ? $product['recommendation_reason'] : '';
                                    ?>
                                    <div class="ai-product-card" data-index="<?php echo $index; ?>">
                                        <div class="ai-product-inner">
                                            <?php if ($ai_score >= 70): ?>
                                            <div class="ai-match-badge high">
                                                <span class="match-icon">🎯</span>
                                                <span class="match-text"><?php echo $ai_score; ?>% phù hợp</span>
                                            </div>
                                            <?php elseif ($ai_score >= 50): ?>
                                            <div class="ai-match-badge medium">
                                                <span class="match-icon">⭐</span>
                                                <span class="match-text">Đề xuất cao</span>
                                            </div>
                                            <?php endif; ?>
                                            
                                            <div class="ai-product-image">
                                                <a href="product.php?id=<?php echo $product['id_san_pham']; ?>">
                                                    <img src="<?php echo $first_image; ?>" 
                                                         alt="<?php echo htmlspecialchars($product['ten_san_pham']); ?>"
                                                         class="primary-img"
                                                         loading="lazy">
                                                    <?php if ($second_image !== $first_image): ?>
                                                    <img src="<?php echo $second_image; ?>" 
                                                         alt="<?php echo htmlspecialchars($product['ten_san_pham']); ?>"
                                                         class="secondary-img"
                                                         loading="lazy">
                                                    <?php endif; ?>
                                                </a>
                                                
                                                <div class="ai-quick-actions">
                                                    <a href="product.php?id=<?php echo $product['id_san_pham']; ?>" class="action-btn view-btn" title="Xem chi tiết">
                                                        <i class="glyphicon glyphicon-eye-open"></i>
                                                    </a>
                                                    <a href="product.php?id=<?php echo $product['id_san_pham']; ?>" class="action-btn cart-btn" title="Mua ngay">
                                                        <i class="glyphicon glyphicon-shopping-cart"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            
                                            <div class="ai-product-info">
                                                <?php if ($reason): ?>
                                                <div class="ai-reason">
                                                    <span class="reason-icon">💡</span>
                                                    <span class="reason-text"><?php echo htmlspecialchars($reason); ?></span>
                                                </div>
                                                <?php endif; ?>
                                                
                                                <h3 class="ai-product-name">
                                                    <a href="product.php?id=<?php echo $product['id_san_pham']; ?>">
                                                        <?php echo htmlspecialchars($product['ten_san_pham']); ?>
                                                    </a>
                                                </h3>
                                                
                                                <div class="ai-product-price">
                                                    <span class="current-price">
                                                        <?php echo number_format($product['gia_khuyen_mai'], 0, ',', '.'); ?>₫
                                                    </span>
                                                    <?php if (isset($product['gia_san_pham']) && $product['gia_san_pham'] > $product['gia_khuyen_mai']): ?>
                                                    <span class="original-price">
                                                        <?php echo number_format($product['gia_san_pham'], 0, ',', '.'); ?>₫
                                                    </span>
                                                    <span class="discount-percent">
                                                        -<?php echo round((($product['gia_san_pham'] - $product['gia_khuyen_mai']) / $product['gia_san_pham']) * 100); ?>%
                                                    </span>
                                                    <?php endif; ?>
                                                </div>
                                                
                                                <a href="product.php?id=<?php echo $product['id_san_pham']; ?>" class="ai-buy-btn">
                                                    <i class="glyphicon glyphicon-shopping-cart"></i>
                                                    <span>Mua ngay</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        
                        <button class="carousel-btn carousel-next" onclick="slideAIProducts('next', '<?php echo $section_id; ?>')">
                            <i class="glyphicon glyphicon-chevron-right"></i>
                        </button>
                    </div>
                    
                    <div class="ai-carousel-dots" id="<?php echo $section_id; ?>-dots">
                        <?php 
                        $totalDots = ceil(count($recommendations) / 4);
                        for ($i = 0; $i < $totalDots; $i++): 
                        ?>
                        <span class="dot <?php echo $i === 0 ? 'active' : ''; ?>" 
                              onclick="goToAISlide(<?php echo $i; ?>, '<?php echo $section_id; ?>')"></span>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php
}

/**
 * Render component gợi ý sau khi mua hàng
 */
function render_post_purchase_recommendations($purchased_product_ids, $title = 'BẠN CÓ THỂ THÍCH', $limit = 8) {
    global $dbc;
    
    // Include AI recommendation logic nếu chưa có
    if (!function_exists('get_post_purchase_recommendations')) {
        include_once(__DIR__ . '/../inc/ai_recommendation.php');
    }
    
    $recommendations = get_post_purchase_recommendations($purchased_product_ids, $limit);
    
    if (empty($recommendations)) {
        return;
    }
    
    // Render với title và section ID khác
    render_ai_recommendations_html($recommendations, $title, 'post-purchase-recommendations');
}

/**
 * Helper function để render HTML
 */
function render_ai_recommendations_html($recommendations, $title, $section_id) {
    if (empty($recommendations)) {
        return;
    }
    ?>
    
    <div id="<?php echo htmlspecialchars($section_id); ?>" class="ai-recommendation-section post-purchase">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="ai-recommendation-header">
                        <div class="ai-badge success">
                            <span class="ai-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M20 6L9 17L4 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>
                            <span class="ai-text">Smart Suggestion</span>
                        </div>
                        <h2 class="ai-title">
                            <span class="sparkle">🎁</span>
                            <?php echo htmlspecialchars($title); ?>
                            <span class="sparkle">🎁</span>
                        </h2>
                        <p class="ai-subtitle">Tiếp tục khám phá những sản phẩm hoàn hảo cho bạn</p>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-xs-12">
                    <div class="ai-products-carousel">
                        <button class="carousel-btn carousel-prev" onclick="slideAIProducts('prev', '<?php echo $section_id; ?>')">
                            <i class="glyphicon glyphicon-chevron-left"></i>
                        </button>
                        
                        <div class="ai-products-wrapper">
                            <div class="ai-products-track" id="<?php echo $section_id; ?>-track">
                                <?php foreach ($recommendations as $index => $product): ?>
                                    <?php 
                                    $img_product = explode(" ", $product['anh_thumb']);
                                    $first_image = isset($img_product[0]) ? $img_product[0] : '';
                                    $second_image = isset($img_product[1]) ? $img_product[1] : $first_image;
                                    ?>
                                    <div class="ai-product-card" data-index="<?php echo $index; ?>">
                                        <div class="ai-product-inner">
                                            <div class="ai-product-image">
                                                <a href="product.php?id=<?php echo $product['id_san_pham']; ?>">
                                                    <img src="<?php echo $first_image; ?>" 
                                                         alt="<?php echo htmlspecialchars($product['ten_san_pham']); ?>"
                                                         class="primary-img"
                                                         loading="lazy">
                                                    <?php if ($second_image !== $first_image): ?>
                                                    <img src="<?php echo $second_image; ?>" 
                                                         alt="<?php echo htmlspecialchars($product['ten_san_pham']); ?>"
                                                         class="secondary-img"
                                                         loading="lazy">
                                                    <?php endif; ?>
                                                </a>
                                            </div>
                                            
                                            <div class="ai-product-info">
                                                <h3 class="ai-product-name">
                                                    <a href="product.php?id=<?php echo $product['id_san_pham']; ?>">
                                                        <?php echo htmlspecialchars($product['ten_san_pham']); ?>
                                                    </a>
                                                </h3>
                                                
                                                <div class="ai-product-price">
                                                    <span class="current-price">
                                                        <?php echo number_format($product['gia_khuyen_mai'], 0, ',', '.'); ?>₫
                                                    </span>
                                                </div>
                                                
                                                <a href="product.php?id=<?php echo $product['id_san_pham']; ?>" class="ai-buy-btn">
                                                    <i class="glyphicon glyphicon-shopping-cart"></i>
                                                    <span>Xem ngay</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        
                        <button class="carousel-btn carousel-next" onclick="slideAIProducts('next', '<?php echo $section_id; ?>')">
                            <i class="glyphicon glyphicon-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php
}
?>

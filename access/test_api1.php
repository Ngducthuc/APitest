<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once('FlaskApiService.php'); // Đảm bảo file FlaskApiService.php đã tồn tại và đúng đường dẫn

// Khởi tạo service gọi API
$apiService = new FlaskApiService('http://localhost:5000'); // Đường dẫn tới Flask API của bạn

try {
    // Gửi yêu cầu tới API với sản phẩm mẫu (ví dụ: sản phẩm ID 1)
    $testProductId = 1;
    $response = $apiService->getProductRecommendations($testProductId);

    // Hiển thị phản hồi từ API
    echo "<h1>Phản hồi từ API:</h1>";
    echo "<pre>";
    print_r($response);
    echo "</pre>";
} catch (Exception $e) {
    // Hiển thị lỗi nếu gọi API thất bại
    echo "<h1>Lỗi gọi API:</h1>";
    echo "<p>" . $e->getMessage() . "</p>";
} 


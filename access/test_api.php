<?php
require_once('FlaskApiService.php');

error_log("Action: " . (isset($_GET['action']) ? $_GET['action'] : 'NULL'));
error_log("Product ID: " . (isset($_GET['product_id']) ? $_GET['product_id'] : 'NULL'));

// Khởi tạo FlaskApiService
$flaskApi = new FlaskApiService("http://127.0.0.1:5000"); // Flask API chạy trên cổng 5000

// Kiểm tra tham số `action` trên URL
$action = isset($_GET['action']) ? $_GET['action'] : '';

// Xử lý các hành động tương ứng
switch ($action) {
    case 'analyze':
        // Gọi endpoint `/api/analyze`
        $response = $flaskApi->callGetApi('/api/analyze');
        echo $response;
        break;

    case 'recommend':
        // Lấy product_id từ URL (vd: ?action=recommend&product_id=1)
        $product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;
        if ($product_id > 0) {
            $response = json_encode($flaskApi->callGetApi("/api/recommend/$product_id"));
            echo $response;
        } else {
            echo json_encode(["error" => "Thiếu product_id"]);
        }
        break;

    case 'trending':
        // Gọi endpoint `/api/trending-products`
        $response = $flaskApi->callGetApi('/api/trending-products');
        echo $response;
        break;

    default:
        echo json_encode(["error" => "Hành động không hợp lệ."]);
        break;
}


?>

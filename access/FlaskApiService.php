<?php
class FlaskApiService {
    private $baseUrl;

    public function __construct($baseUrl = 'http://localhost:5000') {
        $this->baseUrl = $baseUrl;
    }

    /**
     * Phương thức gọi chung cho các API GET
     * @param string $endpoint
     * @param array $params Các tham số query
     * @return array Kết quả từ API
     */
    public function callGetApi($endpoint, $params = []) {
        $url = $this->baseUrl . $endpoint;
        
        // Thêm query params nếu có
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Accept: application/json',
                'Content-Type: application/json'
            ]
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            throw new Exception("Curl Error: " . $error);
        }
        
        curl_close($ch);

        // Kiểm tra mã trạng thái
        if ($httpCode != 200) {
            throw new Exception("API Error: HTTP {$httpCode} - " . $response);
        }

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            error_log("CURL Error: " . curl_error($ch)); // Lỗi cURL
        } else {
            error_log("Response from Flask API: " . $response); // Log phản hồi từ Flask
        }
        return json_decode($response, true);
    }

    /**
     * Phân tích dữ liệu
     * @return array Dữ liệu phân tích
     */
    public function analyzeData() {
        try {
            return $this->callGetApi('/api/analyze');
        } catch (Exception $e) {
            // Log lỗi hoặc xử lý
            error_log($e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Lấy gợi ý sản phẩm
     * @param int $productId ID sản phẩm
     * @return array Danh sách sản phẩm được đề xuất
     */
    public function getProductRecommendations($productId) {
        try {
            $result = $this->callGetApi("/api/recommend/{$productId}");
            return $result['product'] ?? [];
        } catch (Exception $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    /**
     * Lấy sản phẩm xu hướng
     * @return array Danh sách sản phẩm xu hướng
     */
    public function getTrendingProducts() {
        try {
            return $this->callGetApi('/api/trending-products');
        } catch (Exception $e) {
            error_log($e->getMessage());
            return [];
        }
    }
}

// Ví dụ sử dụng
try {
    $apiService = new FlaskApiService('http://localhost:5000');

    // Phân tích dữ liệu
    $analyzeData = $apiService->analyzeData();
    
    // Gợi ý sản phẩm cho ID 123
    $recommendations = $apiService->getProductRecommendations(123);
    
    // Lấy sản phẩm xu hướng
    $trendingProducts = $apiService->getTrendingProducts();

    // Xử lý và hiển thị kết quả
    print_r($recommendations);
} catch (Exception $e) {
    echo "Lỗi: " . $e->getMessage();
}
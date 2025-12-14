<?php
    header("Content-Type: text/html; charset=UTF-8");
    $url = 'https://vietnamnet.vn'; 

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    // 1. QUAN TRỌNG: Giả lập trình duyệt thật để tránh bị chặn
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36");
    
    // 2. QUAN TRỌNG: Tự động giải nén GZIP/Brotli (Nguyên nhân chính gây lỗi không đọc được HTML)
    curl_setopt($ch, CURLOPT_ENCODING, ''); 
    
    // 3. Tự động theo dõi chuyển hướng (Redirect)
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

    // Bỏ qua lỗi SSL (Chỉ dùng cho Localhost)
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15); 

    $html = curl_exec($ch);
    $curl_error = curl_error($ch); 
    curl_close($ch);

    if (!$html) {
        die("Lỗi cURL: " . htmlspecialchars($curl_error));
    } else {
        echo "✔️ Tải thành công trang: $url <br>";
    }
    
    // Phân tích HTML
    $doc = new DOMDocument();
    libxml_use_internal_errors(true); // Bỏ qua các lỗi cú pháp HTML nhỏ
    
    // Load HTML (Thêm mb_convert_encoding nếu cần thiết để xử lý tiếng Việt tốt hơn)
    if (!empty($html)) {
        $doc->loadHTML($html);
    }

    $xpath = new DOMXPath($doc);
    
    // --- CẬP NHẬT XPATH MỚI NHẤT CHO VIETNAMNET ---
    // VietNamNet thường dùng thẻ h3 cho tiêu đề tin. 
    // Query này lấy tất cả thẻ a nằm trong h3 (bất kể class nào) để đảm bảo không bị sót.
    $xpath_query = '//h3/a'; 

    $nodes = $xpath->query($xpath_query);

    echo "<h2>Tiêu đề tin trang VietNamNet:</h2>";
    
    if ($nodes->length > 0) {
        $count = 0;
        foreach ($nodes as $node) {
            // Lấy tối đa 20 tin để demo
            if ($count >= 20) break;
            
            $title = trim($node->nodeValue);
            // Bỏ qua các tiêu đề rỗng
            if (empty($title)) continue;

            $link = $node->getAttribute('href');
            
            // Xử lý link tương đối
            if (strpos($link, 'http') === false) {
                 // Nếu link bắt đầu bằng /, nối trực tiếp. Nếu không, thêm /
                 $prefix = (strpos($link, '/') === 0) ? 'https://vietnamnet.vn' : 'https://vietnamnet.vn/';
                 $link = $prefix . $link;
            }
            
            echo "<p>🔹 <a href='$link' target='_blank' style='text-decoration:none; color:#0056b3; font-weight:bold;'>$title</a></p>";
            $count++;
        }
        echo "<p><i>Đã tìm thấy tổng cộng: " . $nodes->length . " tin.</i></p>";
    } else {
        // Debug: Nếu vẫn không tìm thấy, in ra 500 ký tự đầu để kiểm tra xem tải về cái gì
        echo "<p style='color:red'>❌ Không tìm thấy tiêu đề nào.</p>";
        echo "<textarea style='width:100%; height:200px'>" . htmlspecialchars(substr($html, 0, 2000)) . "</textarea>";
    }
?>
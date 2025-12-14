<?php
    header("Content-Type: text/html; charset=UTF-8");
    $url = 'https://dantri.com.vn/';

    // 1. Cấu hình và sử dụng cURL để tải trang
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64)");
    
    // Tùy chọn xử lý SSL (giúp tránh lỗi khi quét HTTPS trên môi trường cục bộ)
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    
    // Đặt thời gian chờ
    curl_setopt($ch, CURLOPT_TIMEOUT, 15); 
    

    $html = curl_exec($ch);
    $curl_error = curl_error($ch); 
    curl_close($ch);

    // Kiểm tra lỗi cURL
    if (!$html) {
        die("Không thể tải trang <em>$url</em>. Lỗi cURL: " . htmlspecialchars($curl_error));
    } else {
        echo "✔️ Tải thành công trang: $url<br>";
    }
    
    // 2. Phân tích HTML bằng DOM/XPath
    $doc = new DOMDocument();
    libxml_use_internal_errors(true); // Bỏ qua lỗi cú pháp HTML
    $doc->loadHTML($html);

    $xpath = new DOMXPath($doc);
    
    // XPATH ĐÃ CẬP NHẬT cho trang Dân Trí: 
    // Tìm các thẻ <a> nằm trong <h3> có class chứa "article-title" (tin nổi bật/tin chính)
    $xpath_query = '//h3[contains(@class, "article-title")]/a | //h2[contains(@class, "article-title")]/a';
    $nodes = $xpath->query($xpath_query);

    echo "<h2>Tiêu đề tin trang Dân Trí:</h2>";
    
    // 3. Hiển thị kết quả
    if ($nodes->length > 0) {
        foreach ($nodes as $node) {
            /** @var DOMElement $node */
            $title = trim($node->nodeValue);
            $link = $node->getAttribute('href');
            
            // Xử lý link tương đối: Nếu link không bắt đầu bằng http(s), thêm domain vào
            if (strpos($link, 'http') === false) {
                 $link = 'https://dantri.com.vn' . $link;
            }
            
            echo "<p><a href='$link' target='_blank'>$title</a></p>";
        }
    } else {
        echo "<p>❌ Không tìm thấy tiêu đề nào. Kiểm tra lại cấu trúc HTML của Dân Trí.</p>";
    }
?>
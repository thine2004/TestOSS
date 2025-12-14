<?php
    header("Content-Type: text/html; charset=UTF-8");
    $url = 'https://stu-nghia.info/';

    // Sử dụng cURL để tải trang
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64)");
    
    // THÊM: Tùy chọn xử lý SSL (thường gây lỗi khi quét HTTPS)
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    
    // THÊM: Tăng thời gian chờ (để tránh lỗi timeout)
    curl_setopt($ch, CURLOPT_TIMEOUT, 15); 
    

    $html = curl_exec($ch);
    $curl_error = curl_error($ch); // Lấy thông báo lỗi cURL
    curl_close($ch);

    // Kiểm tra nếu lấy nội dung thành công
    if (!$html) {
        // In thông báo lỗi chi tiết
        die("Không thể tải trang <em>$url</em>. Lỗi cURL: " . htmlspecialchars($curl_error));
    } else {
        echo "✔️ Tải thành công trang: $url<br>";
    }
    
    // ... Phần còn lại của code DOM/XPath
    $doc = new DOMDocument();
    libxml_use_internal_errors(true);
    $doc->loadHTML($html);

    $xpath = new DOMXPath($doc);
    $nodes = $xpath->query('//h2[@class="uk-h5 uk-margin-small"]/a');

    echo "<h2>Tiêu đề tin trang Thầy Nghĩa:</h2>";
    // ... (Phần hiển thị kết quả)
    if ($nodes->length > 0) {
        foreach ($nodes as $node) {
            /** @var DOMElement $node */  // <-- THÊM DÒNG NÀY ĐỂ BÁO CHO VS Code BIẾT KIỂU DỮ LIỆU CỦA $node
            $title = trim($node->nodeValue);
            $link = $node->getAttribute('href');
            echo "<p><a href='$link' target='_blank'>$title</a></p>";
        }
    } else {
        echo "<p>Không tìm thấy tiêu đề nào. Kiểm tra lại XPath hoặc cấu trúc HTML.</p>";
    }
?>
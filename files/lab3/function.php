<?php
    function aggregateFunctions() {
        // Tạo chuỗi chứa tên các hàm
        $functions = [
        'BCC',
        'BanCo',
        ];
        // Khởi tạo chuỗi kết quả
        $result = "";
        // Lặp qua các hàm và gọi chúng
        foreach ($functions as $function) {
            if (function_exists($function)) {
                $result .= $function() . " "; // Gọi hàm và thêm vào chuỗi
                }
        }
        return trim($result);
    }
    aggregateFunctions();
?>
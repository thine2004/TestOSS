<?php
// 5.2 Viết hàm kiểm tra một chuỗi có đối xứng không
function kiem_tra_chuoi_palindrome($string)
{
    // Sửa lỗi 1: Dùng dấu nháy kép " " để nhận diện biến $string
    // Thêm <br> để xuống dòng cho dễ nhìn
    echo "Chuỗi hiện tại: $string <br>"; 
    
    $chuoi_dao = strrev($string);
    echo "Chuỗi đảo: " . $chuoi_dao . "<br>";

    // So sánh
    if ($string == $chuoi_dao)
    {
        echo "Kết quả: Chuỗi đối xứng <br>";
        return 1;
    }
    else
    {
        echo "Kết quả: Chuỗi không đối xứng <br>";
        return 0;
    }
}

// Sửa lỗi 2: Truyền tham số vào hàm khi gọi
echo "--- Test 1 ---<br>";
kiem_tra_chuoi_palindrome('abcba'); 

echo "<br>--- Test 2 ---<br>";
kiem_tra_chuoi_palindrome('abcdba'); 
?>
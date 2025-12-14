<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>lab 2_5</title>
</head>

<body>
<?php
	include("lab2_5a.php");
	
    include("lab2_5a.php");
	if(isset($x))
		echo "Giá trị của x là: $x";
	else
		echo "Biến x không tồn tại";
    echo"<br>";
    echo " KQ khi thêm include ở dòng 12 thì đoạn mã sẽ chạy tại lần đầu ở dòng 10, và sau đó sẽ chạy tiếp dòng 12";
    echo"<br>";
    echo "Nếu lab2_5.php tạo ra biến $x , thì chương trình sẽ in ra giá trị của nó";
    echo"<br>";
    echo "Nếu lab2_5.php chứa hàm, chương trình sẽ lỗi và dừng lại"
   
    
?>
</body>
</html>
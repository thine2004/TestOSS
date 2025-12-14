<?php
// Hàm lấy dữ liệu từ form POST
function postIndex($index, $value = "")
{
    if (!isset($_POST[$index])) return $value;
    return trim($_POST[$index]);
}

// Hàm kiểm tra tính hợp lệ của username
function checkUserName($string)
{
    // Kiểm tra độ dài tối thiểu là 1 ký tự (thay * bằng +)
    if (preg_match("/^[a-zA-Z0-9._-]+$/", $string))
        return true;
    return false;
}

// Hàm kiểm tra định dạng email
function checkEmail($string)
{
    // ĐÃ XÓA dòng: echo $string; (Nguyên nhân gây lỗi hiện email ra màn hình)
    if (preg_match("/^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z.]{2,5}$/", $string))
        return true;
    return false;
}

// Lấy dữ liệu
$sm = postIndex("submit");
$username = postIndex("username");
$email = postIndex("email");

// Biến chứa thông báo lỗi
$error_message = "";

// Xử lý kiểm tra khi bấm submit
if ($sm != "") {
    if (checkUserName($username) == false) {
        $error_message .= "Username: Các ký tự được phép: a-z, A-Z, số 0-9, ký tự ., _ và - <br>";
    }
    if (checkEmail($email) == false) {
        $error_message .= "Định dạng email sai!<br>";
    }
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Lab6_3</title>
    <style>
        fieldset { width: 50%; margin: 50px auto; }
        .info { width: 600px; color: #006; background: #6FC; margin: 20px auto; padding: 10px; text-align: center; }
        .error { background: #ffcccc; color: red; } /* Style cho lỗi */
        .success { background: #ccffcc; color: green; } /* Style cho thành công */
        #frm1 input { width: 300px }
    </style>
</head>

<body>
    <fieldset>
        <legend>Đăng ký thông tin</legend>
        <form action="" method="post" enctype="multipart/form-data" id='frm1'>
            <table align="center">
                <tr>
                    <td>UserName</td>
                    <td><input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>" />*</td>
                </tr>
                <tr>
                    <td>Mật khẩu</td>
                    <td><input type="password" name="password" />*</td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td><input type="text" name="email" value="<?php echo htmlspecialchars($email); ?>" />*</td>
                </tr>
                <tr>
                    <td>Ngày sinh</td>
                    <td><input type="text" name="date" />*</td>
                </tr>
                <tr>
                    <td>Điện thoại</td>
                    <td><input type="text" name="phone" /></td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                        <input type="submit" value="submit" name="submit">
                    </td>
                </tr>
            </table>
        </form>
    </fieldset>

    <?php
    // Hiển thị kết quả
    if ($sm != "") {
        if ($error_message != "") {
            // Có lỗi thì in ra bảng màu đỏ
            echo '<div class="info error"><b>Lỗi:</b><br />' . $error_message . '</div>';
        } else {
            // Không có lỗi thì báo thành công
            echo '<div class="info success">Đăng ký thành công! <br> Email: ' . $email . '</div>';
        }
    }
    ?>
</body>
</html>
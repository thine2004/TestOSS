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
    if (preg_match("/^[a-zA-Z0-9._-]*$/", $string))
        return true;
    return false;
}

// Hàm kiểm tra định dạng email
function checkEmail($string)
{
    if (preg_match("/^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z.]{2,5}$/", $string))
        return true;
    return false;
}

// --- SỬA LẠI HÀM NÀY ---
// Hàm kiểm tra mật khẩu
function checkPassword($password)
{
    // Giải thích Regex mới: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/
    // (?=.*[a-z]): Phải có chữ thường
    // (?=.*[A-Z]): Phải có chữ hoa
    // (?=.*\d)   : Phải có số
    // .{8,}      : Dấu chấm (.) nghĩa là chấp nhận mọi ký tự (cả @, #...), độ dài từ 8 trở lên
    if (preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/", $password))
        return true;
    return false;
}

// Hàm kiểm tra số điện thoại
function checkPhone($phone)
{
    if (preg_match("/^\d+$/", $phone))
        return true;
    return false;
}

// Hàm kiểm tra ngày sinh
function checkDateOfBirth($date)
{
    if (preg_match("/^(0[1-9]|[12][0-9]|3[01])[-\/](0[1-9]|1[0-2])[-\/](19|20)\d\d$/", $date))
        return true;
    return false;
}

// Lấy dữ liệu từ form
$sm = postIndex("submit");
$username = postIndex("username");
$password = postIndex("password");
$email = postIndex("email");
$date = postIndex("date");
$phone = postIndex("phone");

// Biến chứa lỗi
$error_message = "";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Lab6_4.2 Fix</title>
    <style>
        fieldset { width: 50%; margin: 50px auto; }
        .info { width: 600px; margin: 20px auto; padding: 10px; text-align: center; }
        .error { background: #ffcccc; color: red; border: 1px solid red; }
        .success { background: #ccffcc; color: green; border: 1px solid green; }
        #frm1 input { width: 300px; }
    </style>
</head>

<body>
    <fieldset>
        <legend style="margin:0 auto">Đăng ký thông tin</legend>
        <form action="" method="post" enctype="multipart/form-data" id='frm1'>
            <table align="center">
                <tr>
                    <td width="88">UserName</td>
                    <td width="317"><input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>" />*</td>
                </tr>
                <tr>
                    <td>Mật khẩu</td>
                    <td><input type="text" name="password" value="<?php echo htmlspecialchars($password); ?>" />*</td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td><input type="text" name="email" value="<?php echo htmlspecialchars($email); ?>" />*</td>
                </tr>
                <tr>
                    <td>Ngày sinh</td>
                    <td><input type="text" name="date" value="<?php echo htmlspecialchars($date); ?>" />*</td>
                </tr>
                <tr>
                    <td>Điện thoại</td>
                    <td><input type="text" name="phone" value="<?php echo htmlspecialchars($phone); ?>" /></td>
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
    if ($sm != "") {
        // Kiểm tra từng điều kiện và gom lỗi lại
        if (!checkUserName($username))
            $error_message .= "Username: Chỉ cho phép: a-z, A-Z, số 0-9, ., _, - <br>";
        
        if (!checkPassword($password))
            $error_message .= "Mật khẩu: Cần >= 8 ký tự, có chữ Hoa, chữ thường và số (Cho phép ký tự đặc biệt).<br>";
        
        if (!checkEmail($email))
            $error_message .= "Email: Định dạng sai!<br>";
        
        if (!checkDateOfBirth($date))
            $error_message .= "Ngày sinh: Phải có dạng dd/mm/yyyy hoặc dd-mm-yyyy.<br>";
        
        if (!checkPhone($phone))
            $error_message .= "Số điện thoại: Chỉ được phép nhập số.<br>";

        // Hiển thị kết quả
        if ($error_message != "") {
            echo "<div class='info error'><b>Có lỗi xảy ra:</b><br>" . $error_message . "</div>";
        } else {
            echo "<div class='info success'><b>Đăng ký thành công!</b><br>Dữ liệu hợp lệ.</div>";
        }
    }
    ?>
</body>
</html>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tìm kiếm sản phẩm</title>
</head>
<body>
    <h1>Tim kiem san pham </h1>

    <form action="index.php" method="get">
        <label for="productName">Ten san pham</label>
        <input type="text" id="productName" name="productName" required>
        <br><br>

        <label for="searchType">Cach tim: </label>
        <input type="radio" id="exact" name="searchType" value ="Chinh xac" required>
        <label for="exact">Chinh xac </label>
        <input type="radio" id="fuzzy" name="searchType" value="Gan dung" required>
        <label for ="fuzzy">Gan dung</label>
        <br><br>

        <label for="productType">Loai san pham</label>
        <select id="productType" name="productType">
            <option value="tatca">Tat ca</option>
            <option value="loai1">Loai1</option>
            <option value="Loai2">Loai2</option>
            <option value="loai3">Loai3</option>

        </select>
        <br><br>

        <input type="submit" value="Tim Kiem">

    </form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $productName = isset($_GET['producName']) ? $_GET['productName'] :'';
    $searchType = isset($_GET['searchType']) ? $_GET['searchType'] :'';
    $productType = isset($_GET['producType']) ? $_GET['productType'] :'tatca';

    echo "<h2>Ket qua tim kiem:</h2>";
    echo "<p><strong>Ten san pham:</strong>". htmlspecialchars($productName) ."</p";
    echo "<p><strong>Cach tim</strong>". htmlspecialchars($searchType) ."</p>";

    if($productType != 'tatca'){
        echo "<p><strong>Loai san pham</strong>" .htmlspecialchars($productType)."</p>"

    }else{
        echo "<p><strong>Loai san pham: </strong>Tat ca</p>";
    }
}
?>
</body>
</html>
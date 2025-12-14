<?php
    // Cách 1: Dùng file_get_contents
    $url = 'https://vnexpress.net/the-thao';
    $content = file_get_contents($url);
    echo $content;
?>
----------------------------------------------------
<?php
    // Cách 2: Dùng cURL
    $url = 'https://vnexpress.net/the-thao';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $content = curl_exec($ch);
    curl_close($ch);
    echo $content;
?>
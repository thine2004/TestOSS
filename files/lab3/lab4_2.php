<?php
    $tong=0;
    $n=1;

    while($tong<=1000){
        $tong+=$n;
        $n++;
    }

    echo "N nhỏ nhất sao cho 1+2+...+n>1000 là: " . ($n) . "<br>";
    echo "Tổng là: " . $tong;
?>
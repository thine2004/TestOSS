<!-- Xuất n số nguyên tố đầu tiên -->
<?php
    function kiemtranguyento($x) {
        if ($x < 2) return false;
        if ($x == 2) return true;
        for ($i = 2; $i <= sqrt($x); $i++) {
            if ($x % $i == 0) return false;
        }
        return true;
    }
    function xuatSNTDauTien($n, $tt){
        if($n<$tt){
            echo "Số lượng không được lớn hơn giới hạn";
            return;
        }
        echo "$tt Số nguyên tố đầu tiên từ 1 đến $n: <br>";
        $dem=0;
        $i=2;
        while($dem<$n){
            if(kiemtranguyento($i)){
                if($dem<$tt){
                    echo $i . " ";
                }
                $dem++;
            }
            $i++;
        }
    }
    xuatSNTDauTien(20,5);
?>
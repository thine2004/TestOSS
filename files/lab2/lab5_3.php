<?php
$a=2;
$b=5;
$c=3;
$denta= $b*$b-4*$a*$c;
if($denta<0){
    echo " Phương trình vô nghiệm";
}else if($denta==0){
    echo "Phương trình có 1 nghiệm là:".$b/2*$a;
}else{
    echo "Phương trình có 2 nghiệm là " .-$b+sqrt($denta/2*$a)."va" .-$b-sqrt($denta/2*$a);
}
?>
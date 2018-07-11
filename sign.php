<?php

session_start();

//test
//$username = "ji33ff";

function file_sign($file,$prvkey,$filename)
{//进行数字签名

    $signpath = "/media/sf_sf_acfile/test/signpath/";

    if(!is_file($file))
    {
        echo "$file does not exist!\n";
        exit(1);
    }

    $data = file_get_contents($file);
    //数字签名-sha256,signature返回 签名signature
    openssl_sign($data,$signature,$prvkey,OPENSSL_ALGO_SHA256);
    //输出签名文件
    $out = $signpath.$filename.".sign";
    file_put_contents($out,$signature);
    echo"<br />"."签名OK SIGN";

    //用户验证签名所需签名文件路径入库
    include_once 'connect.php';
    $str="UPDATE fileinfo set signpath = '$out' WHERE chagname='$filename'";
    mysql_query($str)or die(mysql_error());
}
/*
include_once'connect.php';

$sql = mysql_query("SELECT * from fileinfo WHERE uplduser='$username'") or die(mysql_error());
$fileinfo = mysql_fetch_array($sql);
$chagname = $fileinfo["chagname"];
//print_r($gencfile);
$cphflink = $fileinfo["cphflink"];
//echo $enckey;

$file = $cphflink.$chagname;
//$file = "test.txt";

//取用户私钥
$sql = mysql_query("SELECT * from userinfo WHERE username='$username'") or die(mysql_error());
$userinfo = mysql_fetch_array($sql);
$prvkey = $userinfo["prvkey"];
*/

?>
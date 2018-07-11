<?php

function file_sign_v($file,$pblkey,$filename)
{//数字签名验证
    //查询当前内容
    include_once 'connect.php';
    $sql = mysql_query("SELECT * from fileinfo WHERE chagname='$filename'") or die(mysql_error());
    $result = mysql_fetch_array($sql);

    //$signpath = "/media/sf_sf_acfile/test/signpath/";
    //$file_sign = $signpath.$filename.".sign";
    $file_sign = $result["signpath"];
    $signature = $signature = file_get_contents($file_sign);
    if(!is_file($file)) {
        echo "data". $file ."does not exist!\n";
        exit(1);
    }
    if(!is_file($file_sign)) {
        echo "signature file" .$file ."does not exist!不存在！\n";
        exit(1);
    }
    $data = file_get_contents($file);
    $ok = openssl_verify($data, $signature, $pblkey, OPENSSL_ALGO_SHA256);
    if ($ok == 1)
    {
        echo "<br />"."验证签名valid", PHP_EOL;
    }
    elseif ($ok == 0)
    {
        echo "<br />"."invalid", PHP_EOL;
    }
    else
    {
        echo "<br />"."error: ".openssl_error_string();
    }
    return $ok;

}
/*
// read private and public key
$cwd      = dirname(__FILE__);
$pblkey  = openssl_pkey_get_public("file://$cwd/server.crt");
$signature = file_get_contents($file_sign);
//verify signature
*/

?>
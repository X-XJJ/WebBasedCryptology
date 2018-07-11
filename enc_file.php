<?php

//session_start();

//$username = $_SESSION['username'];
//$hpassword = MD5($_POST['pass']);

//  test
//$username = "ji33ff";

 function enc_f($o_data,$enckey,$chagname)
 {//用des-cbc模式进行文件加密
     include_once 'connect.php';

     if(!function_exists("hex2bin"))
     { // 检查函数“hex2bin”是否定义
         function hex2bin($data)
         {//将ascii转成十六进制
             return pack("H*", $data);
         }
     }
     //打开模块的算法和使用模式，tripledes，分组，最大密钥24位
     $td = mcrypt_module_open('tripledes','','cbc','');
    //随机创建初始化向量iv
     $iv_hex = mcrypt_create_iv(mcrypt_enc_get_iv_size($td),MCRYPT_DEV_RANDOM);
    //fileinfo用一项存iv
     $sql = "UPDATE fileinfo set iv = '$iv_hex' WHERE chagname = '$chagname'" ;
     mysql_query($sql)or die(mysql_error());
     //print '<p>IV ok!</p>';

     //进制转换  不论哪个要转换，保证加解密的流程能对称上
     $iv = hex2bin($iv_hex);
     /*
     $o_data_bin = hex2bin($o_data);//*无效
     echo"<br/>".var_dump($iv_hex);
     */

    //初始化缓冲区 用没有变过进制的初始iv
     mcrypt_generic_init($td,$enckey,$iv);//key mcrypt_enc_get_key_size($td)?$enckey
    //加密文件
     $enc_data = mcrypt_generic($td,$o_data);
     $enc_data_hex = bin2hex($enc_data);
    //清理缓冲区，停止加密模块
     mcrypt_generic_deinit($td);
    //关闭加密模块
     mcrypt_module_close($td);
     print_r("<br />"."初始iv:".bin2hex($iv_hex)."\n");
     print_r("<br />"."密文mi:".bin2hex($enc_data)."\n");
     */
     return $enc_data_hex;
 }
/*
//取8位对称密钥
include_once 'connect.php';

$sql = mysql_query("SELECT * from userinfo WHERE username='$username'") or die(mysql_error());
$genckey = mysql_fetch_array($sql);
//print_r($genckey);print_r($genckey[6]);//test
$enckey = $genckey["enckey"];
//echo $enckey;

*/
?>
<?php

/*
session_start();
$username = $_SESSION['username'];
//test
$username = "ji33ff";
*/

function dec_f($username,$fid)
{
    include_once'connect.php';

    if(!function_exists("hex2bin"))
    {// 检查函数“hex2bin”是否定义
        function hex2bin($data)
        {return pack("H*",$data);}
    }

    //取对称密钥
    $sql1 = mysql_query("SELECT * from userinfo WHERE username='$username' limit 1") or die(mysql_error());
    $userinfo = mysql_fetch_array($sql1);
    $enckey = $userinfo["enckey"];

    //取初始向量iv，密文路径，文件名
    $sql2 = mysql_query("SELECT * from fileinfo WHERE id=$fid") or die(mysql_error());
    $fileinfo = mysql_fetch_array($sql2);

    $iv_hex = $fileinfo["iv"];
    $cphflink = $fileinfo["cphflink"];
    $chagname = $fileinfo["chagname"];
    $filesize = $fileinfo["filesize"];

    $file_path = $cphflink.$chagname;

    if(!is_file($file_path))
    {
        echo "$file_path does not exist!\n";
        exit(1);
    }

    $n_data_hex = file_get_contents($file_path);

    //进制转换
    $n_data = hex2bin($n_data_hex);
    $iv = hex2bin($iv_hex);

    //打开模块的算法和使用模式，tripledes，分组，最大密钥24位
    $td = mcrypt_module_open('tripledes','','cbc','');
    //初始化缓冲区
    mcrypt_generic_init($td,$enckey,$iv);
    //解密文件
    $dec_data = mdecrypt_generic($td,$n_data);
    //清理缓冲区，停止加密模块
    mcrypt_generic_deinit($td);
    //关闭加密模块
    mcrypt_module_close($td);

    //删除之前插入的8个字符,解决文件第2-7字节解密问题
    $dec_data = substr($dec_data,8);

    $d_file_path = $fileinfo["cphflink"].$fileinfo["orgfname"];

    $myfile = fopen($d_file_path,"w") or die("UNable");
    fwrite($myfile,$dec_data);
    fclose($myfile);
    //去除文件末尾的零，但是文件第2-7字节解密还存在问题
    $myfile = fopen($d_file_path,"a+") or die("UNable");
    ftruncate($myfile,$filesize);
    fclose($myfile);

    return $d_file_path;
}



/*//test
$cphfpath = "cphfpath/";
$file = "test.txt";
$iv = "5176c918d260ab7e";
$enckey = 'jjjjjjjj';

fopen("test.txt");
*/


?>
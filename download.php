<?php

session_start();
include_once 'connect.php';

$fid=$_GET['id'];
$dwldpath="/media/sf_sf_acfile/test/download/";

$sql2="SELECT * from fileinfo WHERE id='$fid' ";
$result2 = mysql_query($sql2)or die(mysql_error());
$row2= mysql_fetch_array($result2);

$dwldnumb=$row2["dwldnumb"];
$cphfpath=$row2["cphflink"].$row2["chagname"];

//登录用户下载自己的文件才是明文
//if($_SESSION['username']==$row2["uplduser"]) 
	
if(isset($_SESSION['username'])
{//判断当前用户是否有下载明文权限
    include "dec_file.php";
    //解密文件
    $username = $row2["uplduser"];
    $cphfpath = dec_f($username,$fid);
}
else
{}

if(!file_exists($cphfpath))
{exit;}
else
{
    $file=fopen($cphfpath,"r");
    $file_size=filesize($cphfpath);

    if(filetype($file)=="text/plain")
    {
        header("Content-type:text/plain");
    }
    else  {
        header("Content-type:image/jpeg");
    }
    header("Accept-Ranges:bytes");
    header("Accept-Length:$file_size");
    header("Content-Disposition:attachment;filename=".$row2["orgfname"]);

     echo fread($file,filesize($cphfpath));
      $dwldnumb++;
    $str="UPDATE fileinfo set dwldnumb = $dwldnumb WHERE id = $fid";
    mysql_query($str)or die(mysql_error());
    fclose($file);

    if($_SESSION['username']==$row2['uplduser'])
    {unlink($row2["orgfname"],$file);}
    else {}

    exit;
}




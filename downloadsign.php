<?php

$dwldpath="/media/sf_sf_acfile/test/download/";

include 'connect.php';
$fid=$_GET['id'];
$sql2="SELECT * from fileinfo WHERE id='$fid' ";
$result2 = mysql_query($sql2)or die(mysql_error());
$row2= mysql_fetch_array($result2);

$signpath=$row2["signpath"];
$user=$row2["uplduser"];
$file=fopen($signpath,"r");

    header("Content-type:application/octet-stream");
    header("Accept-Ranges:bytes");
    header("Accept-Length:$file_size");
    header("Content-Disposition:attachment;filename=".$row2["orgfname"].".sign");
    //header("Content-Disposition:attachment;filename=".$dwldpath.$row2["orgfname"].".sign");
echo fread($file,filesize($signpath));
fclose($file);

include"verify_sign.php";
$file2="/home/qing/下载/".$row2["orgfname"].".sign";

$sql="SELECT * from userinfo WHERE username=$user ";
$result = mysql_query($sql)or die(mysql_error());
$row= mysql_fetch_array($result);

//下载数字签名验证
$vsign = file_sign_v($file2,$row["pblkey"],$row2["chagname"]);
if($vsign){
    exit('verify succeed!!<a href="javascript:history.back(-1);">back</a>');
}
var_dump($_SESSION);
$_SESSION["signed"]=true;
print "verify succeed!!!";

?>
<?php

//非持续地连接PHP后端与MySQL
//过程写在此文件内，其他地方在每次需要打开数据库时，使用include或include_once来调用并执行此文件

//link sql
$con = mysql_connect("localhost","root","qingkong") or die(mysql_errno());

//设置数据库字符集，防止中文存储为乱码
mysql_set_charset('utf8',$con);

if($con)
{
    //print '<p>link_sql OK</p>';
    //mysqli_close($link_sql);
}
else
{
    //print'<p>link_sql fail<br/>' . mysqli_error($con) . '</p>';
}

//select database
$select_db = mysql_select_db("qk", $con);
if($select_db)
{
    //print'<p>select_db OK</p>';
}
//else {print'<p>select_db fail<br/>' . mysqli_error($select_db) .'</p>';}

?>

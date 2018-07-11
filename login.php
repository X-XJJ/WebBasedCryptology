<?php

session_start();
header("Content-Type: text/html; charset=utf8");

$username = isset($_POST['username']) ? $_POST['username'] :"";
$password = isset($_POST['pass']) ? $_POST['pass'] :"";

/*test
$username = "111";
$password = "12345678";
*/

//login
include 'connect.php';

//check username & password_hash
$check_query = mysql_query("Select * From userinfo Where username = '$username' limit 1");
$result = mysql_fetch_array($check_query);

//验证用户口令匹配
if(password_verify($password,$result["hpassword"])!=false)
{
    echo'login succeed!!!<br>welcome '.$username;

    $_SESSION['username']=$username;
    print "<h2>Do you want to <a href='upload.html'>upload</a> file or <a href='listdownload.php'>download</a> file ?</h2>";
    //界面跳转
}
else
{
    echo'fial!!!no!失败！';
    exit('<a href="javascript:history.back(-1);">back</a>');
}

?>
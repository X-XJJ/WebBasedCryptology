<?php

header("Content-Type: text/html; charset=utf8");
session_start();

$username = isset($_POST['username']) ? $_POST['username'] :"";
$password = isset($_POST['pass']) ? $_POST['pass'] :"";
$pass_2nd = isset($_POST['re_pass']) ? $_POST['re_pass'] :"";
$logtime = time();

$_SESSION['username']=$username;

///*用户注册信息控制：1.用户名长度2.口令不为空3.口令长度4.弱口令
if($username == "" ||strlen($username)<3 ||strlen($username)>15)
{
    //var_dump($username);
    exit('error:illigel name <a href="javascript:history.back(-1);">back</a>');
}

if($password == ""||strlen($password)<=6 ||strlen($password)>=36){
    exit('error:illigel password <a href="javascript:history.back(-1);">back</a>');
}
if(!preg_match('/^\w*[a-zA-Z]+\w*$/', $password )){
    exit('error:weak password <a href="javascript:history.back(-1);">back</a>');
}

if($password !== $pass_2nd)
{
    exit('error:different password between two input <a href="javascript:history.back(-1);">back</a>');
}

$hpassword=password_hash($password,PASSWORD_BCRYPT);

//生成对称密钥
$salt = mcrypt_create_iv(16, MCRYPT_DEV_URANDOM);
$enckey = hash_pbkdf2("sha256",$hpassword,$salt,1024,24);

/*
//证书存放路径
//"file://"是前缀，文件路径还需要一个/开头  /var/www/qingkong/sf_sf_acfile/test
$prvkey = openssl_pkey_get_private("file:///var/www/qingkong/sf_sf_acfile/test/zhespath/ji33ff.key");
var_dump($prvkey);
$pblkey = openssl_pkey_get_public("file:///media/sf_sf_acfile/test/jj33ff.crt");
var_dump($prvkey);
*/

//私钥参数
$pk_config = array(
    'private_key_bits' => 2048,
    'private_key_type' => OPENSSL_KEYTYPE_RSA,
    'digest_alg' => 'sha256',
);
// 产生公私钥对
$res = openssl_pkey_new($pk_config);
//获取私钥private key
openssl_pkey_export($res, $pkeyout);
//echo($pkeyout);
//获取公钥public key
$pblkey = openssl_pkey_get_details($res);
$pblkey = $pblkey["key"];
//echo($pkeyout);
$prvkey = $pkeyout;
//生成证书
include "pb_pr_key.php";
pbpr_key($username,$res,$pkeyout);

/*  test
$username = "ji33ff";$password = "fewfrwetw";$logtime = null;
$pblkey="mei";$prvkey="zuo";$enckey='dao';
*/

//link sql
include 'connect.php';

//check the same username
$check_name = mysql_query("SELECT id from userinfo WHERE username='$username'");
if(mysql_fetch_array($check_name))
{
    echo "this username already exist";
    exit('<a href="javascript:history.back(-1);">back</a>');
}
else
{//write in sql
    $sql = "INSERT INTO userinfo (id,username,hpassword,logtime,pblkey,prvkey,enckey)
            VALUES(0,'$username','$hpassword','$logtime','$pblkey','$prvkey','$enckey')";
}

if(mysql_query($sql))
{

    print '<p>regist succeed!!!</p>';
    //页面跳转
    echo "welcome ".$username;
    $_SESSION['username']=$username;
    print "<h2>Do you want to <a href='upload.html'>upload</a> file? </h2> ";

}
else
{print'<p>fail,no!</p>';}

?>


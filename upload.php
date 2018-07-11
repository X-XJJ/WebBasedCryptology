<?php

session_start();
header("Content-Type: text/html; charset=utf8");

//var_dump($_FILES);
$uplduser=$_SESSION['username'];

//$hpass=password_hash($_POST['pass'],PASSWORD_BCRYPT);

$cphfpath="/media/sf_sf_acfile/test/cphfpath/";
$dwldnumb=0;
$crattime=time();

//echo $_SESSION['username'];

include_once 'connect.php';
//limit 1
$check_query = mysql_query("Select * From userinfo Where username = '$uplduser'  ");
$result = mysql_fetch_array($check_query);
var_dump($uplduser);
if(password_verify($_POST['pass'],$result["hpassword"]) == false)
{
    exit('error:no register <a href="javascript:history.back(-1);">back</a>');
}
else{
	print('verify succeed!!<br>');
}
//doc文件类型:application/msword
//文件类型控制 文件大小控制
$file_type=$_FILES['uploadfile']['type'];
var_dump($file_type);
if($file_type!='image/jpeg'&& $file_type!='image/pjpeg'&& $file_type!='text/plain'){
    echo "文件类型只能是 jpg,txt的";
    exit('<a href="javascript:history.back(-1);">back</a>');
}

$file_size=$_FILES['uploadfile']['size'];
if($file_size>10*1024*1024)
{
    echo "file too big!! more than 10MB";
    exit('<a href="javascript:history.back(-1);">back</a>');
}
else echo "file size OK<br>";

if($_FILES['uploadfile']['error']==0)
{
    $upldfile=$_FILES['uploadfile'];
    $orgfname=$upldfile['name'];

    echo "Uploadfilename: " . $_FILES["uploadfile"]["name"] . "<br />";
    echo "Type: " . $_FILES["uploadfile"]["type"] . "<br />";
    echo "Size: " . ($_FILES["uploadfile"]["size"] / 1024) . " Kb<br />";
    echo "Temp file: " . $_FILES["uploadfile"]["tmp_name"] . "<br />";

    $chagname=time().rand(1,1000).substr ($orgfname,strrpos ($orgfname,"."));

    include_once 'connect.php';

    $sql = "INSERT INTO fileinfo (id,uplduser,orgfname,chagname,cphflink,dwldnumb,crattime)
            VALUES(0,'$uplduser','$orgfname','$chagname','$cphfpath','$dwldnumb','$crattime')";

    if(mysql_query($sql))
    {
        //加密文件
        include "enc_file.php";

        if (file_exists( $cphfpath.$chagname))
        {
            echo $chagname . " already exists. <br />";
        }
        else
        {
            //取文件大小入库
            $filesize_str = "UPDATE fileinfo set filesize = '$file_size' WHERE chagname='$chagname'";
            mysql_query($filesize_str)or die(mysql_error());

            //取文件内容
            $o_data = file_get_contents($_FILES["uploadfile"]["tmp_name"]);
            //插入8位数字（解密恢复时删除）解决文件第2-7字节解密问题
            $in = "00000000";
            $o_data = $in.$o_data;
            //得到密文(十六进制内容)
            $n_data=enc_f($o_data,$result["enckey"],$chagname);
            //写入密文,w只写
            $myfile = fopen($_FILES["uploadfile"]["tmp_name"],"w");
            fwrite($myfile,$n_data);
            fclose($myfile);

            $move = move_uploaded_file($_FILES["uploadfile"]["tmp_name"], $cphfpath.$chagname);
            if($move!=false){
                echo "<br />"."Stored in: " . $cphfpath.$chagname;
            }

            //数字签名
            $file = $cphfpath.$chagname;
            include"sign.php";
            file_sign($file,$result["prvkey"],$chagname);
                 echo "<br/>sign succeed!!";

            //数字签名验证
            include"verify_sign.php";
            $vsign = file_sign_v($file,$result["pblkey"],$chagname);

            //生成密文文件散列值,匿名用户可用hash_file不带密钥,hash_hmac_file带密钥
            include_once 'connect.php';
            $hash = hash_file("md5",$file,TRUE);
            //var_dump($hash);
            $hash_str="UPDATE fileinfo set hash = '$hash' WHERE chagname='$chagname'";
            mysql_query($hash_str)or die(mysql_error());
            //echo"<br />"."hash OK";

        }

        print '<p>upload succeed!!</p>';
        //页面跳转

        print "<h2>Do you want to <a href='upload.html'>upload</a>  file again?
            <br>or <a href='listdownload.php'>download</a> file?</h2>";

    }
    else
    {print'<p>fail,no!</p>';}

    exit('<a href="javascript:history.back(-1);">back</a>');

}

else
{
    echo "erroe!!Return Code: " . $_FILES["uploadfile"]["error"] . "<br />";
    echo "Invalid file";
}

?>
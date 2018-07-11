<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>download</title>
</head>
<body>
<h1 align="center">download</h1><br><br>
<h2 align="center" >
    <br>
    <?php

    session_start();
    include 'connect.php';

    mysql_select_db("qk");
    $sql="SELECT * from fileinfo ";
    $result = mysql_query($sql) or die(mysql_error());

    while ($row= mysql_fetch_array($result))
    {
        $line = sprintf("<br/><a href=\"download.php?id=%s\">%s   </a>", $row["id"], $row["orgfname"]);//chagname
        $line2 = sprintf("<a href=\"downloadsign.php?id=%s\">%s</a>",$row["id"],$row["orgfname"].".sign");
        echo $line;
        echo $line2;
    }

   // include"verify_sign.php";
   // $vsign = file_sign_v($file,$result["pblkey"],$chagname);

var_dump($_SESSION);
   if($_SESSION["signed"]==true)
    {
        print "verify succeed!!!";
    }
    ?>
    <br>

</h2>
</body>
</html>



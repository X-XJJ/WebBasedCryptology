<?php

//session_start();
//$username = $_SESSION['username'];

function pbpr_key($username,$res,$pkeyout)
{
    $zhespath = "/media/sf_sf_acfile/test/zhespath/";
    //最终使用的公钥证书中可以被查看的Distinguished Name（DN）信息
    $dn = array(
        "countryName" => "CN",
        "stateOrProvinceName" => "Beijing",
        "localityName" => "Chaoyang",
        "organizationName" => "CUC",
        "organizationalUnitName" => "CS",
        "commonName" => "qingkong.ac",  // https应用和使用的站点域名匹配
        "emailAddress" => $username."@..."
    );
    //保存 server.key
//openssl_pkey_export($res, $pkeyout);
    file_put_contents($zhespath.$username.".key", $pkeyout);

// 制作CSR请求文件
    $csr = openssl_csr_new($dn, $res, $pk_config);
// 对CSR文件进行自签名(第2个参数设置为null,否则可以设置为CA的证书路径),证书有效期3650天
    $sscert = openssl_csr_sign($csr, null, $res, 3650, $pk_config);

//openssl_pkey_free($privkey);//不用则放
    openssl_csr_export($csr, $csrout);
// 查看生成的server.csr的内容
// 验证生成的server.csr格式是否合法
    file_put_contents($zhespath.$username.".csr", $csrout);

    openssl_x509_export($sscert, $certout);
// 查看生成的server.crt的内容
    file_put_contents($zhespath.$username.".crt", $certout);
//openssl_x509_export_to_file($sscert, "haha.cert");
}

/*
$pk_config = array(
    'private_key_bits' => 2048,
    'private_key_type' => OPENSSL_KEYTYPE_RSA,
    'digest_alg' => 'sha256',
);

// 产生公私钥对
$res = openssl_pkey_new($pk_config);
*/


?>
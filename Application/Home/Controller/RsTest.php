<?php
//require_once("qiniu/rs.php");
require_once("/www/web/Application/Home/SDK/qiniu/qiniu/rs.php");
/*accessKey：
Ooc3G1APJIOxMixKio1nfOFUgi4_JvTG-Zb9f407
secretKey:
XNkVhsIoJ7oxCCq82bKQz3Gsor2iXgdx-5NWNgVo
bucket:
grassuz
*/
$bucket = 'grassuz';
$accessKey = 'Ooc3G1APJIOxMixKio1nfOFUgi4_JvTG-Zb9f407';
$secretKey = 'XNkVhsIoJ7oxCCq82bKQz3Gsor2iXgdx-5NWNgVo';

Qiniu_SetKeys($accessKey, $secretKey);
$putPolicy = new Qiniu_RS_PutPolicy($bucket);
$upToken = $putPolicy->Token(null);
var_dump($upToken);

<?php
require_once("qiniu/rs.php");
$bucket = 'grassuz';
$accessKey = 'Ooc3G1APJIOxMixKio1nfOFUgi4_JvTG-Zb9f407';
$secretKey = 'XNkVhsIoJ7oxCCq82bKQz3Gsor2iXgdx-5NWNgVo';

Qiniu_SetKeys($accessKey, $secretKey);
$putPolicy = new Qiniu_RS_PutPolicy($bucket);
$upToken = $putPolicy->Token(null);
$data = array();
$data['result']=0;
$data['msg']="";
$data['data'] = array("token"=>$upToken);
exit(json_encode($data));

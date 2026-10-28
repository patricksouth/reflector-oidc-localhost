<?php 
require_once '../classes/JwtBuilder.php';
$pem=file_get_contents('../../../keys/private.pem');
$claims=json_decode($_POST['claims']??'{}',true)?:[];
$jwt=JwtBuilder::generate($claims,$pem);
echo '<h2>JWT</h2>
    <textarea rows=10 cols=120>'.htmlspecialchars($jwt).'</textarea>';
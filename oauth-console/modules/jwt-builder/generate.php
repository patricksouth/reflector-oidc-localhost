<?php 

require 'JwtBuilder.php'; 

$pem=@file_get_contents('../../keys/private.pem'); 

if(!$pem){
    die('missing ../../keys/private.pem');
} 

$claims=[
    'iss'=>$_POST['iss']??'client',
    'sub'=>$_POST['sub']??'client','aud'=>$_POST['aud']??'https://example.org/token'
    'iat'=>time(),
    'exp'=>time()+300,
    'jti'=>bin2hex(random_bytes(8))
]; 

echo '<pre>'.htmlspecialchars(JwtBuilder::create($claims,$pem)).'</pre>';
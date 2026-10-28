<?php 
class JwtVerifier{
    static function verify($jwt,$pub){
        [$h,$p,$s]=explode('.',$jwt);
        $sig=base64_decode(strtr($s,'-_','+/'));
        return openssl_verify("$h.$p",$sig,$pub,OPENSSL_ALGO_SHA256)===1;
    }
}
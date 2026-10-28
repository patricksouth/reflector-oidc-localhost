<?php 

class JwtBuilder {
    static function b64($d){
        return rtrim(strtr(base64_encode($d),"+/","-_") ,"=");
    }

    static function create($claims,$pem) {
    $h=["alg"=>"RS256","typ"=>"JWT"];
    $h64=self::b64(json_encode($h));
    $p64=self::b64(json_encode($claims));
    $data=$h64.".".$p64;
    openssl_sign($data,$sig,$pem,OPENSSL_ALGO_SHA256);
    return $data.".".self::b64($sig);
    } 
}
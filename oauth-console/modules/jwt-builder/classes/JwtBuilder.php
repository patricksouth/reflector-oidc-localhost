<?php
class JwtBuilder {
    public static function b64($d){
        return rtrim(strtr(base64_encode($d),'+/','-_'),'=');
    }
    public static function generate(array $claims,string $privatePem){
        $header=['alg'=>'RS256','typ'=>'JWT'];
        $claims['iat']=$claims['iat']??time();
        $claims['exp']=$claims['exp']??time()+300;
        $claims['jti']=$claims['jti']??bin2hex(random_bytes(8));
        $h=self::b64(json_encode($header));
        $p=self::b64(json_encode($claims));
        $data="$h.$p";
        openssl_sign($data,$sig,$privatePem,OPENSSL_ALGO_SHA256);
        return $data.'.'.self::b64($sig);
    }
}

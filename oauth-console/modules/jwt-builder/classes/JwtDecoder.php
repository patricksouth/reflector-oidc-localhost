<?php 
class JwtDecoder{
    static function decode($jwt){
        [$h,$p,$s]=explode('.',$jwt);
        return [
            'header'=>json_decode(base64_decode(strtr($h,'-_','+/')),true),
            'payload'=>json_decode(base64_decode(strtr($p,'-_','+/')),true)
        ];
    }
}
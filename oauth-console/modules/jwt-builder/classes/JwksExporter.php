<?php 
class JwksExporter{
    static function export(){
        return ['keys'=>[['kty'=>'RSA','kid'=>'local-key','alg'=>'RS256']]];
    }
}
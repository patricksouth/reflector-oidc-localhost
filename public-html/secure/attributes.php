<!DOCTYPE html>
<html lang="en">
<head>
</head>
<style>
  table, th, tr, td {
    border: 1px solid black;
    border-collapse: collapse;
    padding: 3px;
    text-align: left;
    vertical-align: top;
  }
  td {
    max-width: 800px;
    word-wrap: break-word;
  }
  .novalue {
    font-weight: bold;
    color: red;
  }
  .token {
    font-weight: bold;
    color: limegreen;
  }
</style>
<body>
<h1>OIDC - Attribute Reflector</h1>
<a href="../public/attributes.php"><b>Access the unprotected page</b></a> <br><br>

<table> <tr> <th>Attributes</th> <th>Values</th> </tr>

<?php error_reporting(0);

$list = [
  'REMOTE_USER',
  'OIDC_CLAIM_affiliation',
  'OIDC_CLAIM_email',
  'OIDC_CLAIM_eppn',
  'OIDC_CLAIM_eptid',
  'OIDC_CLAIM_family_name',
  'OIDC_CLAIM_given_name',
  'OIDC_CLAIM_name',
  'OIDC_CLAIM_idp',
  'OIDC_CLAIM_idp_name',
  'OIDC_CLAIM_isMemberOf',
  'OIDC_access_token',
  'OIDC_CLAIM_sub',
  'OIDC_CLAIM_iss',
  'OIDC_CLAIM_jti',
  'OIDC_CLAIM_nonce',
  'OIDC_CLAIM_auth_time',
  'OIDC_CLAIM_exp',
  'OIDC_access_token_expires',
  'HTTPS',
  'OIDC_CLAIM_acr',
  'OIDC_CLAIM_aud',
  'SSL_TLS_SNI',
  'OIDC_CLAIM_cert_subject_dn',
  'OIDC_CLAIM_voPersonID',
  'OIDC_CLAIM_groups',
  'OIDC_CLAIM_uid'
];

function isMemberOf_list ($val) {
  array_walk($val, function(&$item, $idx) {
    echo $item . "<br>";
  } );
}
// Prints the above claims with their values.

function token_explode($val, $type) {
  print ("<table> <tr> <th>$type</th> <th>Values</th> </tr>");
  array_walk($val, function(&$item ,$idx) {
    if ( is_array($item) ) {
      print ("<tr><td>" . $idx . "</td><td>");
      token_explode($item, $idx);
      print ("</td></tr>");
    } else {
      print ("<tr><td>" . $idx . "</td><td>" . $item . "</td></tr>");
    }
  } );
  print ("</table>");
}

foreach ($list as $claim) {
  print ("<tr><td>" . $claim . "</td><td>");
  if ( is_null($_SERVER[$claim]) ) {
    print ("<div class='novalue'>no value</div>");

  } else {
    if ( $claim == "OIDC_CLAIM_isMemberOf" )  {
      $isMemberOf_split = preg_split('/(\,)/', $_SERVER['OIDC_CLAIM_isMemberOf'], -1,);
      token_explode($isMemberOf_split, "GROUPS");
//      isMemberOf_list ($isMemberOf_split);

    } elseif ( $claim == "OIDC_access_token" ) {
        print ("<div class='token'>TOKEN</div>" );
        print ($_SERVER['OIDC_access_token'] . "<br>"); 

        if ( ! is_null($_SERVER['OIDC_access_token']) ) {
          $token1=json_decode(base64_decode(str_replace('_', '/', str_replace('-','+',explode('.', $_SERVER['OIDC_access_token'])[1]))));
          $token0=json_decode(base64_decode(str_replace('_', '/', str_replace('-','+',explode('.', $_SERVER['OIDC_access_token'])[0]))));

          print ("<br>");
          print ("<div class='token'>DECODED TOKEN</strong> </div>");

          if ( ! is_null($token0) ) {
            token_explode($token0, "Token Artefacts[HEADER]");
          } else {
            print ("NOT a decodeable Access Token");
          }

          if ( ! is_null($token1) ) {
            print ("<br>");
            token_explode($token1, "Token Artefacts[DATA]");
          } else {
            print ("<br>" . "NOT a JWT....");
          }
        }

    } else {
      print ($_SERVER[$claim]);
    }
  }
  print ("</td></tr>");
}
?>
</table>

<!-- Prints all info -->
<?php
 phpinfo(INFO_VARIABLES);
?>
<!-- -->
</body>
</html>


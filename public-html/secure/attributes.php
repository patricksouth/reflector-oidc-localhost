<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="stylesheet" href="/styles.css">
</head>
<body>
<h2>OIDC - Claim Reflector</h2>
<table> <tr> <th>Claims</th> <th>Values</th> </tr>

<?php error_reporting(0);
// Select the claims to print from the array.
$list = [
  'REMOTE_USER',
  'OIDC_CLAIM_sub',
  'OIDC_CLAIM_affiliation',
  'OIDC_CLAIM_email',
  'OIDC_CLAIM_emailverified',
  'OIDC_CLAIM_eppn',
  'OIDC_CLAIM_eduPersonAssurance',
  'OIDC_CLAIM_family_name',
  'OIDC_CLAIM_given_name',
  'OIDC_CLAIM_name',
  'OIDC_CLAIM_isMemberOf',
  'OIDC_CLAIM_groups',
  'OIDC_CLAIM_organization_name',
  'OIDC_CLAIM_subject-id',
#  'OIDC_CLAIM_groups',
#  'OIDC_access_token',
#  'OIDC_CLAIM_iss',
#  'OIDC_CLAIM_jti',
#  'OIDC_CLAIM_nonce',
#  'OIDC_CLAIM_auth_time',
#  'OIDC_CLAIM_exp',
#  'OIDC_access_token_expires',
  'OIDC_CLAIM_acr',
  'OIDC_CLAIM_aud',
#  'SSL_TLS_SNI',
#  'OIDC_CLAIM_cert_subject_dn',
#  'OIDC_CLAIM_voPersonID',
#  'OIDC_CLAIM_uid',
  'OIDC_CLAIM_terms_and_conditions',
  'OIDC_CLAIM_training_courses',
  'OIDC_userinfo_json'
];

function isMemberOf_list ($val) {
  array_walk($val, function(&$item, $idx) {
    echo $item . "<br>";
  } );
}

function token_explode($val, $type, $values) {
  print ("<table> <tr> <th>$type</th> <th>$values</th> </tr>");
  array_walk($val, function(&$item ,$idx) {
    if ( is_array($item) ) {
      print ("<tr><td>" . $idx . "</td><td>");
      token_explode($item, $idx, $values);
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
      print ("<div class='token'>Group Claims</div>" );      
      $isMemberOf_split = preg_split('/(\,)/', $_SERVER['OIDC_CLAIM_isMemberOf'], -1,);
      token_explode($isMemberOf_split, "Count", "Groups");

    } elseif ( $claim == "OIDC_CLAIM_groups" )  {
      print ("<div class='token'>Group Claims</div>" );      
      $groups_split = preg_split('/(\,)/', $_SERVER['OIDC_CLAIM_groups'], -1,);
      token_explode($groups_split, "Count", "Groups");

    } elseif ( $claim == "OIDC_access_token" ) {
      print ("<div class='token'>TOKEN</div>" );
      print ($_SERVER['OIDC_access_token'] . "<br>"); 

      if ( ! is_null($_SERVER['OIDC_access_token']) ) {
        $token1=json_decode(base64_decode(str_replace('_', '/', str_replace('-','+',explode('.', $_SERVER['OIDC_access_token'])[1]))));
        $token0=json_decode(base64_decode(str_replace('_', '/', str_replace('-','+',explode('.', $_SERVER['OIDC_access_token'])[0]))));

        print ("<br>");
        print ("<div class='token'>DECODED TOKEN</strong> </div>");

        if ( ! is_null($token0) ) {
          token_explode($token0, "Token Artefacts[HEADER]", "Values");
        } else {
          print ("NOT a decodeable Access Token");
        }

        if ( ! is_null($token1) ) {
          print ("<br>");
          token_explode($token1, "Token Artefacts[DATA]", "Values");
        } else {
          print ("<br>" . "NOT a JWT....");
        }
      }
    } elseif ($claim == "OIDC_CLAIM_terms_and_conditions") {
    // Extract Terms and conditions claims from "OIDC_userinfo_json".
       $claim_tcs=$_SERVER["OIDC_userinfo_json"];	
       if ( ! is_null($claim_tcs) ) {
          $userinfo=json_decode($claim_tcs);
          $tcs = ($userinfo->terms_and_conditions);
          print ("<div class='token'>Terms and Conditions</div>" );
          print ("<table><th>Policy</th><th>Agreed</th>");
          foreach ($tcs as $item) {
            print ("<tr><td>" . $item->name . "</td><td>");
            if ($item->agreed) {print "TRUE";} else {print "FALSE";}
            print ("</td></tr>");
          }
	  print ("</table>");
	  print ("<br>Extracted from claim <b>OIDC_userinfo_json</b>");
        }  else {
          print ("No Terms and Conditions Available");
	}
    } elseif ($claim == "OIDC_userinfo_json") {
      if (! is_null($_SERVER["OIDC_userinfo_json"])) {
        print ("<pre>");
	print (json_encode(json_decode($_SERVER["OIDC_userinfo_json"]), JSON_PRETTY_PRINT));
        print ("</pre>");
      } else {
        print ("<div class='novalue'>no value</div>");
	print("No OIDC_userinfo_json");
      }
    } else {
      print ($_SERVER[$claim]);
    }
  }
  print ("</td></tr>");
}
print ("</table>");
?>

<a href="/secure/redirect_uri?logout=https://localhost:8443"><b>Logout</b></a> </td>

<!-- Prints all info
<?php
 phpinfo(INFO_VARIABLES);
?>
 -->
</body>
</html>


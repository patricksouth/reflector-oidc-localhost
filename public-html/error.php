<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="/styles.css">
</head>
<body>
<h2>OIDC - Attribute Reflector</h2>
<table> <tr> <th>Attributes</th> <th>Values</th> </tr>

<?php error_reporting(0);

$list = [
  'REDIRECT_OIDC_ERROR',
  'REDIRECT_OIDC_ERROR_DESC'
];

foreach ($list as $claim) {
  print ("<tr><td>" . $claim . "</td><td>");
  if ( is_null($_SERVER[$claim]) ) {
    print ("<div class='novalue'>no value</div>");

  } else {
      print ($_SERVER[$claim]);
  }

   print ("</td></tr>");
}
?>
</table>

</body>
</html>

<!--
<?php
 phpinfo(INFO_VARIABLES);
?>
-->

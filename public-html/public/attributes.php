<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="stylesheet" href="/styles.css">
</head>
<body>
<h1>Attribute Reflector - OIDC</h1>
<a href="../secure/attributes.php"><b>Sign In and Access the Attribute Reflector</b></a> <br><br>

<table> <tbody> <tr> <th>Claims</th> <th>Values</th> </tr>
<?php error_reporting(0);
$claim = 'HTTPS';
print ("<tr> <td>" . $claim . "</td>");
print ("<td>" . $_SERVER[$claim] . "</td> </tr>") ;

print ("<tr><td>" . "OIDC ERROR" . "</td><td>");
print ($_SERVER['REDIRECT_OIDC_ERROR']) . "</td></tr>";

print ("<tr><td>" . "OIDC ERROR DESC" . "</td><td>");
print ($_SERVER['REDIRECT_OIDC_ERROR_DESC']);
print ("</td></tr>");

print ("<tr><td>" . "REDIRECT OIDC AUTHZ ERROR" . "</td><td>");
print ($_SERVER['REDIRECT_OIDC_AUTHZ_ERROR']);
print ("</td></tr>");

print ("<tr><td>" . "REDIRECT STATUS" . "</td><td>");
print ($_SERVER['REDIRECT_STATUS']);
print ("</td></tr>");

print ("<tr><td>" . "REDIRECT REMOTE USER" . "</td><td>");
print ($_SERVER['REDIRECT_REMOTE_USER']);
print ("</td></tr>");
?>
</tbody> </table>

</body>
</html>

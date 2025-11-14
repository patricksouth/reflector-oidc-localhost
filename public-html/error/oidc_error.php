<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="stylesheet" href="/styles.css">
</head>
<body>
<h1>oidc_error </h1>
<a href="../secure/attributes.php"><b>Sign In</b></a> <br><br>

<table> <tr> <th>Claims</th> <th>Values</th> </tr>
<?php error_reporting(0);
print ("<tr><td>" . "OIDC_ERROR" . "</td><td>");
print ($_SERVER['REDIRECT_OIDC_ERROR']) . "</td></tr>";

print ("<tr><td>" . "OIDC_ERROR_DESC" . "</td><td>");
print ($_SERVER['REDIRECT_OIDC_ERROR_DESC']);
print ("</td></tr>");

?>
<!-- 
<?php
 phpinfo(INFO_VARIABLES);
?>
 -->

</body>
</html>


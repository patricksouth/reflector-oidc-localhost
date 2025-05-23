<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="stylesheet" href="../public/style.css">
</head>
<body>
<h1>Attribute Reflector - OIDC</h1>
<a href="../secure/attributes.php"><b>Login and Access the Attribute Reflector page</b></a> <br><br>

<table> <tbody>
<tr> <th>Attributes</th> <th>Values</th> </tr>

<?php error_reporting(0);
  $claim = 'HTTPS';
  print ("<tr> <td>" . $claim . "</td>");
  print ("<td>" . $_SERVER[$claim] . "</td> </tr>") ;
?>

</tbody> </table>

</body>
</html>

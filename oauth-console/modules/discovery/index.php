<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$issuer = '';
$obj = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $issuer = trim($_POST['issuer'] ?? '');

    if (!empty($issuer) && strpos($issuer, 'http') !== 0) {
        $issuer = 'https://' . $issuer;
    }

    if (!empty($issuer)) {
        $url = rtrim($issuer, '/') . '/.well-known/openid-configuration';

        $json = @file_get_contents($url);

        if ($json === false) {
            $error = 'Unable to retrieve discovery document.';
        } else {
            $obj = json_decode($json, true);

            if (!is_array($obj)) {
                $error = 'Unable to parse discovery document.';
                $obj = null;
            }
        }
    }
}

$endpoints = [
    'Issuer' => 'issuer',
    'Authorization Endpoint' => 'authorization_endpoint',
    'Token Endpoint' => 'token_endpoint',
    'UserInfo Endpoint' => 'userinfo_endpoint',
    'JWKS URI' => 'jwks_uri'
];
?>
<!doctype html>
<html>
<head>
    <title>OIDC Discovery</title>
    <link rel="stylesheet" href="../../assets/discovery.css">
</head>
<body>

<h1>OIDC Discovery Viewer</h1>

<form method="post">
    <label>Issuer URL</label>
    <input
        type="text"
        name="issuer"
        size="80"
        value="<?= htmlspecialchars($issuer) ?>"
        placeholder="https://test.cilogon.aaf.edu.au">

    <button type="submit">Fetch Metadata</button>

    <p>
        <strong>Note:</strong>
        Enter only the issuer URL. Do not include
        <code>/.well-known/openid-configuration</code>
    </p>
</form>

<?php if ($error): ?>
<div class="error">
    <?= htmlspecialchars($error) ?>
</div>
<?php endif; ?>

<?php if ($obj): ?>

<h2>OIDC Provider Summary</h2>
<table border="1" cellpadding="5">
<tr>
    <th>Property</th>
    <th>Value</th>
    <th>Action</th>
</tr>

<?php foreach ($endpoints as $name => $endpoint): ?>
<tr>
    <td><?= htmlspecialchars($name) ?></td>
    <td><?= htmlspecialchars($obj[$endpoint] ?? '') ?></td>
    <td>
    <?php if ($endpoint === 'jwks_uri' && !empty($obj[$endpoint])): ?>
        <a class="button-jwks"
           href="../jwks-viewer/index.php?uri=<?= urlencode($obj[$endpoint]) ?>">
           View JWKS
        </a>
    <?php endif; ?>
    </td>
</tr>
<?php endforeach; ?>
</table>

<h2>Supported Scopes</h2>
<?php if (!empty($obj['scopes_supported'])): ?>
<ul>
<?php foreach ($obj['scopes_supported'] as $scope): ?>
<li><?= htmlspecialchars($scope) ?></li>
<?php endforeach; ?>
</ul>
<?php endif; ?>

<h2>Supported OAuth Grant Types</h2>
<?php if (!empty($obj['grant_types_supported'])): ?>
<ul>
<?php foreach ($obj['grant_types_supported'] as $grant): ?>
<li><?= htmlspecialchars($grant) ?></li>
<?php endforeach; ?>
</ul>
<?php endif; ?>

<h2>Client Authentication Methods</h2>
<ul>
<?php foreach (($obj['token_endpoint_auth_methods_supported'] ?? []) as $method): ?>
<li><?= htmlspecialchars($method) ?></li>
<?php endforeach; ?>
</ul>

<h2>ID Token Signing Algorithms</h2>
<ul>
<?php foreach (($obj['id_token_signing_alg_values_supported'] ?? []) as $alg): ?>
<li><?= htmlspecialchars($alg) ?></li>
<?php endforeach; ?>
</ul>

<h2>Analysis</h2>
<ul>
<li><?= isset($obj['authorization_endpoint']) ? '✅ Supports OAuth/OIDC Authorisation Requests' : '❌ No authorisation endpoint detected' ?></li>
<li><?= isset($obj['token_endpoint']) ? '✅ Supports OAuth Token Requests' : '❌ No token endpoint detected' ?></li>
<li><?= !empty($obj['token_endpoint_auth_methods_supported']) ? '✅ Advertises Client Authentication Methods' : '❌ No Client Authentication Methods Advertised' ?></li>
<li><?= isset($obj['jwks_uri']) ? '✅ Publishes Signing Keys' : '❌ No JWKS URI Published' ?></li>
</ul>

<h2>Raw Discovery Document</h2>
<pre><?= htmlspecialchars(json_encode($obj, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)) ?></pre>

<?php endif; ?>

</body>
</html>

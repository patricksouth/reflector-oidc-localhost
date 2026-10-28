<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$modules = [
    'Discovery'                => 'modules/discovery/',
    'JWT Builder'              => 'modules/jwt-builder/',
    'JWT Verifier'             => 'modules/jwt-verifier/',
    'JWKS Generator'           => 'modules/jwks-generator/',
    'client_secret_post'       => 'modules/client-secret-post/',
    'client_secret_basic'      => 'modules/client-secret-basic/',
    'private_key_jwt'          => 'modules/private-key-jwt/',
    'JWT Authorization Grant'  => 'modules/jwt-authorization-grant/',
    'mTLS'                     => 'modules/mtls/',
    'Certificate Inspector'    => 'modules/certificate-inspector/',
    'OAuth Request Builder'    => 'modules/oauth-request-builder/',
    'Token Viewer'             => 'modules/token-viewer/',
    'mod_auth_oidc Debug'      => 'modules/mod-auth-oidc-debugger/'
];

?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="styles.css">
<title>OAuth/OIDC Interoperability Test Console</title>
</head>
<body>

<h1>OAuth/OIDC Interoperability Test Console</h1>

<p>
A learning environment for OAuth 2.0, OpenID Connect, JWT, <br>
RFC 7523, mTLS and mod_auth_oidc.
</p>



<h2>Modules</h2>
<div class="module-grid">
    <?php foreach ($modules as $name => $url): ?>
        <a class="module-card"
        href="<?= htmlspecialchars($url) ?>">
            <h3><?= htmlspecialchars($name) ?></h3>
        </a>
    <?php endforeach; ?>
</div>
<hr>

<!--
<ul>
    <?php foreach ($modules as $name => $url): ?>
        <li class="module">
            <a href="<?= htmlspecialchars($url) ?>">
                <?= htmlspecialchars($name) ?>
            </a>    

        </li>

    <?php endforeach; ?>
</ul>
-->
<h2>Environment</h2>

<ul>
    <li>PHP Version: <?= phpversion() ?> </li>
    <li>OpenSSL:
        <?= extension_loaded('openssl')
                ? 'Installed'
                : 'Missing' ?>
    </li>
    <li>
    cURL:
        <?= extension_loaded('curl')
                ? 'Installed'
                : 'Missing' ?>
    </li>
    <li>
    JSON:
        <?= extension_loaded('json')
                ? 'Installed'
                : 'Missing' ?>
    </li>
</ul>
<h2>Statistics</h2>

<ul>

<li>
Configured Modules:
<?= count($modules) ?>
</li>

</ul>

<li>Document Root:
<?= $_SERVER['DOCUMENT_ROOT'] ?>
</li>

<li>Server Software:
<?= $_SERVER['SERVER_SOFTWARE'] ?>
</li>

<h2>JWT Keys</h2>
<?php
$privateKey = file_exists('keys/private.pem');
$publicKey = file_exists('keys/public.pem');
?>
<ul>

<li>
Private Key:
<?= $privateKey ? '✅ Found' : '❌ Missing' ?>
</li>

<li>
Public Key:
<?= $publicKey ? '✅ Found' : '❌ Missing' ?>
</li>
</ul>


<h2>mTLS Certificates</h2>
<?php
$clientCert = file_exists('certs/client.crt');
$clientKey = file_exists('certs/client.key');
$caCert = file_exists('certs/ca.pem');
?>
<ul>
<li>
client.crt:
<?= $clientCert ? '✅ Found' : '❌ Missing' ?>
</li>
<li>
client.key:
<?= $clientKey ? '✅ Found' : '❌ Missing' ?>
</li>
<li>
ca.pem:
<?= $caCert ? '✅ Found' : '❌ Missing' ?>
</li>
</ul>

</body>
</html>
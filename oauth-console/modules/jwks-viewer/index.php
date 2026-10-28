<?php
    $jwks = null;
    $error = null;

    $uri = $_GET['uri'] ?? '';

    if (!empty($uri))
    {
        $json = @file_get_contents($uri);
        if ($json === false)
        {
            $error = "Failed to fetch JWKS.";
        }
        else
        {
            $jwks = json_decode($json, true);
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
<title>JWKS Viewer</title>

<link rel="stylesheet" href="jw">

<h1>JWKS Viewer</h1>
<p>
<strong>JWKS URI:</strong>
<?= htmlspecialchars($uri) ?>
</p>

<?php if ($error): ?>
    <div class="error">
        <?= htmlspecialchars($error) ?>
    </div>
    <?php endif; ?>

<?php if ($jwks): ?>
    <h2>JWKS Summary</h2>
    <p>Keys Found:
        <?= count($jwks['keys'] ?? []) ?>
    </p>
    <?php endif; ?>

<?php if (!empty($jwks['keys'])): ?>
    <table border="1" cellpadding="5">
    <tr>
        <th>KID</th>
        <th>Algorithm</th>
        <th>Type</th>
        <th>Use</th>
    </tr>
    <?php foreach ($jwks['keys'] as $key): ?>
        <tr>
            <td>
                <?= htmlspecialchars($key['kid'] ?? 'Unknown') ?>
            </td>
            <td>
                <?= htmlspecialchars($key['alg'] ?? 'Unknown') ?>
            </td>
            <td>
                <?= htmlspecialchars($key['kty'] ?? 'Unknown') ?>
            </td>
            <td>
                <?= htmlspecialchars($key['use'] ?? 'Unknown') ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
    
    <?php foreach ($jwks['keys'] as $key): ?>
        <div class="key-card">
            <h3> kid: 
                <?= htmlspecialchars($key['kid'] ?? 'No KID') ?>
            </h3>
            <ul>
                <li>
                    Key Type:
                    <?= htmlspecialchars($key['kty'] ?? 'Unknown') ?>
                </li>
                <li>
                    Algorithm:
                    <?= htmlspecialchars($key['alg'] ?? 'Unknown') ?>
                </li>
                <li>
                    Use:
                    <?= htmlspecialchars($key['use'] ?? 'Unknown') ?>
                </li>
            </ul>
        </div>
    <?php endforeach; ?>
    
<?php endif; ?>
<php



<?php if ($jwks): ?>
    <h2>Raw JWKS</h2>
    <pre>
    <?= htmlspecialchars(
        json_encode(
            $jwks,
            JSON_PRETTY_PRINT |
            JSON_UNESCAPED_SLASHES
        )
    ) ?>
    </pre>
    <?php endif; ?>

</body>
</html>

$uri = $_POST['jwks_uri'];

$json = file_get_contents($uri);

$obj = json_decode(
    $json,
    true
);

<h2>JWKS Summary</h2>
<p>
Number of Keys:
<?= count($obj['keys']) ?>
</p>

foreach ($obj['keys'] as $key)
{

    <h3>
    <?= htmlspecialchars(
        $key['kid'] ?? 'Unknown'
    ) ?>
    </h3>

    <ul>

    <li>

    Key Type:

    <?= htmlspecialchars(
        $key['kty'] ?? 'Unknown'
    ) ?>

    </li>

    <li>

    Algorithm:

    <?= htmlspecialchars(
        $key['alg'] ?? 'Unknown'
    ) ?>

    </li>

    <li>

    Use:

    <?= htmlspecialchars(
        $key['use'] ?? 'Unknown'
    ) ?>

    </li>

    </ul>

    <pre>
    <?= htmlspecialchars(
            json_encode(
                $key,
                JSON_PRETTY_PRINT
            )
        )
    ?>
    </pre>
    echo "
    <p>
    This RSA public key is published by the
    Identity Provider and is used by relying
    parties to verify signatures on JWTs.
    </p>
    ";
}
<?php require_once "classes/JwtBuilder.php"; ?>
<!doctype html>
<html><head>
    <title>JWT Builder v2</title>
    <link rel="stylesheet" href="assets/jwt-builder.css">
</head>
<body>
    <h1>JWT Builder v2</h1>
    <form method="post" action="api/generate.php">
        <textarea name="claims" rows="12" cols="100">{
            "iss":"client",
            "sub":"client",
            "aud":"https://example.org/token"
        }
        </textarea>
        <button>Generate JWT</button>
    </form>

    <div class="tabs">
    <button onclick="showTab('generate')">
        Generate
    </button>
    <button onclick="showTab('decode')">
        Decode
    </button>
    <button onclick="showTab('verify')">
        Verify
    </button>
    <button onclick="showTab('jwks')">
        JWKS
    </button>
    <button onclick="showTab('keys')">
        Keys
    </button>
</div>

<div id="generate" class="tab-content">
    <h2>Generate JWT</h2>
    <?php include 'templates/generate.php'; ?>
</div>
<div id="decode" class="tab-content">
    <h2>Decode JWT</h2>
    <?php include 'templates/decode.php'; ?>
</div>
<div id="verify" class="tab-content">
    <h2>Verify JWT</h2>
    <?php include 'templates/verify.php'; ?>
</div>
<div id="jwks" class="tab-content">
    <h2>JWKS Export</h2>
    <?php include 'templates/jwks.php'; ?>
</div>
<div id="keys" class="tab-content">
    <h2>Key Inspector</h2>
    <?php include 'templates/keys.php'; ?>
</div>
    <p>
        <a href="api/export-jwks.php">Export JWKS</a>
    </p>
</body></html>

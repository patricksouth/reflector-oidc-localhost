# Localhost OIDC attribute reflector

A local development tool for inspecting claims released by an OpenID Connect
provider (OP) after authentication. It runs PHP and Apache with
[mod_auth_openidc](https://github.com/OpenIDC/mod_auth_openidc) and can decode
access tokens for troubleshooting.

This application displays authentication data. Use it for local testing, not as
a production service.

## Prerequisites

- Docker with the Compose plugin.
- GNU Make.
- [Mike Farah's yq v4](https://github.com/mikefarah/yq) on your `PATH`.
- OpenSSL to generate a local TLS certificate.
- An OIDC client registration with your provider.

## Configure versions and ports

Edit [config.yaml](./config.yaml). The Makefile reads this file and exports the
image and networking settings to Compose.

| Setting | Purpose |
| --- | --- |
| `release.IMAGE_NAME` | Docker image repository/name |
| `release.VERSION` | Local image release identifier |
| `components.PHP_IMAGE_VER` | PHP image version |
| `components.OIDC_VER` | mod_auth_openidc release version |
| `components.OIDC_SHA256` | SHA-256 digest of that release's source archive |
| `networking.HTTP_PORT` | Host HTTP port, currently `8080` |
| `networking.HTTPS_PORT` | Host HTTPS port, currently `8443` |

Available versions:

- [PHP image tags](https://hub.docker.com/_/php/tags)
- [mod_auth_openidc releases](https://github.com/OpenIDC/mod_auth_openidc/releases)

When changing `components.OIDC_VER`, also update `components.OIDC_SHA256` using
the digest shown under the matching GitHub release's Assets for
`mod_auth_openidc-<version>.tar.gz`. Copy only the 64 hexadecimal characters,
without the `sha256:` prefix.

The resulting image tag has this format:

```text
<IMAGE_NAME>:<PHP_IMAGE_VER>-<OIDC_VER>-<VERSION>
```

## Configure the OIDC client

If you do not already have a local OIDC configuration, copy the example:

```sh
cp src/auth_openidc.conf.example src/auth_openidc.conf
```

Edit [src/auth_openidc.conf](./src/auth_openidc.conf), including:

- `OIDCClientID`
- `OIDCClientSecret`
- `OIDCScope`
- `OIDCProviderMetadataURL`
- `OIDCRedirectURI`
- `OIDCCryptoPassphrase`

Use the same hostname and HTTPS port in the provider registration, redirect URI,
and browser. For example, when using `www.localhost` and port `8443`:

```text
Home URL:     https://www.localhost:8443/
Redirect URI: https://www.localhost:8443/secure/redirect_uri
```

Ensure that the chosen hostname resolves to your loopback address. Review
[src/000-webserver.conf](./src/000-webserver.conf) for matching host settings.
Its HTTP-to-HTTPS rewrite currently redirects to `https://localhost` without a
port; adjust it if using a different hostname or a nonstandard HTTPS port.

See the upstream
[mod_auth_openidc configuration reference](https://github.com/OpenIDC/mod_auth_openidc/blob/master/auth_openidc.conf)
for directive descriptions. Keep client secrets, private keys, and captured
tokens out of version control.

## Generate a local certificate

For the example hostname:

```sh
openssl req -x509 -sha256 -nodes -days 365 -newkey rsa:2048 \
  -keyout src/server.key \
  -out src/server.crt \
  -subj "/CN=www.localhost" \
  -addext "subjectAltName=DNS:www.localhost,DNS:localhost"
chmod 600 src/server.key
```

This writes the certificate and key mounted by Compose. Do not overwrite an
existing pair unless you intend to replace it. The mounts are read-only inside
the container. The certificate is self-signed, so your browser will normally
display a trust warning.

## Build and run

Build the image:

```sh
make build
```

Make rejects a missing, null, empty, or whitespace-only OIDC checksum before invoking
Docker for this target. The Dockerfile verifies the downloaded source archive
using `sha256sum --check --strict` before extraction; malformed or mismatched
checksums fail the build.

The Dockerfile applies Debian OS upgrades in a shared base stage inherited by
both the OpenIDC builder and the final runtime image. Dependency installations
then run in their respective stages. Normal `make build` allows cached layers.
To refresh the PHP base image and rerun OS upgrades, use:

```sh
make build-fresh
```

This target automatically passes `--pull --no-cache` to Docker; no additional
flags are needed.

Start the service:

```sh
make up
```

Use Make for these commands so Compose receives the values from
[config.yaml](./config.yaml). Running `docker compose` directly does not load
that YAML configuration automatically.

Open `https://www.localhost:8443/` (or your configured hostname and port), accept
the local certificate warning, and click **Login** to authenticate with the
provider.

| Command | Action |
| --- | --- |
| `make` / `make help` | Show available targets |
| `make build` | Build the configured image |
| `make build-fresh` | Pull the base image and rebuild without cached layers |
| `make up` | Create/start the service in the background |
| `make down` | Stop and remove the Compose containers and network |
| `make restart` | Restart the existing `reflector` container |
| `make logs` | Follow the container logs |
| `make cli` | Open a Bash shell inside the running container |

Running `make` without a target displays help. After rebuilding an image,
use `make up` to apply it; `make restart` only restarts the existing
container.

Application files, Apache site configuration, and OIDC configuration are
bind-mounted from this checkout. Restart Apache/the container after changing
Apache or OIDC configuration.

## Credits

Built on the official [PHP Docker image](https://hub.docker.com/_/php) and
[mod_auth_openidc](https://github.com/OpenIDC/mod_auth_openidc).

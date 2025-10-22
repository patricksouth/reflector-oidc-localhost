# README

This is a local host example that deploys an attribut reflector for an **OIDC RP** that displays attributes released by the **OIDC OP** after successful authenication. 

This reflector is useful for troubleshooting OIDC claims including decoding encoded access tokens - this is still a work in progress.

Register this RP with an OIDC OP before starting this service.

## Configuration Before Deploying

Copy the example file ```src/auth_openidc.conf.example``` to ```src/auth_openidc.conf``` as a starting OIDC RP config and update with relevant details.
See [https://github.com/OpenIDC/mod_auth_openidc/blob/master/auth_openidc.conf](https://github.com/OpenIDC/mod_auth_openidc/blob/master/auth_openidc.conf)
for all config items and descriptions

## USAGE

If necessary, update the ```Makefile``` file with later releases of the PHP and OIDC module or continue with defaults.

Later PHP releases here: [https://hub.docker.com/_/php/tags](https://hub.docker.com/_/php/tags).

Later mod_auth_openidc Apache module here: [https://github.com/OpenIDC/mod_auth_openidc/releases](https://github.com/OpenIDC/mod_auth_openidc/releases).

For the mod_auth_openidc, just use the version number.

Build the Docker image with: 

```make build ```

To create the certs run the following:

```openssl req -x509 -sha256 -nodes -days 365 -newkey rsa:2048 -keyout src/server.key -out src/server.crt```

Start the service with:
```make start ```

Restart the service with:
```make restart ```

Stop the service with:
```make stop ```

View logs with:
```make log ```

From within a browser, access the localhost URL and accept the invalid certs warning.

Depending on the host and/or guest config, it maybe necessary to route traffic to ports 5000 and 5001 and update the ```docker-compose.yml``` file.       

Use <b>https://localhost/</b>

Click the "Login" button and when prompted complete a login.

On the redirect after login, the local host may be slow to respond, give it a chance.

<i>This is a derivative work based on the PHP Docker image and (https://github.com/OpenIDC/mod_auth_openidc) </i>

<b>Notes for configuring the OIDC OP</b>

Home URL: https://localhost.local
Callback/redirect_uri parameter: https://localhost/secure/redirect_uri

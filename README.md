# README

This is a local_host example that deploys an attribut reflector for an **OIDC RP** that displays attributes released by the **OIDC OP** after successful authenication. 

This reflector is useful for troubleshooting OIDC claims including decoding encoded access tokens - this is still a work in progress.

Register this RP with the OIDC OP before starting this service.

## Configuration Before Deploying

Copy the example file ```src/auth_openidc.conf.example``` to ```src/auth_openidc.conf``` as a starting OIDC RP config and update with relevant details, including isetting the port and the following items:
- OIDCClientID
- OIDCClientSecret
- OIDCScope
- OIDCProviderMetadataURL

See [https://github.com/OpenIDC/mod_auth_openidc/blob/master/auth_openidc.conf](https://github.com/OpenIDC/mod_auth_openidc/blob/master/auth_openidc.conf)
for all config items and descriptions

## USAGE

If necessary, update the ```.env``` file with later versions of the PHP and OIDC modules or continue with defaults - just update version numbers. 
Replace the container port numbers (8080/8443) to match the values in `auth_openidc.conf` file if necessary before starting the service.

PHP tags here: [https://hub.docker.com/_/php/tags](https://hub.docker.com/_/php/tags).

OpenIDC mod_auth_openidc tags here: [https://github.com/OpenIDC/mod_auth_openidc/releases](https://github.com/OpenIDC/mod_auth_openidc/releases).

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

From within a browser, access the localhost URL at the configured port and accept the invalid certs warning.

Depending on the host and/or guest config, it maybe necessary to route traffic to the ports configured (8080/8443).       

With a browser acccess <b>https://www.localhost:8443/</b>

Click the "Login" button and when prompted complete a login.

On the redirect after login, the local_host may be slow to respond, give it a chance.

<i>This is a derivative work based on the PHP Docker image and (https://github.com/OpenIDC/mod_auth_openidc) </i>

<b>Notes for configuring the OIDC OP</b>

Home URL: https://www.localhost:8443
Callback/redirect_uri parameter: https://www.localhost:8443/secure/redirect_uri

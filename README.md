# README

This is a local host example that deploys an attribut reflector for an **OIDC RP** that displays attributes release by the **OIDC OP** after successful authenication. 

This reflector is useful for troubleshooting OIDC claims including decoding encoded access tokens.

## Configuration Before Deploying

Copy the example file ```src/auth_openidc.conf.example``` to ```src/auth_openidc.conf``` as a starting OIDC RP config and update with relevant details.
See [https://github.com/OpenIDC/mod_auth_openidc/blob/master/auth_openidc.conf](https://github.com/OpenIDC/mod_auth_openidc/blob/master/auth_openidc.conf)
for all config items and descriptions

## USAGE

Build the Docker image with: 

```docker build -t reflector-oidc-localhost:1 . ```

To create the certs run the following:

```openssl req -x509 -sha256 -nodes -days 365 -newkey rsa:2048 -keyout src/server.key -out src/server.crt ```

Start the service with:
```docker compose up ```

From within a browser, access the localhost URL and accept invalid certs warning. Depending on the locahost config, it maybe necessary to route traffic to ports 5000 and 5001.

<b>https://localhost:5001/</b>


Follow the link "OIDC - Attribute Reflector" and then 
"Access the protected page" and when prompted complete a login.

On the redirect after login, the local host may be slow to respond, give it a chance.


<i>This is derivative work based on php:8-apache Docker image from: </i>

https://hub.docker.com/_/php/

<b>Notes for configuring the OIDC OP</b>

Home URL: https://localhost.local:5001
Callback/redirect_uri parameter: https://localhost:5001/secure/redirect_uri

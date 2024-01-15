

This is a local host example that connects to an OIDC OP and reflects attributes passed by the OP. 
This reflector is useful for troubleshooting OIDC claims including decoding encoded access tokens.
You need to register this service with the OIDC OP before this example will function.

Build the image with:

<b>docker build -t openidc-local:1 . </b>

To create the certs run the following:

<b>openssl req -x509 -sha256 -nodes -days 365 -newkey rsa:2048 -keyout src/server.key -out src/server.crt </b>

Copy the example file <b>src/auth_openidc.conf.example</b> to <b>src/auth_openidc.conf</b> as a starting OIDC RP config. Update <b>src/auth_openidc.conf</b> with relevant details.

Start the service with:
<b>docker compose up </b>

From within a browser, access the localhost URL and accept invalid certs warning. Depending on the locahost config, it maybe necessary to route traffic to ports 5000 and 5001.

<b>https://localhost:5001/</b>


Follow the link "OIDC - Attribute Reflector" and then 
"Access the protected page" and when prompted complete a login.

On the redirect after login, the local host may be slow to respond, give it a chance.


<i>This is derivative work based on php:8-apache Docker image from: </i>

https://hub.docker.com/_/php/

<b>Notes for the OP</b>

Home URL: https://localhost.local:5001
Callback/redirect_uri parameter: https://localhost:5001/secure/redirect_uri

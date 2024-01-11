#!/bin/bash
set -x
set -o errexit
set -o nounset
set -o pipefail

# 9-Jan-2024

cd "$(dirname "$0")"

source reflector.env
# export $(cat reflector.env) > /dev/null 2>&1

# This reflector depends on a Traefik proxy. See README.md for details to configure a Traefik proxy.
# Traefik stores web AL certificates in the $LETSENCRYPT path automatically the first time it runs.
# If you're using your own certs, place your certs into these locations
# $LETSENCRYPT/{certs,private} respectively before starting these services.
# Otherwise, let this script create a symlink to Letsencrypt (specified in the reflector.env file.

if [[ -L ./letsencrypt ]]; then
  if [[ ! -e ./letsencrypt ]]; then
    rm -f ./letsencrypt
    ln -s ${LETSENCRYPT} ./letsencrypt
  fi
else
    ln -s ${LETSENCRYPT} ./letsencrypt
fi

if [ ! -d "logs" ]; then
  mkdir {logs,tmp}
fi

# Deploy HTTPS SSL cert to container as secrets, update secrets otherwise.
#

if [ -n $(docker secret ls | grep reflector_oidc_https_cert_file) ]; then
  docker secret rm reflector_oidc_https_cert_file
  docker secret rm reflector_oidc_https_privkey_file
fi

# Apache SSL certificates
docker secret create reflector_oidc_https_cert_file ./letsencrypt/certs/$APACHE_FQDN.crt
docker secret create reflector_oidc_https_privkey_file ./letsencrypt/private/$APACHE_FQDN.key

if [ -z $(docker stack ls --format '{{.Name}}' | grep reflector-oidc) ]; then
  echo "removing reflector-oidc ... wait"
  docker stack rm reflector-oidc
  sleep 10
fi

docker stack deploy --compose-file docker-stack.yml reflector-oidc

# docker service logs -f httpd-oidc_oidc

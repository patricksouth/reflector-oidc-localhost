#!/bin/bash
set -x
openssl genrsa \
-out keys/private.pem \
2048
 
openssl rsa \
-in keys/private.pem \
-pubout \
-out keys/public.pem



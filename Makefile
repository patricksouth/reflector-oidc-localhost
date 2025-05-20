IMAGE_NAME=reflector-oidc
TAG=1

start:
	docker compose up -d

stop:
	docker compose down

restart:
	docker restart reflector

build:
	docker build -t $(IMAGE_NAME):$(TAG) -f Dockerfile3 . --build-arg MOD_AUTH_OPENIDC_VERSION=2.4.17 --build-arg PHP_VER=php:8.4-apache

log:
	docker logs -f reflector


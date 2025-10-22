IMAGE_NAME=reflector-oidc
TAG=1
PHP_IMAGE=php:8.4-apache-bookworm
OIDC_VER=2.4.18.1

up:
	docker compose up -d

down:
	docker compose down

restart:
	docker restart reflector

build:
	docker build -t $(IMAGE_NAME):$(OIDC_VER) -f Dockerfile . --build-arg MOD_AUTH_OPENIDC_VERSION=$(OIDC_VER) --build-arg PHP_IMAGE=$(PHP_IMAGE)

log:
	docker logs -f reflector


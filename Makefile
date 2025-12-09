include .env

up:
	docker compose up -d

down:
	docker compose down

restart:
	docker restart reflector

build:
	docker build -t $(IMAGE_NAME):$(OIDC_VER) -f Dockerfile . --build-arg MOD_AUTH_OPENIDC_VERSION=$(OIDC_VER) --build-arg PHP_IMAGE_VER=$(PHP_IMAGE_VER)

log:
	docker logs -f reflector

cli:
	docker exec -it reflector bash

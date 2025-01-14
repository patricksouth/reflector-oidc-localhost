IMAGE_NAME=reflector-oidc
TAG=1

start:
	docker compose up -d

stop:
	docker compose down

restart:
	docker compose down
	docker compose up -d

build:
	docker build -t $(IMAGE_NAME):$(TAG) .

log:
	docker logs -f reflector

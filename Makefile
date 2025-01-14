
start:
	docker compose up -d

stop:
	docker compose down

restart:
	docker compose down
	docker compose up -d
build:
	docker build -t reflector-oidc-localhost:1 .

log:
	docker logs -f reflector

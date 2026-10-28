.DEFAULT_GOAL := help

# Need a YAML parser
YQ := $(shell command -v yq 2> /dev/null)
all:
ifndef YQ
    $(error "yq is not available please install yq")
endif

RELEASE := $(shell yq '.release.VERSION' config.yaml)
IMAGE_NAME := $(shell yq '.release.IMAGE_NAME' config.yaml)
PHP_IMAGE_VER  := $(shell yq '.components.PHP_IMAGE_VER' config.yaml)
OIDC_VER  := $(shell yq '.components.OIDC_VER' config.yaml)
OIDC_SHA256 := $(shell yq -r '.components.OIDC_SHA256 // ""' config.yaml)
HTTPS_PORT := $(shell yq '.networking.HTTPS_PORT' config.yaml)
HTTP_PORT := $(shell yq '.networking.HTTP_PORT' config.yaml)

export IMAGE_NAME PHP_IMAGE_VER OIDC_VER RELEASE HTTP_PORT HTTPS_PORT

help:
	@printf '%s\n' \
		'Usage: make <target>' \
		'' \
		'  help         Show this help (default)' \
		'  build        Build the configured image' \
		'  build-fresh  Pull the base image and rebuild without cached layers' \
		'  up           Create/start the service in the background' \
		'  down         Stop and remove the Compose containers and network' \
		'  restart      Restart the service' \
		'  logs         Follow the service logs' \
		'  cli          Open a Bash shell in the running service' \
		'  clean        Prune unused cache across the selected Docker builder'

up:
	docker compose up -d

down:
	docker compose down

restart:
	docker compose restart httpd

build:
ifeq ($(strip $(OIDC_SHA256)),)
	$(error OIDC_SHA256 is missing or empty; set components.OIDC_SHA256 in config.yaml using the digest under Assets from https://github.com/OpenIDC/mod_auth_openidc/releases/tag/v$(OIDC_VER))
endif
	docker build $(BUILD_FLAGS) -t $(IMAGE_NAME):$(PHP_IMAGE_VER)-$(OIDC_VER)-$(RELEASE) -f Dockerfile . \
		--build-arg PHP_IMAGE_VER=$(PHP_IMAGE_VER) \
		--build-arg MOD_AUTH_OPENIDC_VERSION=$(OIDC_VER) \
		--build-arg OPENIDC_SHA256=$(OIDC_SHA256)

build-fresh: BUILD_FLAGS = --pull --no-cache
build-fresh: build

logs:
	docker compose logs -f httpd

cli:
	docker compose exec httpd bash

.PHONY: all help build build-fresh up down restart logs cli clean

clean:
	docker builder prune
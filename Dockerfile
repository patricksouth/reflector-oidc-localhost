ARG PHP_IMAGE=${PHP_IMAGE:-php:8.3.21-apache-bookworm}

FROM ${PHP_IMAGE}
LABEL "BUILD"="docker build -t reflector-oidc:1 -f Dockerfile . --build-arg MOD_AUTH_OPENIDC_VERSION=2.4.17 --build-arg PHP_IMAGE=php:8.3.21-apache-bookworm"

ENV APT_KEY_DONT_WARN_ON_DANGEROUS_USAGE=DontWarn
ARG MOD_AUTH_OPENIDC_VERSION
ENV MOD_AUTH_OPENIDC_VERSION=${MOD_AUTH_OPENIDC_VERSION:-2.4.17}

RUN echo "OpenIDC verison: ${MOD_AUTH_OPENIDC_VERSION}"

RUN apt update && apt dist-upgrade -y && \
    apt install -y \
        curl wget \
        apache2-dev \
        libcurl4-openssl-dev \
        openssl \
        libssl-dev \
        libjansson-dev \
        libcjose0 \
        libcjose-dev && \
	apt autoremove && apt clean && \
        rm -rf /var/lib/apt/lists/*

RUN MOD_AUTH_OPENIDC_URL="https://github.com/OpenIDC/mod_auth_openidc/releases/download/v${MOD_AUTH_OPENIDC_VERSION}/mod_auth_openidc-${MOD_AUTH_OPENIDC_VERSION}.tar.gz" && \
    mkdir -p /tmp/mod_auth_openidc && \
    wget -O mod_auth_openidc.tar.gz "${MOD_AUTH_OPENIDC_URL}" && \
    tar -zxf mod_auth_openidc.tar.gz -C /tmp/mod_auth_openidc --strip-components=1 && \
    cd /tmp/mod_auth_openidc && \
    ./configure --with-apxs2=`which apxs2` && \
    make && \
    make install && \
    echo "LoadModule auth_openidc_module /usr/lib/apache2/modules/mod_auth_openidc.so" > /etc/apache2/mods-available/auth_openidc.load && \
    rm -rf /tmp/mod_auth_openidc && \
    touch /etc/apache2/sites-available/000-webserver.conf && \
    touch /etc/apache2/conf-available/auth_openidc.conf

RUN a2enmod rewrite && \
    a2enmod auth_openidc && \
    a2enmod socache_shmcb && \
    a2enmod ssl && \
    a2enmod headers && \
    a2dismod status && \
    a2dissite 000-default  && \
    a2ensite 000-webserver && \
    a2enconf auth_openidc && \
    service apache2 restart

EXPOSE 80 443

CMD ["apache2-foreground"]


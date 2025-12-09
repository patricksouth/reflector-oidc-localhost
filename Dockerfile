ARG PHP_IMAGE_VER=${PHP_IMAGE_VER}
ARG PHP_IMAGE=php:${PHP_IMAGE_VER:-8.3.21}-apache-bookworm

FROM ${PHP_IMAGE} AS reflector
RUN echo "${PHP_IMAGE_VER}"
FROM ${PHP_IMAGE} AS building

ENV APT_KEY_DONT_WARN_ON_DANGEROUS_USAGE=DontWarn
ARG MOD_AUTH_OPENIDC_VERSION
ENV MOD_AUTH_OPENIDC_VERSION=${MOD_AUTH_OPENIDC_VERSION:-2.4.17}

RUN echo "OpenIDC verison: ${MOD_AUTH_OPENIDC_VERSION}"

RUN apt update \
    && apt dist-upgrade -y \
    && apt install -y \
       apache2 \
       apache2-dev \
       automake \
       ca-certificates \
       libcjose0 \
       libcjose-dev \
       libcurl4-openssl-dev \
       libjansson-dev \
       libpcre3-dev \
       libssl-dev \
       pkg-config \
       wget curl

RUN MOD_AUTH_OPENIDC_URL="https://github.com/OpenIDC/mod_auth_openidc/releases/download/v${MOD_AUTH_OPENIDC_VERSION}/mod_auth_openidc-${MOD_AUTH_OPENIDC_VERSION}.tar.gz" \
    && mkdir -p /tmp/mod_auth_openidc \
    && wget -O mod_auth_openidc.tar.gz "${MOD_AUTH_OPENIDC_URL}" \
    && tar -zxf mod_auth_openidc.tar.gz -C /tmp/mod_auth_openidc --strip-components=1 \
    && cd /tmp/mod_auth_openidc \
    && ./configure --with-apxs=`which apxs2` \
    && make \
    && make install 

FROM reflector

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
       libcjose0 \
       openssl \
       jq

COPY --from=building /usr/lib/apache2/modules/mod_auth_openidc.so /usr/lib/apache2/modules/mod_auth_openidc.so
COPY src/conf-available/security.conf /etc/apache2/conf-available/security.conf
COPY src/mods-available/ssl.conf /etc/apache2/mods-available/ssl.conf

RUN echo "LoadModule auth_openidc_module /usr/lib/apache2/modules/mod_auth_openidc.so" > /etc/apache2/mods-available/auth_openidc.load \
    && touch /etc/apache2/sites-available/000-webserver.conf \
    && touch /etc/apache2/conf-available/auth_openidc.conf


RUN a2enmod rewrite \
    && a2enmod auth_openidc \
    && a2enmod socache_shmcb \
    && a2enmod headers \
    && a2enmod ssl \
    && a2dismod status \
    && a2enconf auth_openidc \
    && a2enconf security \
    && a2dissite 000-default \
    && a2ensite 000-webserver 

EXPOSE 80 443

CMD ["apache2-foreground"]



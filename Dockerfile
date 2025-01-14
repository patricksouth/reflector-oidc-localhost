FROM php:8.4-apache 
LABEL BUILD docker build -t reflector-oidc-localhost:1 .

RUN apt update && apt dist-upgrade -y && \
  apt install -y \
  curl \
  libjansson4 \
  wget \
  libhiredis0.14 \
  libcjose0 \
  libapache2-mod-auth-openidc && \
  rm -rf /var/lib/apt/lists/* && \
  touch /etc/apache2/sites-available/000-webserver.conf

RUN a2enmod rewrite && \
    a2enmod auth_openidc && \
    a2enmod socache_shmcb && \
    a2enmod ssl && \
    a2enmod headers && \
    a2dismod status && \
    a2dissite 000-default  && \
    a2ensite 000-webserver && \
    service apache2 restart

EXPOSE 443 80

CMD ["apache2-foreground"]

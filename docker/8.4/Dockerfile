FROM ubuntu:24.04

LABEL maintainer="Taylor Otwell"

ARG WWWGROUP=1000
ARG NODE_VERSION=22
ARG MYSQL_CLIENT="mysql-client"

WORKDIR /var/www/html

ENV DEBIAN_FRONTEND=noninteractive
ENV TZ=UTC
ENV SUPERVISOR_PHP_COMMAND="/usr/bin/php -d variables_order=EGPCS /var/www/html/artisan serve --host=0.0.0.0 --port=80"
ENV SUPERVISOR_PHP_USER="sail"
ENV PLAYWRIGHT_BROWSERS_PATH=0

# ---------------------------
# Timezone e APT
# ---------------------------
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone \
 && echo "Acquire::http::Pipeline-Depth 0;" > /etc/apt/apt.conf.d/99custom \
 && echo "Acquire::http::No-Cache true;" >> /etc/apt/apt.conf.d/99custom \
 && echo "Acquire::BrokenProxy true;" >> /etc/apt/apt.conf.d/99custom

# ---------------------------
# Pacotes essenciais + PHP + Swoole + gosu
# ---------------------------
RUN apt-get update && apt-get install -y \
      gnupg curl ca-certificates zip unzip git nano libpng-dev libcap2-bin supervisor \
 && mkdir -p /etc/apt/keyrings /var/log/supervisor /etc/supervisor/conf.d \
 && curl -sS 'https://keyserver.ubuntu.com/pks/lookup?op=get&search=0xb8dc7e53946656efbce4c1dd71daeaab4ad4cab6' \
      | gpg --dearmor | tee /etc/apt/keyrings/ppa_ondrej_php.gpg > /dev/null \
 && echo "deb [signed-by=/etc/apt/keyrings/ppa_ondrej_php.gpg] https://ppa.launchpadcontent.net/ondrej/php/ubuntu noble main" \
      > /etc/apt/sources.list.d/ppa_ondrej_php.list \
 && apt-get update && apt-get install -y \
      php8.4-cli php8.4-dev php8.4-mysql php8.4-mbstring php8.4-xml \
      php8.4-zip php8.4-bcmath php8.4-intl php8.4-curl php8.4-gd php8.4-swoole \
      gosu \
 && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# ---------------------------
# Composer
# ---------------------------
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer

# ---------------------------
# Node.js + npm + pnpm + bun
# ---------------------------
RUN curl -fsSL https://deb.nodesource.com/gpgkey/nodesource-repo.gpg.key | gpg --dearmor -o /etc/apt/keyrings/nodesource.gpg \
 && echo "deb [signed-by=/etc/apt/keyrings/nodesource.gpg] https://deb.nodesource.com/node_$NODE_VERSION.x nodistro main" > /etc/apt/sources.list.d/nodesource.list \
 && apt-get update && apt-get install -y nodejs \
 && npm install -g npm pnpm bun \
 && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# ---------------------------
# Yarn
# ---------------------------
RUN curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | gpg --dearmor | tee /etc/apt/keyrings/yarn.gpg >/dev/null \
 && echo "deb [signed-by=/etc/apt/keyrings/yarn.gpg] https://dl.yarnpkg.com/debian/ stable main" > /etc/apt/sources.list.d/yarn.list \
 && apt-get update && apt-get install -y yarn \
 && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# ---------------------------
# MySQL client
# ---------------------------
RUN apt-get update && apt-get install -y $MYSQL_CLIENT \
 && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN setcap "cap_net_bind_service=+ep" /usr/bin/php8.4

# ---------------------------
# Usuário sail
# ---------------------------
RUN userdel -r ubuntu || true
RUN groupadd --force -g $WWWGROUP sail
RUN useradd -ms /bin/bash --no-user-group -g $WWWGROUP -u 1337 sail

# ---------------------------
# Permissões Laravel
# ---------------------------
RUN mkdir -p /var/www/html/storage /var/www/html/bootstrap/cache \
    && chown -R sail:$WWWGROUP /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# ---------------------------
# Supervisor e PHP
# ---------------------------
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY php.ini /etc/php/8.4/cli/conf.d/99-sail.ini

# ---------------------------
# Copiar projeto e scripts
# ---------------------------
COPY . .
RUN chown -R sail:$WWWGROUP /var/www/html

COPY start-container.sh /usr/local/bin/start-container.sh
RUN chmod +x /usr/local/bin/start-container.sh

# ---------------------------
# Portas expostas
# ---------------------------
EXPOSE 8000 5173

# ---------------------------
# CMD final
# ---------------------------
CMD ["/usr/local/bin/start-container.sh"]

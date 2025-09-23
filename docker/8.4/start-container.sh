#!/usr/bin/env bash

set -e

# -----------------------------
# Verifica usuário SUPERVISOR_PHP_USER
# -----------------------------
if [ "$SUPERVISOR_PHP_USER" != "root" ] && [ "$SUPERVISOR_PHP_USER" != "sail" ]; then
    echo "SUPERVISOR_PHP_USER deve ser 'root' ou 'sail'."
    exit 1
fi

# -----------------------------
# Ajusta UID do sail
# -----------------------------
if [ ! -z "$WWWUSER" ]; then
    usermod -u $WWWUSER sail
fi

# -----------------------------
# Cria diretório global do Composer
# -----------------------------
mkdir -p /.composer
chmod -R ugo+rw /.composer

# -----------------------------
# Ajustes Laravel e Composer
# -----------------------------
chown -R sail:$WWWGROUP /var/www/html
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public
chown -R sail:sail /var/www/html/node_modules

# Remove locks do Octane
rm -f /var/www/html/storage/octane/*.lock || true
pkill -f "artisan octane:start" || true

# Instala dependências do Composer
if [ ! -d /var/www/html/vendor ]; then
    echo "Instalando dependências do Composer..."
    gosu sail composer install --no-interaction --optimize-autoloader --no-dev
fi

# -----------------------------
# Instala dependências Node.js
# -----------------------------
cd /var/www/html
if [ ! -d node_modules ]; then
    echo "Instalando dependências Node..."
    gosu sail npm install
fi

# -----------------------------
# Roda Vite conforme APP_ENV
# -----------------------------
if [ "$APP_ENV" = "local" ]; then
    echo "Modo LOCAL detectado. Iniciando npm run dev..."
    gosu sail npm run dev &
elif [ "$APP_ENV" = "production" ]; then
    echo "Modo PRODUCTION detectado. Rodando npm run build..."
    gosu sail npm run build
fi

# -----------------------------
# Inicia supervisord
# -----------------------------
echo "Iniciando supervisord..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf

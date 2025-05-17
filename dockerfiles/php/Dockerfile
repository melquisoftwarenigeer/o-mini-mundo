# Usa a imagem base do PHP com FPM e Alpine
FROM php:8.3-fpm-alpine3.19

# Instala dependências do sistema e bibliotecas PHP necessárias
RUN apk add --no-cache \
    bash \
    curl \
    libpng-dev \
    libzip-dev \
    zlib-dev \
    postgresql-dev \
    shadow \
    nodejs \
    npm

# Instala extensões do PHP (imagem, compactação, PostgreSQL, etc.)
RUN docker-php-ext-install \
    gd \
    zip \
    pdo \
    pdo_pgsql \
    pgsql

# Instala Composer a partir da imagem oficial
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Cria um usuário não-root por segurança
RUN groupadd -g 1000 appgroup && \
    useradd -u 1000 -g appgroup -m appuser && \
    usermod -aG www-data appuser

# Define o diretório de trabalho
WORKDIR /var/www

# Ajusta permissões do diretório de trabalho
RUN chown -R appuser:appgroup /var/www && \
    chmod -R 755 /var/www

# Alterna para o usuário não-root
USER appuser

# Expõe a porta padrão do PHP-FPM
EXPOSE 9000

# Inicia o PHP-FPM (entrypoint padrão para container PHP-FPM)
CMD ["php-fpm"]

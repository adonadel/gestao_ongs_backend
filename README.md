<h1>Configurar projeto Gestão de Ongs - Laravel 11 + docker</h1>

<h2>Instalar git</h2>
- sudo apt install git-all

-----------

<h2>Clonar projeto</h2>
git clone https://github.com/adonadel/gestao_ongs_backend.git

-----------

<h2>Acessar pasta e instalar php e composer</h2>
apt-get install ca-certificates apt-transport-https software-properties-common

add-apt-repository ppa:ondrej/php

sudo apt update && sudo apt install -y \
  php8.3 \
  php8.3-cli \
  php8.3-fpm \
  php8.3-mysqlnd \
  php8.3-sqlite3 \
  php8.3-mbstring \
  php8.3-xml \
  php8.3-gd \
  php8.3-imagick \
  php8.3-redis \
  php8.3-opcache \
  php8.3-zip \
  php8.3-curl \
  curl \
  libpq-dev;

curl -sS https://getcomposer.org/installer -o /tmp/composer-setup.php
HASH=`curl -sS https://composer.github.io/installer.sig`

php -r "if (hash_file('SHA384', '/tmp/composer-setup.php') === '$HASH') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"

sudo php /tmp/composer-setup.php --install-dir=/usr/local/bin --filename=composer

------------

<h2>Acessar .ini e ativar extensões</h2>
sudo nano /etc/php/8.3/cli/php.ini
ctrl W pra pesquisar e pesquisa por -> ";extension=curl"
e retirar os ; das seguintes extensões:
curl
gettext
intl
mbstring
pdo_pgsql
pgsql

----------------

<h2>Baixar dependências</h2>
composer install

----------------

<h2>Criar .env e configurá-lo com os dados</h2>
cp .env.example .env

----------------

<h2>Gerar chave da aplicação</h2>
php artisan key:generate

----------------

<h2>Gerar chave de autenticação do JWT</h2>
php artisan jwt:secret

----------------

<h2>Rodar os containers</h2>
docker-compose up -d

How to setup

install docker compose
install brew https://www.how2shout.com/linux/how-to-install-brew-ubuntu-20-04-lts-linux/
brew install mkcert

git clone project
cd GameApi

setup .env (dev & test)

docker-compose build


##### install certificates #####

https://dev.to/ashleyconnor/configuring-self-signed-ssl-certificates-for-local-development-35c5

console :
mkdir docker/nginx/certs
cd docker/nginx/certs/
mkcert localhost 127.0.01 gameapi ::1
mkcert -install

change .pem generated names to localhost-key.pem and localhost.pem

*** if work on windows with WSL, type consoles commands on WSL terminal ***

console : 
cd ../../..
sudo nano /etc/hosts

add ->
127.0.0.1 gameapi
::1 gameapi

console :
sudo touch /etc/wsl.conf
sudo nano /etc/wsl.conf

add ->
[network]
generateHosts = false

console :
docker-compose up
curl https://gameapi

##### bonus configuration if working un Win with WSL #####

WSL terminal :
mkcert -CAROOT (default is /home/USER/.local/share/mkcert)

Win PowerShell :
$CAROOT="\\wsl$\Ubuntu\home\USER\.local\share\mkcert\"
mkcert -install

Windows -> import certificate
\\wsl$\Ubuntu\home\USER\.local\share\mkcert\rootCA.pem
in trusted certificates

Windows -> edit C:/Windows/System32/drivers/etc/hosts
add ->
127.0.0.1 gameapi
::1 gameapi

restart WSL

Powershell :
curl https://gameapi/api

try URL in browser

certificate (for postman or other) is in :
\\wsl$\Ubuntu\home\milonte\.local\share\mkcert\rootCA.pem

##### end of bonus configuration #####

##### symfony project setup #####

## make jwt certs
PHP-CLI : 
mkdir -p config/jwt
openssl genrsa -out config/jwt/private.pem -aes256 4096
(same passphrase as .env.local)

PHP-CLI : 
openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem
(same passphrase as .env.local)

command :
docker-compose up

PHP-CLI :
composer install
symfony console d:m:migrate
symfony console d:f:l
symfony console d:d:c --env=test
symfony console d:m:migrate --env=test
.vendor/bin/phpunit

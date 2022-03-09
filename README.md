# GameApi
`simple Api project with ApiPlatform and Docker`

## How to setup

### Initialization 

- install docker compose
- clone this repo
```
git clone project
```
- move to project folder
```
cd GameApi
```

- duplicate .example.env, rename it on .env, and changes values

- build docker images
```
docker-compose build
```

### Install certificates

> if work on windows with WSL, type consoles commands on WSL terminal

- install brew https://www.how2shout.com/linux/how-to-install-brew-ubuntu-20-04-lts-linux/
- install mkcert
```
brew install mkcert
```

- make certificates
```
mkdir docker/nginx/certs
cd docker/nginx/certs/
mkcert localhost 127.0.01 gameapi ::1
mkcert -install
```

change .pem generated names to `localhost-key.pem` and `localhost.pem`

- back to project folder
``` 
cd ../../..
```

- modify hosts config
```
sudo nano /etc/hosts
```

- copy to /etc/hosts
```
127.0.0.1 gameapi
::1 gameapi
```

- create a wsl config file
```
sudo touch /etc/wsl.conf
sudo nano /etc/wsl.conf
```

- and copy to /etc/wsl.conf
```
[network]
generateHosts = false
```

- up docker
```
docker-compose up
```

- verify API host response
```
curl https://gameapi/api
```

### bonus configuration if working on Win with WSL

- in WSL terminal
```
mkcert -CAROOT (default is /home/USER/.local/share/mkcert)
```

- in Win PowerShell
```
$CAROOT="\\wsl$\Ubuntu\home\USER\.local\share\mkcert\"
mkcert -install
```

- Import certificate in Windows
`\\wsl$\Ubuntu\home\USER\.local\share\mkcert\rootCA.pem`
in trusted certificates

- Add host
```
code C:/Windows/System32/drivers/etc/hosts
```

- add to host file
```
127.0.0.1 gameapi
::1 gameapi
```

- restart WSL

- try API response in Windows powershell
```
curl https://gameapi/api
```

- try URL in browser `https://gameapi/api`

certificate (for postman or other) is in :
`\\wsl$\Ubuntu\home\milonte\.local\share\mkcert\rootCA.pem`

### Symfony project setup

#### Setup Symfony environments variables

- create an symfony .env.local file
```
touch api/.env.local
```

- copy this lines into api/.env.local and changes values
```
JWT_PASSPHRASE=ChangeMe
DATABASE_URL="mysql://ChangeMe:ChangeMe@database:3306/symfony_docker?serverVersion=8.0"
```
> values must correspond with database values

- create an symfony .env.test.local file
```
cp api/.env.local api/.env.test.local
```

- change database name to symfony_docker_test

#### Make JWT certs

- in PHP-CLI
```
mkdir -p config/jwt
openssl genrsa -out config/jwt/private.pem -aes256 4096
```
> (same passphrase as .env.local)

```
openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem
```
> (same passphrase as .env.local)

- change rights of jwt folder (in PHP-CLI)
```
chmod a+rwX -R config/jwt
```

#### Install composer packages

- in PHP-CLI
```
composer install
```

#### Setup databases

- in PHP-CLI
```
symfony console d:m:migrate
```
```
symfony console d:f:l
```
```
symfony console d:d:c --env=test
```
```
symfony console d:m:migrate --env=test
```

#### Run phpunit tests

- in PHP-CLI
```
vendor/bin/phpunit
```

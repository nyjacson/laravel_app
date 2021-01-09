## How to start application
```shell script
docker-compose up
```

## How to access
```shell script
http://127.0.0.1:80/
http://127.0.0.1:8080/
```
## login to docker
```shell script
docker exec -it web_service bash
```

## create laravel project with specific version
```shell script
composer create-project "laravel/laravel=6.*" <Project name>
```

## enable rewrite module fr apache for routing
```shell script
root@d44766c13848:/etc/apache2/mods-available# more rewrite.load
LoadModule rewrite_module /usr/lib/apache2/modules/mod_rewrite.so

a2enmod rewrite
service apache2 restart
```
INSTALLATION
============
Clone this repo in your apps path
```
git clone https://github.com/ptflp/fyafix.git
```

Run in your cloned project path:
```
docker-compose up
```

In your app path:
```
cd app/
```

Run:
```
composer update
```


```
docker volume create portainer_data
docker run -d -p 9000:9000 --restart always -v /var/run/docker.sock:/var/run/docker.sock -v portainer_data:/data portainer/portainer
```
Go to localhost:9000 choose local
set admin password

enter to db container
```
mysql -u root -proot
GRANT ALL ON laravel.* TO 'laraveluser'@'%' IDENTIFIED BY 'test';
```

Run migration:
```
php artisan migrate
```

php artisan db:seed



USAGE
=====

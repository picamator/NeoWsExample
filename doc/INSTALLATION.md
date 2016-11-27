Environment installation
========================
All commands valid for Linux/Ubuntu operation system.

Pre install
-----------
1. Install [Vagrant 1.8.7](https://releases.hashicorp.com/vagrant/1.8.7/)
2. Install [VirtualBox 5.0.28](https://www.virtualbox.org/wiki/Download_Old_Builds_5_0)
3. Install vboxsf plugin `vagrant plugin install vagrant-vbguest`

Homestead box
-------------
In order to have PHP 5.6 please use [Homestead 0.3.3](https://atlas.hashicorp.com/laravel/boxes/homestead/versions/0.3.3)

To add homestead to your vagrant run: `vagrant box add laravel/homestead --box-version 0.3.3`

Homestead
---------
Use [v2.2.2](https://github.com/laravel/homestead/tree/v2.2.2) as latest version of 2 branches to support php 5.6.

1. Add to composer
```json
    "require-dev": {
	"incenteev/composer-parameter-handler": "~2.0"
    }
```

2. Run `php ./vendor/laravel/homestead/homestead make`
3. Using [Homestead.yaml.dist](../Homestead.yaml.dist) as a template configure your `Homestead.yml` file
4. Generate ssh key `ssh-keygen -t rsa -C "neows-example@neows-example.dev"` keep default destination

Post install
------------

### Nginx
1. Run `vagrant ssh`
2. Open `/etc/nginx/sites-available/your-virtual-host-name`
3. Update section `location` to 

```
    location / {
        # try to serve file directly, fallback to app.php
        try_files $uri /app.php$is_args$args;
    }

```
4. Restart server web server `sudo service nginx restart`

### Composer
1. Run `vagrant ssh`
2. Execute commands to install composer globally on your VM

```
php -r "file_put_contents('composer-setup.php', file_get_contents('https://getcomposer.org/installer'));"
php -r "if (hash_file('SHA384', 'composer-setup.php') === 'aa96f26c2b67226a324c27919f1eb05f21c248b987e6195cad9690d5c1ff713d53020a02ac8c217dbf90a7eacc9d141d') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"
mv composer.phar /usr/local/bin/composer

```

### MongoDB
1. Run `vagrant ssh`
2. Update your VM `sudo apt-get update`
3. Install OpenSSL `sudo apt-get install libcurl3-openssl-dev`
4. Install MongoDB `sudo pecl install mongo`
5. Execute `sudo su`
6. Make mongodb module available `echo "extension=mongo.so" >> /etc/php5/cli/php.ini` and `echo "extension=mongo.so" >> /etc/php5/fpm/php.ini`
7. Exit from root run `exit`
8. Restart web server `sudo service nginx restart`
9. Install MongoDB server `sudo apt-get install mongodb`

To have get access to MongoDB server from your host machine use ssh connection:

1. server: `localhost`
2. port: `27017`
3. ssh tunnel with IP: `192.168.10.10`, port `22`, user `vagrant`, private key `~/.ssh/id_rsa`

Alternatively it's possible yo configure your forwarding ports in `Homestead.yaml`.

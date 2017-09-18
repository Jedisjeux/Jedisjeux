# Description
This configuration includes following software:

* Debian 8.1
* PHP 7.1
* MySQL 5.6 Percona Server
* Apache 2.2.22
* Composer
* Curl
* Vim
* Git

# Usage

First you need to create vagrant VM

```
$ cd etc/vagrant
$ vagrant up
```

While waiting for Vagrant to start up, you should add an entry into /etc/hosts file on the host machine.

```
10.0.0.200      jdj.dev
```

Setup your db password in parameters.yml

```
parameters:
    database_password: vagrant
```

From now you should be able to access your jdj project at [http://jdj.dev/app_dev.php](http://jdj.dev/app_dev.php)

Installing your assets manually

```
    vagrant ssh -c 'cd /var/www/jdj && ./node_modules/.bin/gulp'
```

# Troubleshooting

Using Symfony2 inside Vagrant can be slow due to synchronisation delay incurred by NFS. To avoid this, both locations have been moved to a shared memory segment under ``/dev/shm/jdj``.

To view the application logs, run the following commands:

```bash
$ tail -f /dev/shm/jdj/app/logs/prod.log
$ tail -f /dev/shm/jdj/app/logs/dev.log
```

To view the apache logs, run the following commands:

```bash
$ tail -f /var/log/apache2/jdj_error.log
$ tail -f /var/log/apache2/jdj_access.log
```

1/ go to your vagrant directory in the jdj project

``` bash
cd vagrant/
vagrant up
```

2/ connecting onto your vagrant

``` bash
vagrant ssh
```

3/ change your local hosts file

on linux

``` bash
sudo vim etc/hosts
```

and adding following line :
127.0.0.1       jdj.dev


4/ download an archive of the jedisjeux database :

``` bash
cd /var/www/jdj
scp admin@jedisjeux.net:/srv/d_jedisjeux/www/sav/dmp_jdj_1.sql.gz ./
```

enter the admin password of admin account

5/ unzip your archive and restore the database

``` bash
gzip -d dmp_jdj_1.sql.gz
mysql -u root jedisjeux < dmp_jdj_1.sql
```

6/ create your empty database

``` bash
php app/console do:sc:up --force
php app/console doctrine:fixtures:load --fixtures=src/JDJ/
```
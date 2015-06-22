
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
cd /var/www/
sudo scp admin@jedisjeux.net:/srv/d_jedisjeux/www/sav/dmp_jdj_1.sql.gz ./
sudo scp admin@jedisjeux.net:/srv/d_jedisjeux/www/sav/dmp_cpta.sql ./
```

enter the admin password of admin account

5/ unzip your archive and restore the database

``` bash
sudo gzip -d dmp_jdj_1.sql.gz
mysql -u root jedisjeux < dmp_jdj_1.sql
mysql -u root zf_jedisjeux_test < dmp_cpta.sql
```

6/ create your empty database

``` bash
cd /var/www/jdj
php app/console do:sc:up --force
php app/console doctrine:fixtures:load --fixtures=src/JDJ/
```

7/ change the binding address to access to mysql database from local

connect on your mysql
``` bash
mysql -u root;
```

and execute these commands
``` bash
create user 'root'@'%';
grant all privileges on *.* to 'root'@'%' with grant option;
flush privileges;
```

edit the configuration file
``` bash
sudo vim /etc/mysql/my.cnf  
```

replace bind-address = 127.0.0.1
by bind-address = 0.0.0.0

save your changes and run this command to restart mysql
``` bash
sudo service mysql restart
```
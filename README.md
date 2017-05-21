![Jedisjeux](http://www.jedisjeux.net/img/design/logos/logo2010light2.png)

Welcome to Jedisjeux Project base on Symfony 2

Quick Installation with vagrant
-------------------------------

```bash
$ cd etc/vagrant
$ vagrant up
```

Import Jedisjeux production data
--------------------------------

Ask administrator the admin password or ask him a sql backup file :

```bash
$ scp jedisjeux@jedisjeux.net:/home/jedisjeux/shared/backup/dump_jdj_1.sql.gz ./
$ gzip -d dump_jdj_1.sql.gz
$ mysql -u root -pvagrant jdj_dev < dump_jdj_1.sql
```

[Behat](http://behat.org) scenarios
-----------------------------------

By default Behat uses `http://localhost:8080/` as your application base url. If your one is different,
you need to create `behat.yml` files that will overwrite it with your custom url:

```yaml
imports: ["behat.yml.dist"]

default:
    extensions:
        Behat\MinkExtension:
            base_url: http://my.custom.url
```

Then run selenium-server-standalone:

```bash
$ bin/selenium-server-standalone -Dwebdriver.chrome.driver=$PWD/bin/chromedriver
```

Then setup your test database:

```bash
$ php bin/console doctrine:database:create --env=test
$ php bin/console doctrine:schema:create --env=test
$ php bin/console cache:clear --no-warmup --env=test
$ php bin/console doctrine:phpcr:repository:init --env=test
```

You can run Behat using the following commands:

```bash
$ bin/behat
```
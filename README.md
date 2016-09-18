![Jedisjeux](http://www.jedisjeux.net/img/design/logos/logo2010light2.png)

Welcome to Jedisjeux Project base on Symfony 2

Quick Installation
------------------

```bash
$ cd etc/docker
$ docker-compose build
$ docker-compose up -d
$ docker exec -it $(docker-compose ps -q php) bash
$ composer install
$ php app/console doctrine:migrations:migrate
$ exit
```

Import Jedisjeux production data
--------------------------------

Ask administrator the admin password or ask him a sql backup file :

```bash
$ scp admin@jedisjeux.net:/srv/d_jedisjeux/www/sav/dmp_jdj_1.sql.gz ./
$ gzip -d dmp_jdj_1.sql.gz
```

Then create a new empty database called jedisjeux and import backup file

```bash
$ create database jedisjeux;
$ mysql -u root -proot jedisjeux < dmp_jdj_1.sql
```

```bash
create or replace view jdj_v_article_view_count as
select element_id as id, sum(count_click) as view_count
  from jdj_clicks
where from_id = 'articles'
group by element_id;

create or replace view jdj_v_news_view_count as
  select element_id as id, sum(count_click) as view_count
  from jdj_clicks
  where from_id = 'news'
  group by element_id;

create or replace view jdj_v_review_article_view_count as
  select element_id as id, sum(count_click) as view_count
  from jdj_clicks
  where from_id = 'tests'
  group by element_id;

```


Finally execute the Jedisjeux install command :

```bash
$ docker exec -it $(docker-compose ps -q php) bash
$ app/console app:install
```

And have a good coffee...

Import Prices of Dealers
------------------------

```bash
$ scp admin@jedisjeux.net:/home/admin/export.csv ./philibert.csv
$ scp admin@jedisjeux.net:/home/admin/ludomus/jedisjeux-export-tarif.csv ./ludomus.csv
$ scp admin@jedisjeux.net:/home/admin/espritJeu.csv ./esprit-jeu.csv
$ scp admin@jedisjeux.net:/home/admin/jedisjeux-export-tarifs.csv ./ludifolie.csv
```

```bash
$ app/console app:dealer-prices:import philibert --filename=philibert.csv
$ app/console app:dealer-prices:import ludifolie --filename=ludifolie.csv
$ app/console app:dealer-prices:import ludomus --filename=ludomus.csv --remove-first-line=true
$ app/console app:dealer-prices:import esprit-jeu --filename=esprit-jeu.csv
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
$ bin/selenium-server-standalone
```

Then setup your test database:

```bash
$ php app/console doctrine:database:create --env=test
$ php app/console doctrine:schema:create --env=test
```

You can run Behat using the following commands:

```bash
$ bin/behat
```
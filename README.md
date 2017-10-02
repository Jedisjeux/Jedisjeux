![Jedisjeux](http://www.jedisjeux.net/media/cache/logo/assets/frontend/img/logo.png)

Welcome to Jedisjeux Project base on Symfony 2

Documentation
-------------

Documentation is available at [docs.jedisjeux.net](http://docs.jedisjeux.net).

Quick Installation with vagrant
-------------------------------

```bash
$ cd etc/vagrant
$ vagrant up
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

Bug Tracking
------------

If you want to report a bug or suggest an idea, please use [GitHub issues](https://github.com/Jedisjeux/Jedisjeux/issues).


MIT License
-----------

Jedisjeux is completely free and released under the [MIT License](https://github.com/Jedisjeux/Jedisjeux/blob/master/LICENSE).

Authors
-------

Jedisjeux was originally created by Loïc Frémont.
See the list of [contributors from our community](https://github.com/Jedisjeux/Jedisjeux/contributors).

<h1 align="center">
    <a href="http://www.jedisjeux.net" target="_blank">
        <img src="http://www.jedisjeux.net/media/cache/resolve/full/assets/frontend/images/logo.png" />
    </a>
    <br />
    <a href="http://travis-ci.org/Jedisjeux/Jedisjeux" title="Build status" target="_blank">
        <img src="https://img.shields.io/travis/Jedisjeux/Jedisjeux/master.svg" />
    </a>
    <a href="https://scrutinizer-ci.com/g/Jedisjeux/Jedisjeux/" title="Scrutinizer" target="_blank">
        <img src="https://img.shields.io/scrutinizer/g/Jedisjeux/Jedisjeux.svg" />
    </a>    
</h1>

[Jedisjeux](https://www.jedisjeux.net) is a PHP boardgame website, based on [Symfony Framework](http://symfony.com/) and [Sylius](http://sylius.org/).

Documentation
-------------

Documentation is available at [docs.jedisjeux.net](https://jedisjeux.readthedocs.io/en/latest/).

Installation
------------

You need [composer](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx) to install PHP packages and [yarn](https://yarnpkg.com/lang/en/docs/install/) to install JS packages.

```bash
$ php bin/console app:install
$ yarn install && yarn run gulp
$ php bin/console server:start
```

Then open `http://localhost:8000/` in your web browser to enjoy Jedisjeux website in a development environment.

Alternatively, you can use [Vagrant](https://github.com/Jedisjeux/Jedisjeux/tree/master/etc/vagrant) for your initial setup.
you can also [use our Docker setup](https://github.com/Jedisjeux/Docker/tree/master).


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
$ vendor/bin/behat
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

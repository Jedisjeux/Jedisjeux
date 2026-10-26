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

[Behat](http://behat.org) scenarios
-----------------------------------

With the Docker stack, the `app` container serves the test environment on port 8080 and the `chrome`
container runs a headless Chrome sharing its network. Create a `behat.yml` file to use them:

```yaml
imports: ["behat.yml.dist"]

default:
    extensions:
        Behat\MinkExtension:
            base_url: "http://localhost:8080/"
            javascript_session: chrome_headless
```

Then run the scenarios inside the `app` container (arguments are passed to Behat):

```bash
$ castor test:behat --tags="@javascript"
```

Without Docker, run selenium-server-standalone instead:

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

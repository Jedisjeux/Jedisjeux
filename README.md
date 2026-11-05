<h1 align="center">
    Jedisjeux
    <br />
    <a href="https://github.com/Jedisjeux/Jedisjeux/actions/workflows/ci.yaml" title="Build status" target="_blank">
        <img src="https://github.com/Jedisjeux/Jedisjeux/actions/workflows/ci.yaml/badge.svg" />
    </a>
</h1>

[Jedisjeux](https://www.jedisjeux.net) is a PHP boardgame website, based on [Symfony Framework](http://symfony.com/) and [Sylius](http://sylius.org/).

Documentation
-------------

Documentation is available at [docs.jedisjeux.net](https://jedisjeux.readthedocs.io/en/latest/).

Installation
------------

The development stack runs on Docker and is driven by [Castor](https://castor.jolicode.com/): PHP 8.2 with nginx in the
`app` container, MariaDB 10.6, and a headless Chrome for the JavaScript Behat scenarios. Build and start it:

```bash
castor docker:build
castor docker:up
```

Then install the PHP dependencies, the front-end packages and the database:

```bash
castor app:install
castor app:assets
castor app:db:migrate
castor app:symfony sylius:fixtures:load
```

The front-end assets are served by [AssetMapper](https://symfony.com/doc/current/frontend/asset_mapper.html), no Node.js
build is needed: `castor app:assets` downloads the JavaScript packages of `importmap.php`.

Open `https://jedisjeux.test/` in your web browser to enjoy Jedisjeux website in a development environment.
`castor docker:about` lists every URL of the stack, and `castor docker:doctor` says how to fix your environment
when something goes wrong.

Run any Symfony or Composer command through Castor:

```bash
castor app:symfony debug:router
castor app:composer require vendor/package
```

Tests
-----

Prepare the test database (the test environment uses its own `jdj_test` database, so the tests never purge the
development one):

```bash
castor app:symfony --env=test doctrine:database:create
castor app:symfony --env=test doctrine:migrations:migrate -n
```

Then run [phpspec](https://phpspec.net), [PHPUnit](https://phpunit.de) and [Behat](https://behat.org):

```bash
castor test:phpspec
castor test:phpunit
castor test:behat
```

The `app` container serves the test environment on port 8080 and the `chrome` container shares its network, so Behat
runs inside the `app` container. Create a `behat.yml` file to use them:

```yaml
imports: ["behat.yml.dist"]

default:
    extensions:
        Behat\MinkExtension:
            base_url: "http://localhost:8080/"
            javascript_session: chrome_headless
```

Arguments are passed to Behat, for instance to run only the JavaScript scenarios:

```bash
castor test:behat --tags="@javascript"
```

Quality
-------

```bash
castor app:qa:cs
castor app:qa:phpstan
castor app:qa:psalm
```

Deployment
----------

The application is deployed to production with [Capistrano](https://capistranorb.com/), run in a Ruby container:

```bash
castor app:deploy
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

.. index::
   single: Fixtures

Fixtures
========

Fixtures are used mainly for testing, but also for having your website in a certain state, having defined data
- they ensure that there is a fixed environment in which your application is working.

.. note::

   They way Fixtures are designed in Jedisjeux is well described in the `FixturesBundle documentation <http://docs.sylius.org/en/latest/components_and_bundles/bundles/SyliusFixturesBundle/index.html>`_.

What are the available fixtures in Jedisjeux?
---------------------------------------------

To check what fixtures are defined in Jedisjeux run:

.. code-block:: bash

   $ php bin/console sylius:fixtures:list

How to load Jedisjeux fixtures?
-------------------------------

The recommended way to load the predefined set of Jedisjeux fixtures is here:

.. code-block:: bash

   $ php bin/console sylius:fixtures:load

What data is loaded by fixtures in Jedisjeux?
---------------------------------------------

All files that serve for loading fixtures of Jedisjeux are placed in the ``AppBundle/Fixture/*`` directory.

And the specified data for fixtures is stored in the
``app/config/app/sylius/sylius_fixtures.yml`` file.

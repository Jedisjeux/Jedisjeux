Production settings
===================

PHP settings
------------

Jedisjeux requires php 7.3.

For available list of packages you can read `php73_packages`_.

.. code-block:: bash

    $ sudo yum-config-manager --enable remi-php73
    $ sudo yum install php mod_php php-cli php-intl phpw-pdo phpw-xml php-gd php-mysql


In order to know pathname of php.ini, you can use this following command.

.. code-block:: bash

    $ php --ini

Edit php.ini and change these parameters.

.. code-block:: bash

    date.timezone = Europe/Paris
    memory_limit = 2048M
    upload_max_filesize = 10M
    post_max_size = 20M


Apache settings
---------------

Virtual host
~~~~~~~~~~~~

For more details, you can read `virtualHostsTutorial`_.

.. code-block:: bash

    $ touch /etc/httpd/sites-available/01-jedisjeux.conf

.. code-block:: bash

    <VirtualHost *:80>
            ServerAdmin lc.fremont@gmail.com
            DocumentRoot /home/jedisjeux/current/web/
            <Directory "/home/jedisjeux/current/web">
                    DirectoryIndex app.php
                    Options Indexes FollowSymLinks MultiViews
                    AllowOverride All
            </Directory>
            ServerName XX.XXX.XX.XXX
            ServerAlias XX.XXX.XX.XXX
            CustomLog logs/ovh-access_log combined
            ErrorLog  /var/log/httpd/jedisjeux-error_log
    </VirtualHost>

Restart apache
~~~~~~~~~~~~~~

.. code-block:: bash

    service httpd restart

Install ssh key
---------------

.. code-block:: bash

    $ ssh-keygen

Enter blank paraphrase
And confirm blank paraphrase

User permissions
----------------

Adding jedisjeux to apache group

.. code-block:: bash

    $ usermod -a -G apache jedisjeux

Ensure access
-------------

.. code-block:: bash

    $ chmod g-w /home/jedisjeux
    $ chmod 700 /home/jedisjeux/.ssh
    $ chmod 600 /home/jedisjeux/.ssh/authorized_keys


.. _virtualHostsTutorial: https://www.digitalocean.com/community/tutorials/how-to-set-up-apache-virtual-hosts-on-centos-7
.. _php73_packages: https://webtatic.com/packages/php73/

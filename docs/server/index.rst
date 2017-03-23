Installation
============

Configuration d'Apache
----------------------

Virtual host
~~~~~~~~~~~~

.. code-block:: bash

    $ touch /etc/httpd/vhosts/01-jedisjeux.conf

.. code-block:: bash

    <VirtualHost *:80>
            ServerAdmin lc.fremont@gmail.com
            DocumentRoot /home/jedisjeux/current/web/
            <Directory "/home/jedisjeux/current/web">
                    DirectoryIndex app.php
                    Options Indexes FollowSymLinks MultiViews
                    AllowOverride All
            </Directory>
            ServerName 92.243.10.152
            ServerAlias 92.243.10.152
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

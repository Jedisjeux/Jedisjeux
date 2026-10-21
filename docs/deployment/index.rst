Deployment
==========

Authorized keys API
-------------------

Adding ssh authorized keys for server on your local computer

.. code-block:: bash

    $ cat ~/.ssh/id_rsa.pub | ssh jedisjeux@92.243.10.152 "cat - >> ~/.ssh/authorized_keys"

and enter the correct password for username "jedisjeux" on server


Deploy
------

Capistrano runs inside a Docker container (no Ruby needed on your machine).
Your SSH agent is forwarded to the container, so make sure your key is loaded (``ssh-add``).
The current git branch is deployed.

Deploy the staging environment
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

.. code-block:: bash

    $ castor app:deploy staging

Deploy the production environment
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

.. code-block:: bash

    $ castor app:deploy production

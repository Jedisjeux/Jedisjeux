Deployment
==========

Authorized keys API
-------------------

Adding ssh authorized keys for server on your local computer

.. code-block:: bash

    $ cat ~/.ssh/id_rsa.pub | ssh jedisjeux@51.75.125.242 "cat - >> ~/.ssh/authorized_keys"

and enter the correct password for username "jedisjeux" on server


Deploy
------

Capistrano runs inside a Docker container (no Ruby needed on your machine).
Your SSH agent is forwarded to the container, so make sure your key is loaded (``ssh-add``).
The current git branch is deployed.

From your machine
~~~~~~~~~~~~~~~~~

.. code-block:: bash

    $ castor app:deploy

From GitHub Actions
~~~~~~~~~~~~~~~~~~~

Run the "Deploy" workflow from the Actions tab and pick the branch to deploy.
It needs the following secrets on the ``production`` environment:

* ``SSH_PRIVATE_KEY``: a private key authorized for ``jedisjeux@51.75.125.242``
* ``SSH_KNOWN_HOSTS``: the output of ``ssh-keyscan 51.75.125.242``

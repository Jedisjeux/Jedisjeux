Installation
============

Install ssh key
---------------

.. code-block:: bash

    $ ssh-keygen

Enter blank paraphrase
And confirm blank paraphrase

Ensure access
-------------

.. code-block:: bash

    $ chmod g-w /home/jedisjeux
    $ chmod 700 /home/jedisjeux/.ssh
    $ chmod 600 /home/jedisjeux/.ssh/authorized_keys

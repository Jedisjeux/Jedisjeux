People API
============

People API endpoint is ``/api/people``.

Index of all people
---------------------

To browse all people available you can call the following GET request:

.. code-block:: text

    GET /api/people/

Parameters
~~~~~~~~~~

page
    Number of the page, by default = 1
limit
    Number of items to display per page

Response
~~~~~~~~

Response will contain a paginated list of people.

.. code-block:: text

    STATUS: 200 OK

.. code-block:: json

    {
      "page": 1,
      "limit": 10,
      "pages": 459,
      "total": 4582,
      "_links": {
        "self": {
          "href": "\/app_dev.php\/api\/people\/?page=1&limit=10"
        },
        "first": {
          "href": "\/app_dev.php\/api\/people\/?page=1&limit=10"
        },
        "last": {
          "href": "\/app_dev.php\/api\/people\/?page=459&limit=10"
        },
        "next": {
          "href": "\/app_dev.php\/api\/people\/?page=2&limit=10"
        }
      },
      "_embedded": {
        "items": [
          {
            "last_name": "Seyfarth",
            "first_name": "Andreas",
            "slug": "andreas-seyfarth",
            "id": 1
          },
          {
            "last_name": "Vohwinkel",
            "first_name": "Franz",
            "slug": "franz-vohwinkel",
            "id": 2
          },
          {
            "last_name": "Faidutti",
            "first_name": "Bruno",
            "slug": "bruno-faidutti",
            "id": 3
          },
          {
            "last_name": "Delval",
            "first_name": "Julien",
            "slug": "julien-delval",
            "id": 4
          },
          {
            "last_name": "Magnin",
            "first_name": "Florence",
            "slug": "florence-magnin",
            "id": 5
          },
          {
            "last_name": "Mourier",
            "first_name": "Jean-Louis",
            "slug": "jean-louis-mourier",
            "id": 6
          },
          {
            "last_name": "Ulrich",
            "first_name": "Richard",
            "slug": "richard-ulrich",
            "id": 7
          },
          {
            "last_name": "Kramer",
            "first_name": "Wolfgang",
            "slug": "wolfgang-kramer",
            "id": 8
          },
          {
            "last_name": "Schacht",
            "first_name": "Michael",
            "slug": "michael-schacht",
            "id": 9
          },
          {
            "last_name": "Dion",
            "first_name": "Franck",
            "slug": "franck-dion",
            "id": 10
          }
        ]
      }
    }

Getting a single person
------------------------

You can view a single person by executing the following request:

.. code-block:: text

    GET /api/people/1

Response
~~~~~~~~

.. code-block:: text

    STATUS: 200 OK

.. code-block:: json

    {
      "last_name": "Seyfarth",
      "first_name": "Andreas",
      "website": "",
      "description": "Andreas Seyfarth est un auteur de jeux de soci\u00e9t\u00e9, particuli\u00e8rement c\u00e9l\u00e8bre pour avoir cr\u00e9\u00e9 Puerto Rico, consid\u00e9r\u00e9 par les sp\u00e9cialistes comme l'un des meilleurs jeux de soci\u00e9t\u00e9 modernes et r\u00e9compens\u00e9 en 2002 par le premier prix du Deutscher Spiele Preis. \r\n\r\nUn autre de ses jeux, Manhattan, avait d\u00e9j\u00e0 \u00e9t\u00e9 r\u00e9compens\u00e9 par le prestigieux Spiel des Jahres (jeu de l'ann\u00e9e allemand) en 1994. \r\n\r\nEn juillet 2006, c'est le Prix du jeu de l'ann\u00e9e allemand qui r\u00e9compense son nouveau jeu L'Aventure postale, co-r\u00e9alis\u00e9 avec Karen Seyfarth.\r\n\r\n(provenant de wikipedia.fr)",
      "slug": "andreas-seyfarth"
    }

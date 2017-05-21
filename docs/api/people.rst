People API
==========

People API endpoint is ``/api/people``.

Index of all people
-------------------

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
      "pages": 629,
      "total": 6287,
      "_links": {
        "self": {
          "href": "/api/people/?page=1&limit=10"
        },
        "first": {
          "href": "/api/people/?page=1&limit=10"
        },
        "last": {
          "href": "/api/people/?page=629&limit=10"
        },
        "next": {
          "href": "/api/people/?page=2&limit=10"
        }
      },
      "_embedded": {
        "items": [
          {
            "image": {
              "default": "http://92.243.10.152/media/cache/default/uploads/img/auteur1.jpg",
              "thumbnail": "http://92.243.10.152/media/cache/thumbnail/uploads/img/auteur1.jpg",
              "magazine_item": "http://92.243.10.152/media/cache/resolve/magazine_item/uploads/img/auteur1.jpg",
              "path": "auteur1.jpg",
              "id": 12355,
              "main": true
            },
            "full_name": "Andreas Seyfarth",
            "last_name": "Seyfarth",
            "first_name": "Andreas",
            "slug": "andreas-seyfarth",
            "id": 1
          },
          {
            "image": {
              "default": "http://92.243.10.152/media/cache/default/uploads/img/auteur2.jpg",
              "thumbnail": "http://92.243.10.152/media/cache/thumbnail/uploads/img/auteur2.jpg",
              "magazine_item": "http://92.243.10.152/media/cache/resolve/magazine_item/uploads/img/auteur2.jpg",
              "path": "auteur2.jpg",
              "id": 12347,
              "main": true
            },
            "full_name": "Franz Vohwinkel",
            "last_name": "Vohwinkel",
            "first_name": "Franz",
            "slug": "franz-vohwinkel",
            "id": 2
          },
          {
            "image": {
              "default": "http://92.243.10.152/media/cache/resolve/default/uploads/img/auteur3.jpg",
              "thumbnail": "http://92.243.10.152/media/cache/resolve/thumbnail/uploads/img/auteur3.jpg",
              "magazine_item": "http://92.243.10.152/media/cache/resolve/magazine_item/uploads/img/auteur3.jpg",
              "path": "auteur3.jpg",
              "id": 12353,
              "main": true
            },
            "full_name": "Bruno Faidutti",
            "last_name": "Faidutti",
            "first_name": "Bruno",
            "slug": "bruno-faidutti",
            "id": 3
          },
          {
            "image": {
              "default": "http://92.243.10.152/media/cache/default/uploads/img/auteur4.jpg",
              "thumbnail": "http://92.243.10.152/media/cache/resolve/thumbnail/uploads/img/auteur4.jpg",
              "magazine_item": "http://92.243.10.152/media/cache/resolve/magazine_item/uploads/img/auteur4.jpg",
              "path": "auteur4.jpg",
              "id": 12858,
              "main": true
            },
            "full_name": "Julien Delval",
            "last_name": "Delval",
            "first_name": "Julien",
            "slug": "julien-delval",
            "id": 4
          },
          {
            "full_name": "Florence Magnin",
            "last_name": "Magnin",
            "first_name": "Florence",
            "slug": "florence-magnin",
            "id": 5
          },
          {
            "full_name": "Jean-Louis Mourier",
            "last_name": "Mourier",
            "first_name": "Jean-Louis",
            "slug": "jean-louis-mourier",
            "id": 6
          },
          {
            "image": {
              "default": "http://92.243.10.152/media/cache/default/uploads/img/auteur7.jpg",
              "thumbnail": "http://92.243.10.152/media/cache/thumbnail/uploads/img/auteur7.jpg",
              "magazine_item": "http://92.243.10.152/media/cache/resolve/magazine_item/uploads/img/auteur7.jpg",
              "path": "auteur7.jpg",
              "id": 12348,
              "main": true
            },
            "full_name": "Richard Ulrich",
            "last_name": "Ulrich",
            "first_name": "Richard",
            "slug": "richard-ulrich",
            "id": 7
          },
          {
            "image": {
              "default": "http://92.243.10.152/media/cache/resolve/default/uploads/img/auteur8.jpg",
              "thumbnail": "http://92.243.10.152/media/cache/thumbnail/uploads/img/auteur8.jpg",
              "magazine_item": "http://92.243.10.152/media/cache/resolve/magazine_item/uploads/img/auteur8.jpg",
              "path": "auteur8.jpg",
              "id": 12345,
              "main": false
            },
            "full_name": "Wolfgang Kramer",
            "last_name": "Kramer",
            "first_name": "Wolfgang",
            "slug": "wolfgang-kramer",
            "id": 8
          },
          {
            "image": {
              "default": "http://92.243.10.152/media/cache/resolve/default/uploads/img/auteur9.jpg",
              "thumbnail": "http://92.243.10.152/media/cache/thumbnail/uploads/img/auteur9.jpg",
              "magazine_item": "http://92.243.10.152/media/cache/resolve/magazine_item/uploads/img/auteur9.jpg",
              "path": "auteur9.jpg",
              "id": 12366,
              "main": false
            },
            "full_name": "Michael Schacht",
            "last_name": "Schacht",
            "first_name": "Michael",
            "slug": "michael-schacht",
            "id": 9
          },
          {
            "image": {
              "default": "http://92.243.10.152/media/cache/resolve/default/uploads/img/auteur10.jpg",
              "thumbnail": "http://92.243.10.152/media/cache/thumbnail/uploads/img/auteur10.jpg",
              "magazine_item": "http://92.243.10.152/media/cache/resolve/magazine_item/uploads/img/auteur10.jpg",
              "path": "auteur10.jpg",
              "id": 12772,
              "main": true
            },
            "full_name": "Franck Dion",
            "last_name": "Dion",
            "first_name": "Franck",
            "slug": "franck-dion",
            "id": 10
          }
        ]
      }
    }

Getting a single person
-----------------------

You can view a single person by executing the following request:

.. code-block:: text

    GET /api/people/1

Response
~~~~~~~~

.. code-block:: text

    STATUS: 200 OK

.. code-block:: json

    {
      "image": {
        "default": "http://92.243.10.152/media/cache/default/uploads/img/auteur1.jpg",
        "thumbnail": "http://92.243.10.152/media/cache/thumbnail/uploads/img/auteur1.jpg",
        "magazine_item": "http://92.243.10.152/media/cache/resolve/magazine_item/uploads/img/auteur1.jpg"
      },
      "full_name": "Andreas Seyfarth",
      "last_name": "Seyfarth",
      "first_name": "Andreas",
      "description": "Andreas Seyfarth est un auteur de jeux de société, particulièrement célèbre pour avoir créé Puerto Rico, considéré par les spécialistes comme l'un des meilleurs jeux de société modernes et récompensé en 2002 par le premier prix du Deutscher Spiele Preis. \r\n\r\nUn autre de ses jeux, Manhattan, avait déjà été récompensé par le prestigieux Spiel des Jahres (jeu de l'année allemand) en 1994. \r\n\r\nEn juillet 2006, c'est le Prix du jeu de l'année allemand qui récompense son nouveau jeu L'Aventure postale, co-réalisé avec Karen Seyfarth.\r\n\r\n(provenant de wikipedia.fr)",
      "slug": "andreas-seyfarth"
    }

Products API
============

Getting a single product
------------------------

You can view a single product by executing the following request:

.. code-block:: text

    GET /api/products/game-1

Response
^^^^^^^^

.. code-block:: text

    STATUS: 200 OK

Collection of Products
----------------------

To retrieve a paginated list of products you will need to call the ``/api/products/`` endpoint with the ``GET`` method.

Definition
^^^^^^^^^^

.. code-block:: text

    GET /api/v1/products/

+-------------------------------------+----------------+---------------------------------------------------+
| Parameter                           | Parameter type | Description                                       |
+=====================================+================+===================================================+
| Authorization                       | header         | Token received during authentication              |
+-------------------------------------+----------------+---------------------------------------------------+
| limit                               | query          | *(optional)* Number of items to display per page, |
|                                     |                | by default = 10                                   |
+-------------------------------------+----------------+---------------------------------------------------+
| sorting['nameOfField']['direction'] | query          | *(optional)* Field and direction of sorting,      |
|                                     |                | by default 'asc' and 'name'                       |
+-------------------------------------+----------------+---------------------------------------------------+

To see the first page of all products use the below method:

Example
^^^^^^^

.. code-block:: bash

    $ curl http://demo.sylius.org/api/products/ \
        -H "Authorization: Bearer SampleToken" \
        -H "Accept: application/json"

Exemplary Response
^^^^^^^^^^^^^^^^^^

.. code-block:: text

    STATUS: 200 OK

.. code-block:: json

    {
      "page": 1,
      "limit": 10,
      "pages": 3,
      "total": 22,
      "_links": {
        "self": {
          "href": "\/app_test.php\/api\/products\/?page=1&limit=10"
        },
        "first": {
          "href": "\/app_test.php\/api\/products\/?page=1&limit=10"
        },
        "last": {
          "href": "\/app_test.php\/api\/products\/?page=3&limit=10"
        },
        "next": {
          "href": "\/app_test.php\/api\/products\/?page=2&limit=10"
        }
      },
      "_embedded": {
        "items": [
          {
            "name": "Product 10",
            "id": 215,
            "code": "PRODUCT_10",
            "options": [

            ],
            "slug": "product-10",
            "min_age": 12,
            "min_player_count": 2,
            "max_player_count": 7
          },
          {
            "name": "Product 11",
            "id": 216,
            "code": "PRODUCT_11",
            "options": [

            ],
            "slug": "product-11",
            "min_age": 3,
            "min_player_count": 2,
            "max_player_count": 8
          },
          {
            "name": "Product 12",
            "id": 217,
            "code": "PRODUCT_12",
            "options": [

            ],
            "slug": "product-12",
            "min_age": 9,
            "min_player_count": 2,
            "max_player_count": 4
          },
          {
            "name": "Product 13",
            "id": 218,
            "code": "PRODUCT_13",
            "options": [

            ],
            "slug": "product-13",
            "min_age": 11,
            "min_player_count": 3,
            "max_player_count": 5
          },
          {
            "name": "Product 14",
            "id": 219,
            "code": "PRODUCT_14",
            "options": [

            ],
            "slug": "product-14",
            "min_age": 3,
            "min_player_count": 2,
            "max_player_count": 5
          },
          {
            "name": "Product 15",
            "id": 220,
            "code": "PRODUCT_15",
            "options": [

            ],
            "slug": "product-15",
            "min_age": 12,
            "min_player_count": 3,
            "max_player_count": 5
          },
          {
            "name": "Product 16",
            "id": 221,
            "code": "PRODUCT_16",
            "options": [

            ],
            "slug": "product-16",
            "min_age": 5,
            "min_player_count": 2,
            "max_player_count": 7
          },
          {
            "name": "Product 17",
            "id": 222,
            "code": "PRODUCT_17",
            "options": [

            ],
            "slug": "product-17",
            "min_age": 9,
            "min_player_count": 3,
            "max_player_count": 4
          },
          {
            "name": "Product 18",
            "id": 223,
            "code": "PRODUCT_18",
            "options": [

            ],
            "slug": "product-18",
            "min_age": 10,
            "min_player_count": 2,
            "max_player_count": 6
          },
          {
            "name": "Product 19",
            "id": 224,
            "code": "PRODUCT_19",
            "options": [

            ],
            "slug": "product-19",
            "min_age": 8,
            "min_player_count": 2,
            "max_player_count": 7
          }
        ]
      }
    }
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
      "pages": 1,
      "total": 10,
      "_links": {
        "self": {
          "href": "/app_dev.php/api/products/?page=1&limit=10"
        },
        "first": {
          "href": "/app_dev.php/api/products/?page=1&limit=10"
        },
        "last": {
          "href": "/app_dev.php/api/products/?page=1&limit=10"
        }
      },
      "_embedded": {
        "items": [
          {
            "id": 8,
            "code": "Ab_exercitationem_ipsum",
            "attributes": [

            ],
            "variants": [
              {
                "id": 8,
                "code": "variant_59d53ca3bf532",
                "option_values": [

                ],
                "position": 0,
                "created_at": "2017-10-04T21:55:15+02:00",
                "updated_at": "2017-10-04T21:55:15+02:00",
                "translations": {
                  "fr_FR": {
                    "locale": "fr_FR",
                    "id": 8,
                    "name": "Ab exercitationem ipsum"
                  }
                },
                "translations_cache": [

                ],
                "current_locale": "fr_FR",
                "fallback_locale": "fr_FR"
              }
            ],
            "options": [

            ],
            "associations": [

            ],
            "created_at": "2017-05-29T17:46:44+02:00",
            "updated_at": "2017-10-04T21:55:15+02:00",
            "enabled": true,
            "translations": {
              "fr_FR": {
                "locale": "fr_FR",
                "id": 8,
                "name": "Ab exercitationem ipsum",
                "slug": "ab-exercitationem-ipsum",
                "description": "<p>Ut dolorem quis sint architecto aut quod. At consequuntur molestiae tempore et minima ea molestiae. Iure error et reiciendis voluptatum eveniet maiores vero.</p><p>Dolor veniam reiciendis eveniet saepe nihil. Et impedit quia omnis architecto ipsam. Id eligendi reprehenderit voluptate ab est repellat aut.</p><p>Totam quo dignissimos ut ea qui. Provident voluptatibus alias doloribus nobis. Consequatur pariatur facilis et accusamus est. Porro sit quia et a nulla error.</p><p>Sit repudiandae animi doloremque. Est nulla reiciendis rerum vel sit molestiae vitae atque. Cumque accusantium ab adipisci et alias.</p><p>Consequuntur voluptas est et temporibus suscipit tempora voluptatem adipisci. Iure suscipit quidem veniam consectetur laudantium. Qui rem ut ipsam sapiente aliquid.</p>",
                "short_description": "<p>Asperiores eum blanditiis id sequi dolor quaerat autem mollitia. Dolores quibusdam et labore ipsa neque quae tempore. Consequatur sed eaque qui enim sint. Numquam qui tempora velit ducimus. Nesciunt totam vero facilis et.</p><p>Totam magni dolore deserunt suscipit sunt itaque similique asperiores. Est tenetur culpa consectetur rem aliquid. Sit distinctio et est vero omnis quia consectetur. Harum id voluptatem aut autem distinctio ullam adipisci et.</p>"
              }
            },
            "translations_cache": [

            ],
            "current_locale": "fr_FR",
            "fallback_locale": "fr_FR",
            "name": "Ab exercitationem ipsum",
            "slug": "ab-exercitationem-ipsum"
          },
          {
            "id": 19,
            "code": "Error_quod_suscipit",
            "attributes": [

            ],
            "variants": [
              {
                "id": 19,
                "code": "variant_59d53ca958c5f",
                "option_values": [

                ],
                "position": 0,
                "created_at": "2017-10-04T21:55:21+02:00",
                "updated_at": "2017-10-04T21:55:21+02:00",
                "translations": {
                  "fr_FR": {
                    "locale": "fr_FR",
                    "id": 19,
                    "name": "Error quod suscipit"
                  }
                },
                "translations_cache": [

                ],
                "current_locale": "fr_FR",
                "fallback_locale": "fr_FR"
              }
            ],
            "options": [

            ],
            "associations": [

            ],
            "created_at": "2017-05-05T17:33:14+02:00",
            "updated_at": "2017-10-04T21:55:21+02:00",
            "enabled": true,
            "translations": {
              "fr_FR": {
                "locale": "fr_FR",
                "id": 19,
                "name": "Error quod suscipit",
                "slug": "error-quod-suscipit",
                "description": "<p>Nulla minima provident magnam est facilis error natus voluptas. Ducimus deserunt rerum maxime dolor. Ad voluptatem veniam omnis reiciendis maiores modi. Est aut qui autem illum laboriosam reprehenderit iste et.</p><p>Eum nostrum occaecati dolores numquam dolore quidem omnis. Quod non nemo nihil ut nam distinctio laborum. Et ut molestiae quasi ut cum. Dolores corporis vel autem ullam vitae. Dolorem nihil vel perspiciatis.</p><p>Harum et sunt corrupti ad mollitia commodi. Provident vel rem odio non asperiores quos tempora. Fuga doloremque aut eaque consequuntur. Provident voluptatem et iusto. Facilis dolores unde ut atque et omnis vitae.</p><p>Non nemo dolores a. Error eum fugit sint cupiditate magnam et officiis. Quidem ullam voluptas quia laudantium non molestias quisquam provident. Labore autem esse et quam.</p><p>Ut voluptates maxime molestias dignissimos sit nobis. Nihil qui qui id sequi. Non vel porro eum voluptatem quia maiores. Veritatis consequatur earum veritatis.</p>",
                "short_description": "<p>Incidunt perferendis qui beatae. Aliquam in qui mollitia sint qui placeat. Omnis expedita neque consequatur rerum.</p><p>Sapiente ut et consequatur dolorum enim impedit. Dolores quibusdam quaerat et reprehenderit sit ut ea. Consectetur pariatur non quis quia ipsum sapiente. Officia mollitia tenetur nihil non odio odit eos.</p>"
              }
            },
            "translations_cache": [

            ],
            "current_locale": "fr_FR",
            "fallback_locale": "fr_FR",
            "name": "Error quod suscipit",
            "slug": "error-quod-suscipit"
          },
          {
            "id": 21,
            "code": "Et_quas_iure",
            "attributes": [

            ],
            "variants": [
              {
                "id": 21,
                "code": "variant_59d53caa5c3c7",
                "option_values": [

                ],
                "position": 0,
                "created_at": "2017-10-04T21:55:22+02:00",
                "updated_at": "2017-10-04T21:55:22+02:00",
                "translations": {
                  "fr_FR": {
                    "locale": "fr_FR",
                    "id": 21,
                    "name": "Et quas iure"
                  }
                },
                "translations_cache": [

                ],
                "current_locale": "fr_FR",
                "fallback_locale": "fr_FR"
              }
            ],
            "options": [

            ],
            "associations": [

            ],
            "created_at": "2017-01-10T06:34:38+01:00",
            "updated_at": "2017-10-04T21:55:22+02:00",
            "enabled": true,
            "translations": {
              "fr_FR": {
                "locale": "fr_FR",
                "id": 21,
                "name": "Et quas iure",
                "slug": "et-quas-iure",
                "description": "<p>Ea doloribus quo voluptate minus expedita quae ad. Qui magni qui minus qui eligendi. Ea at et doloribus ut voluptas illo veritatis fuga.</p><p>Ipsum in qui eveniet. Minus saepe voluptatem rerum voluptate blanditiis.</p><p>Quod quisquam animi quia sed quia rerum excepturi reprehenderit. Ut aspernatur consequatur quia dolorem libero sed.</p><p>Voluptatem libero quia et aperiam aut aut eum. Unde odio blanditiis voluptatem ab impedit impedit velit. Error aut et sint facilis. Doloremque velit vel itaque hic vel expedita recusandae.</p><p>Recusandae possimus expedita porro qui sit corporis ab. Quisquam impedit aspernatur quod atque. Ut qui corporis quo tenetur aliquid modi.</p>",
                "short_description": "<p>Dolores tempore numquam eos voluptas magnam. Voluptatibus sint repellat architecto libero et dolorem. Voluptates sed tempore quia voluptas quasi.</p><p>Eos aut nostrum fuga quaerat vel deserunt qui. Deserunt aut ut nihil est. Illum tenetur libero nam voluptatem sed laudantium corrupti nostrum.</p>"
              }
            },
            "translations_cache": [

            ],
            "current_locale": "fr_FR",
            "fallback_locale": "fr_FR",
            "name": "Et quas iure",
            "slug": "et-quas-iure"
          },
          {
            "id": 16,
            "code": "Id_cum_nemo",
            "attributes": [

            ],
            "variants": [
              {
                "id": 16,
                "code": "variant_59d53ca7dc912",
                "option_values": [

                ],
                "position": 0,
                "created_at": "2017-10-04T21:55:19+02:00",
                "updated_at": "2017-10-04T21:55:19+02:00",
                "translations": {
                  "fr_FR": {
                    "locale": "fr_FR",
                    "id": 16,
                    "name": "Id cum nemo"
                  }
                },
                "translations_cache": [

                ],
                "current_locale": "fr_FR",
                "fallback_locale": "fr_FR"
              }
            ],
            "options": [

            ],
            "associations": [

            ],
            "created_at": "2017-02-20T12:23:20+01:00",
            "updated_at": "2017-10-04T21:55:19+02:00",
            "enabled": true,
            "translations": {
              "fr_FR": {
                "locale": "fr_FR",
                "id": 16,
                "name": "Id cum nemo",
                "slug": "id-cum-nemo",
                "description": "<p>Omnis odio ut odio qui expedita repellat. Non aut omnis nam vel dignissimos. Asperiores quisquam eligendi qui qui quidem. Et pariatur ex doloremque itaque et repellendus voluptas.</p><p>Eligendi ducimus quidem a quisquam amet. Sunt rerum tenetur fugit nihil atque vel voluptatum. Nihil qui maiores porro porro occaecati aut.</p><p>Harum praesentium et nulla non architecto officiis ea. Voluptatem tempora rerum tempore ut. Itaque ut et nulla fuga in eaque id eum.</p><p>Illo non rerum id voluptate id sequi iure. Ut nihil harum impedit dolores natus enim. Animi ipsam ducimus enim veniam sint nulla ea. Aut et illum dolore atque cum.</p><p>Quo et molestiae corrupti libero totam nesciunt ullam. Nisi odio totam dolor et. Occaecati sed sequi dignissimos maxime quia adipisci omnis placeat. Omnis dolores aspernatur dolore.</p>",
                "short_description": "<p>Ut quisquam minima provident temporibus porro. Iusto cumque voluptas doloribus. Unde ratione alias voluptate sapiente excepturi.</p><p>Eveniet sunt magnam omnis recusandae. Deserunt quasi eos eaque laborum similique et. Necessitatibus vero corrupti et ut.</p>"
              }
            },
            "translations_cache": [

            ],
            "current_locale": "fr_FR",
            "fallback_locale": "fr_FR",
            "name": "Id cum nemo",
            "slug": "id-cum-nemo"
          },
          {
            "id": 14,
            "code": "Iste_doloribus_sed",
            "attributes": [

            ],
            "variants": [
              {
                "id": 14,
                "code": "variant_59d53ca7136f2",
                "option_values": [

                ],
                "position": 0,
                "created_at": "2017-10-04T21:55:19+02:00",
                "updated_at": "2017-10-04T21:55:19+02:00",
                "translations": {
                  "fr_FR": {
                    "locale": "fr_FR",
                    "id": 14,
                    "name": "Iste doloribus sed"
                  }
                },
                "translations_cache": [

                ],
                "current_locale": "fr_FR",
                "fallback_locale": "fr_FR"
              }
            ],
            "options": [

            ],
            "associations": [

            ],
            "created_at": "2016-10-10T06:00:43+02:00",
            "updated_at": "2017-10-04T21:55:19+02:00",
            "enabled": true,
            "translations": {
              "fr_FR": {
                "locale": "fr_FR",
                "id": 14,
                "name": "Iste doloribus sed",
                "slug": "iste-doloribus-sed",
                "description": "<p>Quo repellendus quam voluptas ut quos omnis est. Voluptatem laborum animi accusantium voluptatem ipsum est sunt vero. Qui est odio nisi rerum qui. Dolores sunt eveniet qui. Voluptatum tempore voluptas aliquid alias.</p><p>Blanditiis rerum expedita sit facilis totam sunt sit. Consequatur quam fugiat aliquid numquam facilis ut. Ab itaque asperiores aut nihil voluptatem. Suscipit consequatur quis natus expedita.</p><p>Quos nam voluptatem minima ex. Consequatur alias voluptatem est voluptate.</p><p>Facilis aliquid cupiditate aperiam modi quia cum. Esse cumque accusamus harum sed magni. Unde quos modi harum voluptas deleniti.</p><p>Dolores aut deleniti reiciendis sunt. Praesentium quidem nobis illo consequatur doloremque sequi. Aliquid et dolore atque officia.</p>",
                "short_description": "<p>Consequatur commodi dolore occaecati dolore sequi esse accusamus. A dolores corporis earum ad molestiae.</p><p>Ut et vero sequi. Impedit culpa neque quaerat impedit quam dolor recusandae. Aut sint est dolorem tempore dolorem provident. Omnis dolor molestias qui est et ut. Rerum omnis distinctio cum est.</p>"
              }
            },
            "translations_cache": [

            ],
            "current_locale": "fr_FR",
            "fallback_locale": "fr_FR",
            "name": "Iste doloribus sed",
            "slug": "iste-doloribus-sed"
          },
          {
            "id": 18,
            "code": "Porro_eum_illum",
            "attributes": [

            ],
            "variants": [
              {
                "id": 18,
                "code": "variant_59d53ca8e952f",
                "option_values": [

                ],
                "position": 0,
                "created_at": "2017-10-04T21:55:20+02:00",
                "updated_at": "2017-10-04T21:55:20+02:00",
                "translations": {
                  "fr_FR": {
                    "locale": "fr_FR",
                    "id": 18,
                    "name": "Porro eum illum"
                  }
                },
                "translations_cache": [

                ],
                "current_locale": "fr_FR",
                "fallback_locale": "fr_FR"
              }
            ],
            "options": [

            ],
            "associations": [

            ],
            "created_at": "2017-07-18T19:20:35+02:00",
            "updated_at": "2017-10-04T21:55:20+02:00",
            "enabled": true,
            "translations": {
              "fr_FR": {
                "locale": "fr_FR",
                "id": 18,
                "name": "Porro eum illum",
                "slug": "porro-eum-illum",
                "description": "<p>Illo occaecati consequatur consequuntur praesentium dolores nostrum aliquam. Eveniet modi qui nobis expedita. Necessitatibus cumque perspiciatis non sapiente repellat.</p><p>Facilis ipsam est quis incidunt voluptatibus. Et est velit et nesciunt nihil esse officia. Dolorum id explicabo pariatur suscipit libero debitis eius provident.</p><p>Rem dolores sunt est. Dolore omnis quia impedit harum ipsa.</p><p>Commodi quae possimus excepturi aspernatur culpa. Eos dolores aut sunt. Saepe et rerum unde qui non. Quibusdam commodi reprehenderit itaque repellendus. Ad iste eum nihil dicta occaecati magnam amet itaque.</p><p>Adipisci animi voluptatem et ullam. Repellendus maiores quia est amet nesciunt. Vitae quasi ut veniam illo. Tempore nihil totam sunt facilis odio et reiciendis.</p>",
                "short_description": "<p>Vero ducimus a officia et qui quae beatae. Ut nam qui qui quas et et rerum. Non quis repellat harum atque magnam.</p><p>Laboriosam tempore asperiores delectus cum. Dolorum aut fuga exercitationem et et eaque tenetur. Est et autem enim eum. Incidunt eos assumenda tenetur et.</p>"
              }
            },
            "translations_cache": [

            ],
            "current_locale": "fr_FR",
            "fallback_locale": "fr_FR",
            "name": "Porro eum illum",
            "slug": "porro-eum-illum"
          },
          {
            "id": 1,
            "code": "Puerto_Rico",
            "attributes": [

            ],
            "variants": [
              {
                "id": 1,
                "code": "variant_59d53ca196c5b",
                "option_values": [

                ],
                "position": 0,
                "created_at": "2017-10-04T21:55:13+02:00",
                "updated_at": "2017-10-04T21:55:13+02:00",
                "translations": {
                  "fr_FR": {
                    "locale": "fr_FR",
                    "id": 1,
                    "name": "Puerto Rico"
                  }
                },
                "translations_cache": [

                ],
                "current_locale": "fr_FR",
                "fallback_locale": "fr_FR"
              }
            ],
            "options": [

            ],
            "associations": [

            ],
            "created_at": "2017-10-04T21:55:13+02:00",
            "updated_at": "2017-10-04T21:55:13+02:00",
            "enabled": true,
            "translations": {
              "fr_FR": {
                "locale": "fr_FR",
                "id": 1,
                "name": "Puerto Rico",
                "slug": "puerto-rico",
                "description": "<p>Fugit ut fugiat libero et. Est sed laboriosam et quia. Necessitatibus odio quibusdam aperiam quo qui quam.</p><p>Dolore quam in voluptatum accusantium explicabo. Est et quidem totam enim quam. Eum illum officia magni voluptate. Minima commodi excepturi aut dolores.</p><p>Rerum sint ut sed voluptatem alias dolores nulla. A reiciendis aut ipsa voluptatem est reiciendis amet. Et recusandae quas adipisci laboriosam labore.</p><p>Autem molestias et velit velit labore. Quas doloremque et nam. Neque quo mollitia blanditiis eveniet. Aspernatur ipsam aut voluptas quaerat repudiandae.</p><p>Possimus fugit optio ipsum vel asperiores. Libero perspiciatis magni recusandae nisi qui facilis. Fugit alias sit aliquam adipisci illum impedit sed facilis. Magnam quis impedit provident dolor voluptas quae sit.</p>",
                "short_description": "<p>Le gouverneur de l'île de Puerto Rico reste à choisir.</p>\n<p>Celui des joueurs qui aura démontré les richesses de son quartier deviendra Gouverneur de Puerto Rico.</p>\n"
              }
            },
            "translations_cache": [

            ],
            "current_locale": "fr_FR",
            "fallback_locale": "fr_FR",
            "name": "Puerto Rico",
            "slug": "puerto-rico"
          },
          {
            "id": 3,
            "code": "Shogun",
            "attributes": [

            ],
            "variants": [
              {
                "id": 3,
                "code": "variant_59d53ca19ff89",
                "option_values": [

                ],
                "position": 0,
                "created_at": "2017-10-04T21:55:13+02:00",
                "updated_at": "2017-10-04T21:55:13+02:00",
                "translations": {
                  "fr_FR": {
                    "locale": "fr_FR",
                    "id": 3,
                    "name": "Shogun"
                  }
                },
                "translations_cache": [

                ],
                "current_locale": "fr_FR",
                "fallback_locale": "fr_FR"
              }
            ],
            "options": [

            ],
            "associations": [

            ],
            "created_at": "2017-10-04T21:55:13+02:00",
            "updated_at": "2017-10-04T21:55:13+02:00",
            "enabled": true,
            "translations": {
              "fr_FR": {
                "locale": "fr_FR",
                "id": 3,
                "name": "Shogun",
                "slug": "shogun",
                "description": "<p>Quisquam beatae eligendi libero saepe rem. Harum est ut ut odio iusto consequatur. Minima alias consequatur voluptate et dolor alias vitae. Qui non magni debitis. Dolore culpa similique maxime consequatur mollitia aspernatur.</p><p>Omnis qui asperiores in blanditiis. Corrupti eveniet eveniet et atque itaque ut. Enim voluptatem quam sit delectus minus quidem in. Et ut quis eaque.</p><p>Nisi ducimus aut et quisquam illo nostrum asperiores. Odit velit incidunt veritatis id commodi ad quam. Ut doloremque debitis et reiciendis. Voluptas ut eligendi sequi id et suscipit possimus.</p><p>Beatae iste tempore numquam. Ea dolores adipisci doloremque fugit officia blanditiis. Velit accusantium esse omnis rem qui. Rerum vero quo dolor dicta qui saepe doloremque et.</p><p>Qui error earum est aut dolorum et odit et. Molestias et inventore amet dolor. Quaerat quas velit eaque sequi autem ipsa.</p>",
                "short_description": "<p>Shogun est une version légèrement modifiée du jeu Wallenstein, sorti en 2002 chez le même éditeur. Le thème a changé mais les mécanismes sont quasi similaires à quelques points de règles près.</p>\n"
              }
            },
            "translations_cache": [

            ],
            "current_locale": "fr_FR",
            "fallback_locale": "fr_FR",
            "name": "Shogun",
            "slug": "shogun"
          },
          {
            "id": 2,
            "code": "Tigre_&_Euphrate",
            "attributes": [

            ],
            "variants": [
              {
                "id": 2,
                "code": "variant_59d53ca19cf1d",
                "option_values": [

                ],
                "position": 0,
                "created_at": "2017-10-04T21:55:13+02:00",
                "updated_at": "2017-10-04T21:55:13+02:00",
                "translations": {
                  "fr_FR": {
                    "locale": "fr_FR",
                    "id": 2,
                    "name": "Tigre & Euphrate"
                  }
                },
                "translations_cache": [

                ],
                "current_locale": "fr_FR",
                "fallback_locale": "fr_FR"
              }
            ],
            "options": [

            ],
            "associations": [

            ],
            "created_at": "2017-10-04T21:55:13+02:00",
            "updated_at": "2017-10-04T21:55:13+02:00",
            "enabled": true,
            "translations": {
              "fr_FR": {
                "locale": "fr_FR",
                "id": 2,
                "name": "Tigre & Euphrate",
                "slug": "tigre-euphrate",
                "description": "<p>Magni animi qui voluptas sapiente accusamus sequi et. Quidem saepe provident dolores velit qui. Cupiditate culpa voluptatum quos dolore sequi blanditiis est ut. Quia ducimus odio sed dolore rerum blanditiis error.</p><p>Numquam voluptas animi esse laboriosam. Quae id quaerat asperiores porro repellendus eos qui. Cumque sit temporibus quia placeat.</p><p>Dignissimos in sequi veritatis est. Omnis possimus voluptatem rerum aperiam voluptatum nemo earum. Molestiae illum eum incidunt ut. Cumque et quo consequatur placeat debitis molestias inventore.</p><p>Debitis et ab voluptatum. Ut aut quaerat fugiat dolores corrupti vero nostrum quia.</p><p>Explicabo optio dignissimos exercitationem architecto reprehenderit. Facere ipsa nemo delectus officiis non nostrum est. Modi est dignissimos omnis saepe perspiciatis asperiores repudiandae.</p>",
                "short_description": "<p>Faites prospérer des villes, des fermes, des temples. Érigez, en l'honneur des dieux, des monuments imprenables. Mais sur la route du pouvoir suprême, bien des conflits armés vous opposeront à vos voisins !</p>\n"
              }
            },
            "translations_cache": [

            ],
            "current_locale": "fr_FR",
            "fallback_locale": "fr_FR",
            "name": "Tigre & Euphrate",
            "slug": "tigre-euphrate"
          },
          {
            "id": 4,
            "code": "Yamataï",
            "attributes": [

            ],
            "variants": [
              {
                "id": 4,
                "code": "variant_59d53ca1a3f6d",
                "option_values": [

                ],
                "position": 0,
                "created_at": "2017-10-04T21:55:13+02:00",
                "updated_at": "2017-10-04T21:55:13+02:00",
                "translations": {
                  "fr_FR": {
                    "locale": "fr_FR",
                    "id": 4,
                    "name": "Yamataï"
                  }
                },
                "translations_cache": [

                ],
                "current_locale": "fr_FR",
                "fallback_locale": "fr_FR"
              }
            ],
            "options": [

            ],
            "associations": [

            ],
            "created_at": "2017-10-04T21:55:13+02:00",
            "updated_at": "2017-10-04T21:55:13+02:00",
            "enabled": true,
            "translations": {
              "fr_FR": {
                "locale": "fr_FR",
                "id": 4,
                "name": "Yamataï",
                "slug": "yamatai",
                "description": "<p>Qui dolor facere quo assumenda sit alias in quisquam. Totam occaecati ipsa harum qui deleniti voluptatem.</p><p>Et voluptatem in non expedita modi quis. Nemo ea ut et temporibus consequatur. Consectetur officiis libero aut perspiciatis ut nihil est.</p><p>Odit doloribus et nostrum quia voluptatem. Distinctio voluptatem eligendi quod est. Sequi nisi iusto officia ut voluptates. Aspernatur libero id error consequatur odit sunt fugit.</p><p>Facere vero earum fugiat non sunt aut est. Hic iusto eum sed molestiae totam ut. Reiciendis temporibus maxime quibusdam eaque aut. Omnis et id ut explicabo.</p><p>Animi consequuntur quod omnis voluptatem illo. Voluptate amet ea in rerum pariatur et qui. Ipsum ducimus sapiente nemo necessitatibus autem et. Placeat quo tempore repellendus unde.</p>",
                "short_description": "<p>La reine Himiko a confié une mission prestigieuse à tous ses bâtisseurs : faire de la capitale de Yamataï la perle du royaume.</p>\n"
              }
            },
            "translations_cache": [

            ],
            "current_locale": "fr_FR",
            "fallback_locale": "fr_FR",
            "name": "Yamataï",
            "slug": "yamatai"
          }
        ]
      }
    }
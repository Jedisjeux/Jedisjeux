---
title: Jeux
description: Récupérer un jeu avec l'API REST.
---

Les points d'entrée de l'API sont préfixés par `/api`.

## Récupérer un jeu

```http
GET /api/products/{code}
Accept: application/json
```

`{code}` est le code du jeu, affiché dans la colonne **Code** de la liste des jeux de l'administration.

### Exemple

```sh
curl https://jedisjeux.test/api/products/alchimia -H "Accept: application/json"
```

### Réponse

`200 OK`, avec le jeu (réponse abrégée) :

```json
{
  "name": "Alchimia",
  "id": 1192,
  "code": "alchimia",
  "short_description": "<p>Transformez le plomb en or dans votre laboratoire d'alchimiste… si les dés le veulent bien.</p>",
  "image": {
    "default": "https://jedisjeux.test/media/cache/resolve/default/uploads/img/6aeb2a24148704.27121648.jpg",
    "thumbnail": "https://jedisjeux.test/media/cache/thumbnail/uploads/img/6aeb2a24148704.27121648.jpg",
    "path": "6aeb2a24148704.27121648.jpg"
  },
  "slug": "alchimia",
  "min_age": 12,
  "min_player_count": 1,
  "max_player_count": 4,
  "min_duration": 60,
  "max_duration": 75,
  "average_rating": 7.3
}
```

La réponse complète contient aussi les traductions, les attributs, les associations, ainsi que le détail des mécanismes et des thèmes.

Un code inconnu renvoie `404 Not Found`.

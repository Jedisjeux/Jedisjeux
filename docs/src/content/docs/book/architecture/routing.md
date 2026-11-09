---
title: Routage
description: Déclarer les routes de l'administration, de l'API et du front.
sidebar:
  order: 4
---

SyliusResourceBundle fournit un chargeur de routes qui fait gagner du temps.

## Où déclarer les routes ?

| Section | Fichiers | Préfixe |
| --- | --- | --- |
| Front | `config/routes/frontend/*.yml`, importés par `config/routes/frontend.yml` | `/` |
| Administration | `config/routes/backend/*.yml`, importés par `config/routes/backend.yml` | `/admin` |
| API | `config/routes/api/*.yml`, importés par `config/routes/api.yml` | `/api` |

Pour ajouter des routes, créer un fichier YAML dans le dossier de la section et l'importer dans le fichier de la section.

## Une route personnalisée

```yaml title="config/routes/backend/article.yml"
app_backend_article_per_product_index:
    methods: [GET]
    path: /products/{productId}/articles/
    defaults:
        _controller: app.controller.article::indexAction
        _sylius:
            section: backend
            template: backend/crud/index.html.twig
            grid: app_backend_article_for_product
```

## Générer un CRUD

```yaml title="config/routes/backend/article.yml"
app_backend_article:
    resource: |
        alias: app.article
        section: backend
        redirect: update
        grid: app_backend_article
        except: ['show']
        vars:
            all:
                subheader: app.ui.manage_articles
                templates:
                    form: backend/article/_form.html.twig
            index:
                icon: newspaper
        templates: backend/crud
    type: sylius.resource
```

## Pour aller plus loin

Voir le [routage du SyliusResourceBundle](https://stack.sylius.com/).

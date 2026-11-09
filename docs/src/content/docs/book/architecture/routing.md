---
title: Routage
description: Déclarer les pages des ressources avec #[AsResource], et les quelques routes restées en YAML.
sidebar:
  order: 4
---

Les pages de l'administration et du front sont des **opérations** de SyliusResourceBundle, déclarées avec l'attribut `#[AsResource]` sur les entités. Chaque opération (`Index`, `Show`, `Create`, `Update`, `Delete`…) génère sa route et passe par le contrôleur unique de la nouvelle architecture : il n'y a plus de contrôleur par ressource.

## Où déclarer les routes ?

| Quoi | Où |
| --- | --- |
| Pages des entités de Jedisjeux (admin et front) | `#[AsResource]` sur l'entité, dans `src/Entity/` |
| Pages des modèles fournis par Sylius (`CustomerGroup`, `ProductAssociationType`) | un fichier PHP par ressource dans `config/sylius/resources/` |
| API JSON, appels AJAX de l'admin, connexion et mot de passe oublié | `config/routes/api/`, `config/routes/backend/ajax/` et `config/routes/frontend/` (YAML) |
| Pages statiques (accueil, tableau de bord, statistiques) | YAML ou attribut `#[Route]` sur un contrôleur de `src/Controller/` |

Les entités sont lues grâce à `sylius_resource.mapping.paths`, et les fichiers PHP grâce à `sylius_resource.mapping.imports` (voir `config/packages/sylius_resource.yaml`).

Les routes des opérations sont chargées **après** les routes YAML dans `config/routes.yaml`. Ainsi, une route précise comme `/jeux-de-societe/les-sorties` passe avant `/jeux-de-societe/{slug}`.

Les routes qui renvoient du JSON (API, AJAX) restent en YAML : elles sont sérialisées avec JMS Serializer, alors que la nouvelle architecture utilise le Serializer de Symfony.

## Un CRUD de l'administration

```php title="src/Entity/Dealer.php"
#[AsResource(
    alias: 'app.dealer',
    section: 'backend',
    templatesDir: '@SyliusAdminUi/crud',
    routePrefix: '/admin',
    vars: [
        'subheader' => 'app.ui.manage_dealers',
        'templates' => ['form' => 'backend/dealer/_form.html.twig'],
    ],
    operations: [
        new Index(grid: 'app_backend_dealer', vars: ['icon' => 'building']),
        new Create(redirectToRoute: 'app_backend_dealer_update'),
        new Update(redirectToRoute: 'app_backend_dealer_update'),
        new Show(),
        new Delete(),
    ],
)]
class Dealer implements ResourceInterface
```

- Les routes sont nommées `{application}_{section}_{ressource}_{opération}` : `app_backend_dealer_index`, `app_backend_dealer_update`…
- Le template est `{templatesDir}/{opération}.html.twig`, sauf si l'opération donne son `template`.
- Les `vars` de la ressource et de l'opération sont fusionnées. Les templates de l'Admin UI les lisent dans `configuration.vars`.
- Après une création ou une modification, la redirection va vers la fiche (`show`) si elle existe, sinon vers la liste. Indiquer `redirectToRoute` pour un autre choix.
- Ne déclarer `BulkDelete` que si la grille propose la suppression groupée.

Pour une page qui n'est pas un CRUD, donner un `shortName` (qui fixe le nom de la route), un `path` et un `template` :

```php
new Create(
    shortName: 'create_for_product',
    eventShortName: 'create',
    path: 'articles/products/{productId}/new',
    template: '@SyliusAdminUi/crud/create.html.twig',
    factoryMethod: 'createForProduct',
    factoryArguments: ['product' => "@=throw_not_found_on_null(sylius_repositories.get('sylius.repository.product').find(request.attributes.get('productId')))"],
    redirectToRoute: 'app_backend_article_update',
),
```

## Une ressource imbriquée

Les jeux d'une liste de festival sont gérés sous `/admin/festival-lists/{festivalListId}/items`. Par défaut, le dépôt cherche la ressource avec **tous** les paramètres de la route (`findOneBy(['festivalListId' => …, 'id' => …])`), ce qui échoue. Il faut donc dire comment la charger, et en profiter pour vérifier qu'elle appartient bien au parent de l'URL :

```php title="src/Entity/FestivalListItem.php"
private const ITEM_OF_LIST = "@={'id': request.attributes.get('id'), 'list': request.attributes.get('festivalListId')}";

new Update(
    path: '/{id}/edit',
    repositoryMethod: 'findOneBy',
    repositoryArguments: ['criteria' => self::ITEM_OF_LIST],
    redirectToRoute: 'app_backend_festival_list_item_index',
    redirectArguments: ['festivalListId' => 'resource.list.id'],
),
```

## Les expressions

Les arguments de dépôt (`repositoryArguments`), de fabrique (`factoryArguments`), de redirection (`redirectArguments`) et les `vars` dynamiques sont des expressions préfixées par `@=`. Elles disposent de :

- `request`, `token` et `user` (l'utilisateur connecté, ou `null`) ; le client connecté s'écrit `user ? user.getCustomer() : null` ;
- `sylius_repositories.get('app.repository.article')` et `throw_not_found_on_null(…)` dans les fabriques et les `vars` ;
- `is_granted('ROLE_…')`, ajouté par `App\ExpressionLanguage\SecurityExpressionFunctionProvider`.

Dans `redirectArguments`, `resource.product.slug` lit une propriété de la ressource.

:::caution
Une `var` écrite avec l'ancienne syntaxe `expr:` qui renvoie un objet fait planter la page : utiliser `@=`.
:::

## Les pages du front

Les pages du front gardent leurs URL et les noms de leurs routes : donner `path` et, si besoin, `routeName`.

```php title="src/Entity/Product.php"
new Index(
    shortName: 'index_by_taxon',
    path: '/jeux-de-societe/{slug}',
    routeRequirements: ['slug' => '[^?]+'],
    template: 'frontend/product/indexByTaxon.html.twig',
    grid: 'sylius_frontend_product_from_taxon',
    twigContextFactory: TaxonContextFactory::class,
),
```

- **Variables en plus dans le template** : une `twigContextFactory` les ajoute. Celles de `src/Twig/Context/` donnent la catégorie (`taxon`), le sujet du forum (`topic`), la personne (`person`) ou la liste de jeux (`product_list`) de l'URL, et refusent l'accès aux catégories privées du forum.
- **Pagination** : elle vient des `limits` de la grille (`->setLimits([16])`), plus de l'option `paginate` de la route.
- **Routes appelées depuis le JavaScript** : une opération ne peut pas porter l'option `expose`. Les ajouter à `routes_to_expose` dans `config/packages/fos_js_routing.yaml`.

## Les événements

Chaque opération déclenche les événements `{application}.{ressource}.pre_{événement}` et `post_{événement}`, comme `app.post.pre_create`, que les abonnés de `src/EventSubscriber/` écoutent.

:::caution
L'événement porte le `shortName` de l'opération : une opération `create_for_article` déclencherait `app.post.pre_create_for_article`, que personne n'écoute. Toujours ajouter `eventShortName: 'create'` (ou `'update'`) à une création ou une modification au `shortName` personnalisé.
:::

Les messages flash viennent de `translations/flashes.*.yml` : la clé `app.topic.bulk_delete` sert par exemple à la suppression groupée des sujets.

## Une ressource d'un modèle Sylius

Un modèle fourni par Sylius ne peut pas porter d'attribut. Sa ressource est déclarée dans un fichier PHP qui renvoie une `ResourceMetadata` :

```php title="config/sylius/resources/customer_group.php"
return (new ResourceMetadata())
    ->withClass(CustomerGroup::class)
    ->withAlias('sylius.customer_group')
    ->withSection('backend')
    ->withTemplatesDir('@SyliusAdminUi/crud')
    ->withRoutePrefix('/admin')
    ->withOperations(new Operations([
        new Index(grid: 'sylius_backend_customer_group', vars: ['icon' => 'archive']),
        new Create(redirectToRoute: 'sylius_backend_customer_group_index'),
        new Update(redirectToRoute: 'sylius_backend_customer_group_index'),
        new Delete(),
    ]))
;
```

## Pour aller plus loin

Voir la documentation des [ressources de Sylius Stack](https://stack.sylius.com/).

---
title: Grilles
description: Afficher la liste des ressources dans l'administration.
sidebar:
  order: 3
---

Une grille définit la façon dont les objets d'une entité sont affichés sur leur page de liste : champs, filtres, tri et actions.

## Créer une grille

Les grilles de l'administration sont des classes PHP dans `src/Grid/Backend/`, déclarées avec l'attribut `#[AsGrid]`. Par exemple, la grille des boutiques :

```php title="src/Grid/Backend/DealerGrid.php"
#[AsGrid(
    name: 'app_backend_dealer',
    resourceClass: Dealer::class,
)]
final class DealerGrid
{
    public function __invoke(GridBuilderInterface $gridBuilder): void
    {
        $gridBuilder
            ->orderBy('name', 'asc')
            ->setLimits([10, 25, 50])
            ->withFields(
                TwigField::create('image', 'backend/dealer/grid/field/image.html.twig')
                    ->setPath('.')
                    ->setLabel('sylius.ui.image'),
                StringField::create('name')
                    ->setLabel('sylius.ui.name')
                    ->setSortable(true, 'name'),
            )
            ->withFilters(
                Filter::create('search', 'string')
                    ->setLabel('sylius.ui.search')
                    ->setOptions(['fields' => ['name']]),
            )
            ->withMainActions(
                CreateAction::create(),
            )
            ->withItemActions(
                UpdateAction::create(),
                DeleteAction::create(),
            )
        ;
    }
}
```

Une grille peut aussi en étendre une autre avec `->extends('app_backend_article')`, comme `ArticleForProductGrid` qui liste les articles d'un jeu.

## Générer le routage

SyliusResourceBundle génère une interface CRUD complète qui utilise la grille. Il suffit de la déclarer dans le routage (voir [Routage](../routing/)) :

```yaml title="config/routes/backend/dealer.yml"
app_backend_dealer:
    resource: |
        alias: app.dealer
        section: backend
        templates: backend/crud
        redirect: update
        grid: app_backend_dealer
        vars:
            all:
                subheader: app.ui.manage_dealers
                templates:
                    form: backend/dealer/_form.html.twig
            index:
                icon: building
    type: sylius.resource
```

## Pour aller plus loin

La référence complète des champs, filtres et actions se trouve dans la [documentation du SyliusGridBundle](https://stack.sylius.com/).

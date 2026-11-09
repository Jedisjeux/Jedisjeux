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

## Afficher la grille

La grille est affichée par l'opération `Index` de la ressource, déclarée avec `#[AsResource]` sur l'entité (voir [Routage](../routing/)) :

```php title="src/Entity/Dealer.php"
new Index(grid: 'app_backend_dealer', vars: ['icon' => 'building']),
```

Le nombre de lignes par page vient des `limits` de la grille : la première valeur est celle par défaut.

## Pour aller plus loin

La référence complète des champs, filtres et actions se trouve dans la [documentation du SyliusGridBundle](https://stack.sylius.com/).

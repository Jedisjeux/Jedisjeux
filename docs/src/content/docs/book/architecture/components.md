---
title: Composants Twig
description: Afficher un fragment de page avec ses propres données, sans sous-requête.
sidebar:
  order: 5
---

Un fragment qui a besoin de ses propres données (les derniers articles, les avis d'un jeu, les catégories d'un menu…) est un [composant Twig](https://symfony.com/bundles/ux-twig-component/current/index.html), et non une route partielle appelée avec `render(url(…))`. Le composant est rendu dans la même requête, sans passer à nouveau par le noyau ni par le pare-feu.

## Afficher un composant

```twig title="templates/frontend/product/show/tabs/_reviews.html.twig"
{{ component('ProductReview:LatestForProduct', {productId: product.id}) }}
```

Les composants se trouvent dans `src/Twig/Components/`, rangés par domaine : `Article:Latest`, `Product:Box`, `Taxon:ArticleMenu`, `Dashboard:LatestCustomers`…

## Créer un composant

Un composant est une classe avec l'attribut `#[AsTwigComponent]`. Ses propriétés publiques sont ses paramètres, et les méthodes marquées `#[ExposeInTemplate]` donnent les variables du template :

```php title="src/Twig/Components/ProductReview/LatestForProduct.php"
#[AsTwigComponent(name: 'ProductReview:LatestForProduct', template: 'frontend/product_review/_list.html.twig')]
final class LatestForProduct
{
    public int $productId;

    public int $count = 3;

    public function __construct(
        #[Autowire(service: 'sylius.repository.product_review')]
        private readonly ProductReviewRepository $productReviewRepository,
    ) {
    }

    #[ExposeInTemplate('product_reviews')]
    public function getProductReviews(): array
    {
        return $this->productReviewRepository->findLatestByProductId($this->productId, $this->count);
    }
}
```

:::caution
Sans nom, `#[ExposeInTemplate]` expose la méthode `getDealerPrices()` sous le nom `dealerPrices`. Donner le nom attendu par le template quand il diffère, comme `#[ExposeInTemplate('dealer_prices')]`.
:::

Un même jeu de données affiché de deux façons donne deux composants : la classe avec le template par défaut, et une sous-classe avec l'autre template (`Article:Latest` et `Article:LatestList`, ou les menus de `Taxon:Children`).

Le template d'un composant voit la requête principale : il lit ses paramètres dans les propriétés du composant, pas dans `app.request`.

## Composants dynamiques

Les statistiques du tableau de bord et du compte (`Dashboard:Statistics`, `Account:Statistics`) sont des [composants dynamiques](https://symfony.com/bundles/ux-live-component/current/index.html), qui se mettent à jour sans recharger la page.

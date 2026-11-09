---
title: Vue d'ensemble
description: Comment l'application Jedisjeux est structurée.
sidebar:
  order: 1
---

Avant d'aborder chaque concept de Jedisjeux, voici comment l'application est structurée.

## Symfony

**Jedisjeux** est une application [Symfony](https://symfony.com/what-is-symfony), le framework PHP de référence pour créer des applications web. Le code applicatif se trouve dans `src/` (espace de noms `App\`), la configuration dans `config/` et les gabarits dans `templates/`.

## Doctrine

[Doctrine](https://www.doctrine-project.org/) est un ensemble de bibliothèques PHP dédiées à la persistance des données. Les plus importantes sont l'ORM et la couche d'abstraction de base de données (DBAL). Les entités se trouvent dans `src/Entity/` et les migrations dans `src/Migrations/`.

## Twig

[Twig](https://twig.symfony.com/) est le moteur de gabarits utilisé par Symfony : rapide, sûr et flexible.

## Bibliothèques tierces

Jedisjeux s'appuie sur de nombreuses bibliothèques :

- [Sylius](https://docs.sylius.com/) pour les ressources (routage, contrôleurs), les fixtures, les grilles, les jeux (produits), les avis et la taxonomie ;
- [Sylius Admin UI](https://github.com/Sylius/Stack) (Bootstrap Admin UI et Twig Hooks) pour l'interface d'administration ;
- [LiipImagineBundle](https://github.com/liip/LiipImagineBundle) pour le traitement des images, les miniatures et le recadrage ;
- [VichUploaderBundle](https://github.com/dustin10/VichUploaderBundle) pour l'envoi de fichiers ;
- [Pagerfanta](https://github.com/BabDev/Pagerfanta) pour la pagination ;
- [Winzou State Machine](https://github.com/winzou/StateMachineBundle) pour les machines à états (cycle de publication des jeux et articles) ;
- [FOSCKEditorBundle](https://github.com/FriendsOfSymfony/FOSCKEditorBundle) pour l'éditeur de texte riche ;
- [Symfony UX](https://ux.symfony.com/) et AssetMapper pour le JavaScript du front.

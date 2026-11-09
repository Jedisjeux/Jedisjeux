---
title: Fixtures
description: Charger un jeu de données de démonstration.
sidebar:
  order: 2
---

Les fixtures servent surtout aux tests, mais aussi à mettre le site dans un état connu, avec des données définies : elles garantissent un environnement fixe dans lequel l'application fonctionne.

:::note
Le fonctionnement des fixtures dans Jedisjeux est celui du [SyliusFixturesBundle](https://github.com/Sylius/SyliusFixturesBundle).
:::

## Quelles fixtures sont disponibles ?

```sh
castor app:symfony sylius:fixtures:list
```

## Comment charger les fixtures ?

La façon recommandée de charger le jeu de fixtures de Jedisjeux :

```sh
castor app:db:fixtures
```

Les fixtures créent notamment des comptes de test, dont `admin@example.com` (mot de passe `admin`) pour l'administration.

## Où sont-elles définies ?

- Les classes de fixtures sont dans `src/Fixture/`, et les fabriques de données d'exemple dans `src/Fixture/Factory/`.
- Les données à charger sont décrites dans `config/sylius/fixtures.yaml`, qui importe les fichiers de `config/sylius/fixtures/`.

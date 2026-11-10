---
title: Déploiement
description: Déployer le site en production.
---

## Clé SSH autorisée

Ajouter sa clé SSH publique aux clés autorisées du serveur :

```sh
cat ~/.ssh/id_rsa.pub | ssh jedisjeux@51.75.125.242 "cat - >> ~/.ssh/authorized_keys"
```

puis saisir le mot de passe de l'utilisateur `jedisjeux` sur le serveur.

## Déployer

Capistrano tourne dans un conteneur Docker (Ruby n'est pas nécessaire sur la machine). L'agent SSH est transmis au conteneur : la clé doit être chargée (`ssh-add`). C'est la branche git courante qui est déployée.

### Depuis sa machine

```sh
castor app:deploy
```

### Depuis GitHub Actions

Le workflow « Deploy » se lance tout seul quand la CI passe sur `main` : chaque merge part en production, sans validation manuelle. Il déploie le commit testé par la CI.

Pour déployer une autre branche, lancer le workflow depuis l'onglet Actions et la choisir. Il a besoin de ces secrets sur l'environnement `production` :

- `SSH_PRIVATE_KEY` : une clé privée autorisée pour `jedisjeux@51.75.125.242` ;
- `SSH_KNOWN_HOSTS` : la sortie de `ssh-keyscan 51.75.125.242`.

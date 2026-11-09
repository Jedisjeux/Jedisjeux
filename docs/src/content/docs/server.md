---
title: Configuration du serveur
description: Réglages PHP et Apache du serveur de production.
---

## PHP

Jedisjeux nécessite PHP 8.3, avec les extensions `intl`, `pdo_mysql`, `xml` et `gd`.

Pour trouver le chemin du `php.ini` :

```sh
php --ini
```

Modifier ces paramètres dans le `php.ini` :

```ini
date.timezone = Europe/Paris
memory_limit = 2048M
upload_max_filesize = 10M
post_max_size = 20M
```

## Apache

### Hôte virtuel

Le site est déployé dans `/home/jedisjeux/` (voir [Déploiement](../deployment/)) et la racine web est le dossier `public/` de la release courante :

```apache
<VirtualHost *:80>
    DocumentRoot /home/jedisjeux/current/public/
    <Directory "/home/jedisjeux/current/public">
        DirectoryIndex index.php
        Options FollowSymLinks
        AllowOverride All
        FallbackResource /index.php
    </Directory>
    ServerName XX.XXX.XX.XXX
    ErrorLog  logs/jedisjeux-error_log
    CustomLog logs/jedisjeux-access_log combined
</VirtualHost>
```

Pour plus de détails, voir la [configuration d'un serveur web](https://symfony.com/doc/current/setup/web_server_configuration.html) dans la documentation de Symfony.

Redémarrer Apache pour prendre en compte la configuration.

## Clé SSH

```sh
ssh-keygen
```

Laisser la phrase de passe vide, et la confirmer.

## Droits de l'utilisateur

Ajouter l'utilisateur `jedisjeux` au groupe d'Apache :

```sh
usermod -a -G apache jedisjeux
```

## Sécuriser l'accès

```sh
chmod g-w /home/jedisjeux
chmod 700 /home/jedisjeux/.ssh
chmod 600 /home/jedisjeux/.ssh/authorized_keys
```

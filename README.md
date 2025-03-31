# Projet gabarit ZEPHYRUS

Projet destiné à être utilisé comme base de gabarit pour les projets ZEPHYRUS.

## Environnement de développement (Docker)

### Prérequis
Assurez-vous d'avoir le [Moteur Docker](https://www.docker.com/products/docker-desktop/) installé et à jour.

### Premier démarrage
Copiez le fichier `.env.example` vers un fichier nommé `.env`. Lancez finalement la construction de l'environnement de développement.

```shell
docker compose up
docker exec -it zephyrus_webserver composer install
```

### Mise à jour des dépendances (Composer)
```shell
docker exec -it zephyrus_webserver composer update
```

### Redémarrer la base de données (au besoin)
```shell
docker compose down
docker compose up
```

### Activer / Désactiver Xdebug
Par défaut, xdebug est installé, mais pas actif pour augmenter les performances en développement. Par contre, il est
possible de l'activer et de le désactiver avec une commande. Doit être exécuté sur l'ordinateur hôte et non depuis le
conteneur Docker (puisque le script interagit avec l'exécutable de Docker sur l'hôte).

#### Activer
```shell
composer xdebug-enable
```

#### Désactiver
```shell
composer xdebug-disable
```

### Génération de la cache Latte
```shell
docker exec -it zephyrus_webserver composer latte-cache
```

### Supprimer les images Docker
```shell
docker rmi $(docker images -q)
```


## MailCatcher

Par défaut, l'image Docker fourni avec Zephyrus inclus [MailCatcher](https://www.google.com/search?client=safari&rls=en&q=mailcatcher&ie=UTF-8&oe=UTF-8). Ceci
permet de tester des courriels simplement. 

Pour accéder à MailCatcher : http://localhost:1080/  

```yml
mailer:
  transport: "smtp"
  from_address: "info@ophelios.com"
  from_name: "Zephyrus"
  smtp:
    enabled: true
    host: "localhost"
    port: 1025
    encryption: "none"
    username: ""
    password: ""
```
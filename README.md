# Covoiturage du Campus

Le résultat de notre projet est disponible sur une page web en suivant ce [lien](https://rpierre002.zzz.bordeaux-inp.fr/SGBD_covoiturage).

## Auteurs

* Romain PIERRE
* Mathys MONELLO
* Yannis YOASSI PATIPE
* Abdurahman EL CALIFA KANIT BENSAIDI

## Table des matières
1. [Mise en place de la base de données PostgreSQL](#mise-en-place-de-la-base-de-données-postgresql)
2. [Mise en place de l'application web Apache/PHP/PostgreSQL en local](#mise-en-place-de-lapplication-web-apachephppostgresql-en-local)

## Mise en place de la base de données PostgreSQL

Cette partie explique comment mettre en place le serveur **PostgreSQL** et notre base de données. Ce mode d'emploi a été conçue et testé pour *Ubuntu*.

### Installation de PostgreSQL

```bash
sudo apt-get install postgresql
```

### Connexion au serveur PostgreSQL en tant que postgres

```bash
sudo -u postgres psql
```

### Création de la base de données et de l'utilisateur

Une fois connecté au serveur PostgreSQL:

(Penser à remplacer login par le nom d'utilisateur de votre machine et password par votre mot de passe)

```sql
CREATE DATABASE covoiturage_du_campus;
CREATE USER login WITH PASSWORD 'password';
GRANT ALL PRIVILEGES ON DATABASE covoiturage_du_campus TO login;
GRANT CREATE ON SCHEMA public TO login;
```

### Connexion à la base de données

Sortir de psql avec :

```psql
\q
```

Se reconnecter au serveur PostgreSQL en tant qu'utilisateur avec :

```bash
psql covoiturage_du_campus
```

> Attention, cela ne fonctionne que si le login que vous avez défini est le même que celui de l'utilisateur du terminal.

Si vous avez enregistré un autre login vous pouvez essayer la commande suivante en remplaçant login par celui que vous avez défini. Mais PostgreSQL rencontrera surement un problème de droit d'accès. Ce n'est donc pas recommandé et il est préférable de recréer un utilisateur dans PostgreSQL avec le même login que l'utilisateur machine.

```bash
sudo -u login psql covoiturage_du_campus
```

### Initiation de la base de données

Pour exécuter un script sql :

```psql
\i script.sql
```

Nos scripts sont les suivants :

* **create.sql** crée la base (tables + contraintes + triggers + ...).
* **insert.sql** peuple la base avec les éléments nécessaires pour la tester.
* **select.sql** contient les requêtes de consultations et de statistiques utilisées dans le projet.
* **update.sql** contient les requêtes de mise à jour.
* **drop.sql** permet de supprimer la base.

>Exécuter create.sql (et insert.sql optionnellement pour déjà avoir des données).

## Mise en place de l'application web Apache/PHP/PostgreSQL en local

Cette partie explique comment mettre en place le serveur web **Apache** et **PHP** afin de le relier à notre base de données. Ce mode d'emploi a été conçue et testé pour *Ubuntu*. L'installation du serveur local est utile à des fins de développement. La version en production de l'application web est disponible en suivant ce [lien](https://rpierre002.zzz.bordeaux-inp.fr/SGBD_covoiturage).

### Modifier connect_pg.php

Dans le répertoire sql, copier le fichier **connect_pg_example.php** en **connect_pg.php** et remplacer "login" et "password" par ceux définis dans la partie précedente.

```bash
cp src/connect_pg_example.php src/connect_pg.php
nano src/connect_pg.php
```

### Installer Apache et PHP

```bash
sudo apt install apache2 apache2-utils
sudo apt install php php-pgsql libapache2-mod-php
```

### Configurer le serveur web

#### Modifier la résolution de nom

Ajouter la ligne suivante dans le fichier **/etc/hosts** :

(Se mettre en sudo pour enregistrer la modification)

```config
127.0.0.1   covoiturage_du_campus
```

#### Créer un hôte virtuel

Dans /etc/apache2/sites-available/, créer le fichier **covoiturage_du_campus.conf** et y écrire dedans :

```apache
<VirtualHost *:80>
    ServerName covoiturage_du_campus
    DocumentRoot /var/www/covoiturage_du_campus
</VirtualHost>
```

Puis dans le terminal créer le dossier du site :

```bash
sudo mkdir /var/www/covoiturage_du_campus
```

#### Lancer le site web et le service

```bash
sudo a2ensite covoiturage_du_campus
sudo systemctl reload apache2
```

### Pousser les fichiers vers le serveur

Pour pousser les fichiers du répertoire src vers le serveur local (i.e. /var/www/covoiturage_du_campus), exécuter le script **push_server.sh**. Cela permet également de rapidement visualiser les modifications lors du développement.

```bash
chmod 755 push_server.sh
./push_server.sh
```

### Accéder au site web

Le serveur web local est normalement maintenant accessible en suivant ce [lien](http://covoiturage_du_campus).

# Gestion Magasin - Configuration de la Base de Données

## Description
Ce projet met en place une base de données MySQL pour la gestion d'un magasin. Il comprend la création de la base de données `gestion_magasin`, des tables nécessaires, et leur configuration via un script PHP automatisé.

---

## Pré-requis
- Docker et Docker Compose installés
- Un environnement LAMP fonctionnel basé sur le fichier `docker-compose.yml` fourni
- Accès au conteneur MySQL avec les bonnes permissions

---

## Installation

### 1. Lancer les conteneurs Docker
Assurez-vous que vos conteneurs Docker sont démarrés :
```bash
docker-compose up -d
```

---

### 2. Vérifier les privilèges MySQL
Par défaut, l'utilisateur MySQL (`lamp_user`) peut ne pas disposer des permissions nécessaires pour créer une base de données. Voici comment vérifier et configurer les privilèges.

#### Étape 1 : Accéder au conteneur MySQL
Exécutez cette commande pour accéder à MySQL en tant que root :
```bash
docker exec -it lamp_db mysql -u root -p
```
Entrez le mot de passe root défini dans le fichier `docker-compose.yml` (par défaut : `root_password`).

#### Étape 2 : Vérifier les privilèges de l'utilisateur
Dans le terminal MySQL, exécutez la commande suivante pour vérifier les permissions actuelles de l'utilisateur `lamp_user` :
```sql
SHOW GRANTS FOR 'lamp_user'@'%';
```

#### Étape 3 : Attribuer les permissions nécessaires
Si l'utilisateur n'a pas les permissions pour créer des bases, utilisez la commande suivante pour lui attribuer tous les droits :
```sql
GRANT ALL PRIVILEGES ON *.* TO 'lamp_user'@'%' WITH GRANT OPTION;
FLUSH PRIVILEGES;
```

Ou pour donner des droits spécifiques à la base `gestion_magasin` :
```sql
CREATE DATABASE IF NOT EXISTS gestion_magasin;
GRANT ALL PRIVILEGES ON gestion_magasin.* TO 'lamp_user'@'%';
FLUSH PRIVILEGES;
```

---

### 3. Exécuter le script PHP
Le script `setup.php` configure automatiquement la base de données et les tables nécessaires. Placez le fichier `setup.php` dans votre répertoire principal et exécutez-le via un navigateur web ou CLI.

#### Exemple d'exécution via un navigateur
Accédez à l'URL suivante :
```
http://localhost:8080/setup.php
```

---

## Structure de la Base de Données

### Tables

1. **Table `produits`**
    - `id` (INT, clé primaire, auto-incrément)
    - `nom` (VARCHAR)
    - `prix` (INT, prix en centimes)
    - `quantite` (INT)
    - `categorie` (VARCHAR)

2. **Table `clients`**
    - `id` (INT, clé primaire, auto-incrément)
    - `nom` (VARCHAR)
    - `email` (VARCHAR)

3. **Table `commandes`**
    - `id` (INT, clé primaire, auto-incrément)
    - `client_id` (INT, clé étrangère vers `clients`)
    - `date_commande` (DATETIME)

4. **Table `commande_produits`**
    - `commande_id` (INT, clé étrangère vers `commandes`)
    - `produit_id` (INT, clé étrangère vers `produits`)
    - `quantite` (INT)

---

## Vérification

1. Accédez à **phpMyAdmin** :  
   `http://localhost:8081`
    - Utilisateur : `lamp_user`
    - Mot de passe : `lamp_password`

2. Vérifiez que :
    - La base de données `gestion_magasin` existe.
    - Les tables `produits`, `clients`, `commandes`, et `commande_produits` sont bien créées.

---

## Dépannage

- **Erreur : "Access denied for user 'lamp_user'@'%'"**  
  Vérifiez et ajustez les permissions MySQL comme indiqué dans la section [Étape 2 : Attribuer les permissions nécessaires](#étape-2--attribuer-les-permissions-nécessaires).

- **Erreur : "Could not find driver"**  
  Assurez-vous que l'extension `pdo_mysql` est bien activée dans votre conteneur PHP.

---

## Fonctionnalités

- Gestion des **produits** :
   - Ajouter, modifier, supprimer, et lister les produits.
   - Validation des quantités disponibles avant toute commande.
- Gestion des **clients** :
   - Ajouter, modifier, supprimer, et lister les clients.
   - Vérification des commandes associées avant suppression.
- Gestion des **commandes** :
   - Créer une commande avec des produits multiples.
   - Calcul automatique du total de la commande.
   - Vérification des stocks avant confirmation de la commande.
- Interface web moderne :
   - Interface responsive utilisant **Bootstrap**.
   - Navigation intuitive pour toutes les opérations CRUD.
- Vérification et gestion des relations entre les entités pour préserver l'intégrité des données.

---

## Technologies utilisées

- **PHP** : Backend de l'application avec utilisation de la programmation orientée objet (POO).
- **MySQL** : Base de données relationnelle.
- **PDO** : Gestion sécurisée des interactions avec la base de données.
- **Bootstrap** : Framework CSS pour une interface moderne et responsive.
- **Docker** : Conteneurisation pour un environnement de développement uniforme.

---
```
/gestion_magasin
│
├── /public              # Interface web (CRUD via Bootstrap)
│   ├── add_product.php      # Ajouter un produit
│   ├── edit_product.php     # Modifier un produit
│   ├── delete_product.php   # Supprimer un produit
│   ├── list_products.php    # Lister les produits
│   ├── add_client.php       # Ajouter un client
│   ├── edit_client.php      # Modifier un client
│   ├── delete_client.php    # Supprimer un client
│   ├── list_clients.php     # Lister les clients
│   ├── place_order.php      # Passer une commande
│   ├── list_orders.php      # Lister les commandes
│   ├── delete_order.php     # Supprimer une commande
│   ├── styles.css           # Fichier de styles CSS
│   └── index.php            # Page d'accueil de l'interface web
│
├── /src                 # Classes PHP
│   ├── Database.php         # Gestion de la connexion à MySQL
│   ├── Produit.php          # Classe produit et ses sous-classes
│   ├── Client.php           # Classe client
│   ├── Commande.php         # Classe commande
│
├── autoload.php         # Chargement automatique des classes (PSR-4)
├── setup.php            # Script de configuration de la base de données
└── README.md            # Documentation du projet
```
---

## Utilisation de l'application

1. **Page d'accueil**
   - Accessible à l'URL : `http://localhost:8080/public/index.php`.
   - Propose un menu de navigation vers les différentes sections : Produits, Clients, Commandes.

2. **Gestion des produits**
   - Ajouter un produit : `http://localhost:8080/public/add_product.php`.
   - Modifier un produit existant : Cliquez sur "Modifier" dans la liste des produits.
   - Supprimer un produit : Cliquez sur "Supprimer" (uniquement si le produit n'est pas utilisé dans une commande).

3. **Gestion des clients**
   - Ajouter un client : `http://localhost:8080/public/add_client.php`.
   - Modifier un client existant : Cliquez sur "Modifier" dans la liste des clients.
   - Supprimer un client : Cliquez sur "Supprimer" (uniquement si le client n'a pas de commande associée).

4. **Gestion des commandes**
   - Passer une commande : `http://localhost:8080/public/place_order.php`.
   - Lister les commandes : `http://localhost:8080/public/list_orders.php`.
   - Supprimer une commande : Cliquez sur "Supprimer" dans la liste des commandes.

---

## Licence
Projet libre d'utilisation pour des fins éducatives. 🎉

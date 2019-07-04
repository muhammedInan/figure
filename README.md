Badge de Codacy
https://app.codacy.com/project/muhammedInan/p6/dashboard
Snowtricks Jimmy Sweat est un entrepreneur ambitieux. Son objectif est de faire un outil pour apprendre les figures du snowboard, de permettre la vulgarisation du snowboard auprès du plus grand nombre.

Configuration de Symfony 4.1 avec PHP 7.2

Obtenez le clone du projet git https://github.com/muhammedInan/p6.git

Installation cd snowtricks npm install composer installer Lancer le projet Créer la base de données: doctrine php bin / console: schéma: update --force Remplir la base de données: doctrine php bin / console: fixtures: charger Exécuter le projet: yarn run encore dev --watch https://app.codacy.com/project/muhammedInan/p6/dashboard DATABASE_URL = mysql: // root: @ 127.0.0.1: 3306 / figure3
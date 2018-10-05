
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/6777a3bd19964ddfa27458772790518c)](https://app.codacy.com/app/muhammedInan/p6?utm_source=github.com&utm_medium=referral&utm_content=muhammedInan/p6&utm_campaign=Badge_Grade_Dashboard)

Snowtricks
Jimmy Sweat est un entrepreneur ambitieux. Son objectif est d'en faire un outil pour apprendre les figures (tricks) du snowboard, de permettre la vulgarisation du snowboard auprès du plus grand nombre.

Configuration
Symfony 4.1 with PHP 7.2

Get the project
git clone https://github.com/muhammedInan/p6.git

Installation
cd snowtricks
npm install
composer install
Launching the project
Create database : php bin/console doctrine:schema:update --force
Populate database : php bin/console doctrine:fixtures:load
Run project : yarn run encore dev --watch
That's it !
https://app.codacy.com/project/muhammedInan/p6/dashboard

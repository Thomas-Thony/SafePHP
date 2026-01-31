# [SafePHP](#safephp) ![PHP](https://img.shields.io/badge/php-%23777BB4.svg?style=for-the-badge&logo=php&logoColor=white)
<a href="#sommaire"><img src="./Components/Img/fra.svg" style="width:40px; height:auto;"></a> <a href="#summary"><img src="./Components/Img/eng.svg" style="width:40px; height:auto;"></a>
## Sommaire

- [Sommaire](#sommaire)
- [Contribution au projet](#contribution-au-projet)
- [Introduction](#introduction)
  - [Pourquoi faire SafePHP](#pourquoi-faire-safephp)
  - [Avant toute chose](#avant-toute-chose)
  - [Contenu de la librarie](#contenu-de-la-librairie)
  - [Outils requis](#outils-requis)
  - [Installation](#installation)
  - [Configuration générale](#configuration-générale)
  - [Utilisation générale](#utilisation-générale)

## Contribution au projet

Le projet SafePHP est en open source et libre de toute utilisation, que ce soit personnelle comme pédagogique. **Vous pouvez bien évidement contribuer activement au projet en faisant des fixs, des tests, des features ou en complétant les différentes documentations par exemple**.
Vous pouvez me conctacter à l'adresse mail thomas.thony.69@gmail.com .

## Introduction
### Pourquoi faire SafePHP
SafePHP est une librairie PHP qui permet d'implémenter des moyens de cybersécurité facilement !<br>
Cela rend le développement de moyens de cybersécurité rapide, efficace et facile à maintenir.

### Avant toute chose

Ce projet a été fait par un étudiant en informatique, avec le moins d'utilisation d'IA possible (Pas même pour la documentation), et vérification auprès de communautées certifiées quand c'est le cas.
Je vous remercie d'être indulgent sur la qualité de code mais en étant pédagogique sur l'apport d'améliorations (dans le code ou simplement la manière de faire, tous les avis sont bon à prendre), ce projet a pour but de faciliter la vie des développeurs pour la cybersécurité. J'espère évidement que SafePHP sera utilisé par le plus grand nombre de développeurs et/ou qu'il sera maintenu par les plus enthousiastes de la libraire.

### Contenu de la librairie

Cette librairie contient plusieurs fichiers de configuration qui sont à disposition dans le dossier **config** comme :

- Un fichier de configuration Apache **.htaccess**
- Un fichier **.env.example** où mettre vos variables d'environnement
- Un fichier **php.ini** avec des modules activés/désactivés par défault

### Outils requis

Composer : Version 2.9.3 \
PHP : Version 8.0.0 minimum \
Serveur LAMP ou XAMP

### Installation

Vous pouvez l'installer avec composer :

```php
composer require thomas-thony/safephp
```
N'oubliez pas d'installer les dépendences associées pour assurer le bon fonctionnement du la librairie: 


Windows :
```php
composer update; if ($?) { composer install }
```
Linux :
```php
composer update && composer install
```

### Configuration générale

Avant de pouvoir utiliser pleinement SafePHP, pensez à importer les fichiers de configuration présents dans **./config** à la racine de votre projet et de mettre vos variables d'environnement à jour dans le fichier .env .

#### Utiliser les variables d'environnement
La classe [Secret](https://safephp.alwaysdata.net/docs/classes/SafePHP-Secret.html) permet de gérer l'importation de votre fichier .env .


### Utilisation générale
La documentation détaillée des classes est disponible à cette adresse : https://safephp.alwaysdata.net/docs/


# [SafePHP](#safephp) (Alpha Version) ![PHP](https://img.shields.io/badge/php-%23777BB4.svg?style=for-the-badge&logo=php&logoColor=white) 
<a href="#sommaire"><img src="./Components/Img/fra.svg" style="width:40px; height:auto;"></a> <a href="#summary"><img src="./Components/Img/eng.svg" style="width:40px; height:auto;"></a>
## Summary
- [Summary](#summary)
- [Project contribution](#project-contribution)
- [Introduction-Bis](#introduction-bis)
  - [Why make SafePHP](#why-make-safephp)
  - [Before anything](#before-anything)
  - [Library's content](#librarys-content)
  - [Requirements](#requirements)
  - [Installation](#installation)
  - [Global configuration](#global-configuration)
    - [Use envrionnement variables](#use-environnement-variables)
  - [General usage](#general-usage)
## Project contribution

The SafePHP project is in open-source and free of usage, for personal or educative purpose. **You can of course contribute by doing fixes, tests, creating new features or documentation**.
To join me, you can e-mail me at thomas.thony.69@gmail.com.

## Introduction-Bis
### Why make SafePHP
SafePHP is a PHP library that allows to implement cybersecurity means easily! <br>
This makes the development of cybersecurity means fast, effective and easy to maintain.

### Before anything

This project was made by a student in computer science, with the least AI as possible (not even for documentation), and verification with certified communities when it was used. Thank you for being lenient on the quality of the code but by being educational about making improvements (in the code or simply the way to do it, all opinions are good), this project aims to facilitate developers for cyber-security. Of course, I hope SafePHP will be used by the most of people and/or will be maintenend by the most enthousiats of the library.

### Library's content

Even more, a couple of configuration's files are aviables in the folder config like :

A file for Apache configuration nammed .htaccess
A .env.example file where use your's environnement variables
A php.ini file with modules enables & disabled by default

### Requirements

Composer : Version 2.9.3 \
PHP : Version 8.0.0 at least \
LAMP or XAMP server

### Installation

You can install the library with composer like this :

```php
composer require thomas-thony/safephp
```
Don't forget to install all dependences linked to the library to ensure the good utility : 

Windows :
```php
composer update; if ($?) { composer install }
```
Linux :
```php
composer update && composer install
```

### Global configuration

Before fully use SafePHP, don't forget to import your files of configuration in **./config** in the root folder of your project and update your environnement variables in the .env file.

#### Use environnement variables
The class [Secret](https://safephp.alwaysdata.net/docs/classes/SafePHP-Secret.html) can manage importation of .env file.


### Global usage
The detailled documentation of classes is aviable here :  https://safephp.alwaysdata.net/docs/
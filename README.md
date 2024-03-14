Spécifications:

Symfony 6.4
PHP 7.2
CSS / HTML / Javascript
twig
MySQL
PHPMyAdmin


Installation et configuration : 

1. Récuperer le dossier de l'application

2. Installez les dépendances avec composer

```bash
composer install
```
3. Changer les paramètres de la connexion a la base de données dans le fichier .env
   
4. Créer la base de données et migrer les entitées

```bash
symfony console doctrine:database:create
symfony console make:migration
symfony console doctrine:migrations:migrate

```

5. lancer l'application en local.

```bash
symfony serve:start
```


Choix des technologies :

Symfony : J'ai choisi symfony pour faire ce test technique, 
car malgré sa réputation d'être un framework lourd composé d'une multitude de bundles,
la structure du framework est tres modulaire et on peut faire une application en utilisant uniquement quelques bundles utiles,
et ainsi allégé le poids de l'app.
Il est ainsi très pratique d'utiliser symfony pour une application simple et rajoutés au fur et à mesure les bundles nécéssaires.
En outre, Symfony offre des configurations par défaut cohérentes et faciles à comprendre. Cela permet de gagner du temps dans le développement et de réduire la complexité.
C'est un framework qui est réguliérement mis a jour et sa documentation fait partie des plus exhaustives disponible actuellement pour un framework, ce qui n'est pas négligeable.

Twig :

J'ai utilisé twig comme moteur de templating afin de render mes views.
C'est le moteur par défaut de symfony il est donc intégré naturellement et son utilisation et d'autant plus fluide avec.
Sa synthaxe simple et facile comprendre ne le rends pas moins puissant grace à différentes fonctionnalités avancées (pipe, filter, fonction).
Twig intègre un mécanisme sous-jacent permettant de protéger l'app d'éventuelle attaque XSS.



Difficultés rencontrés : 

La logique conditionnelle pour l'assignation de quais dynamique à un train nouvellement créer est le point qui ma poser le plus de problèmes,
je pense que mon algorithme n'est pas optimale et je ne suis pas totalement satisfait.
De plus l'endroit ou mettre la logique m'a aussi fais creuser la tête afin de trouver l'emplacement approprié.
J'ai décidé d'utiliser un formEventSubscriber de type POST_SUBMIT afin de séparer la logique de la requete a l'api, ce qui m'a paru être la meilleur solution pour garder le code clair et propre.










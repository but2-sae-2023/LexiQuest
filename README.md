# LexiQuest

## :pencil2: Auteurs :
Rabah Mehdi CHERAK
Loïc MAURITIUS
Enzo LETOCART 
Thivakar JEYASEELAN 
Laxhan PUSHPAKUMAR 
Amaury BOOMS

## Explication :

Le projet Semantic Analogy Explorer s'inscrit dans une démarche pédagogique SAE du BUT Informatique, il met en valeur nos compétences de développement applicatif et web. L'objectif étant de créer un jeu en ligne sur un site web se basant sur de la sémantique. Le principe du jeu est de relier un mot de départ et de fin à d'autres mots proposé par les joueurs et le but étant de réaliser le plus grand score se basant sur la ressemblance sémantique et/ou orthographique de 2 mots, le tout formant une chaîne de mot qui représentera le score de partie du joueur. 

Les joueurs proposent des mots proches en fonction de deux critères principaux : la similarité sémantique et la similarité orthographique. La similarité sémantique est la plus importante, où des mots comme 'hautbois' et 'clarinette' sont considérés comme similaires car ils sont généralement utilisés dans le même contexte. La similarité orthographique, quant à elle, se réfère à la proximité des orthographes, comme 'bateau' et 'château'. Le score de la chaîne de mots est déterminé par le score de similarité de son maillon le plus faible, ce qui signifie que l'objectif est de s'assurer que chaque mot ressemble autant que possible au précédent. 
En plus du jeu lui-même, l'application offre un système de compte pour que les joueurs puissent s'identifier, retrouver leurs parties passées ou en cours, et organiser des parties avec leurs contacts ou dans des salons publiques.

## Règles du jeu  :

Le jeu "Semantic Analogy Explorer" propose des règles simples mais stimulantes pour les parties en solo. Chaque partie est en théorie limitée à une durée maximale de 3 minutes, ce qui devrait pousser les joueurs à réfléchir rapidement et à faire des choix stratégiques. De plus, la chaîne de mots créée ne pourra pas dépasser une longueur maximale de 5 mots. Cela signifie que les joueurs doivent être créatifs et précis dans leur sélection de mots pour relier le mot de départ au mot cible. Enfin, il y a une limite d'insertion maximale, ce qui ajoute un défi supplémentaire pour les joueurs. Ces règles simples mais efficaces rendent le jeu à la fois amusant et stimulant pour les joueurs de tous niveaux. Cependant, les règles ayant été définies tardivement, elles ne sont pas toutes pleinement implémentées.

Pour les parties multijoueurs, ou MulThiv, les règles sont légèrement différentes pour permettre une expérience de jeu plus compétitive et collaborative. Le temps de la partie serait prolongé à une durée maximale de 6 minutes, ce qui donnerait aux joueurs plus de temps pour créer leur chaîne de mots. La longueur maximale de la chaîne de mots serait également augmentée à 20 mots, ce qui permettrait des chaînes plus longues et plus complexes. Enfin, les joueurs devraient pouvoir voir les scores des autres joueurs en temps réel, ce qui ajouterait une dimension compétitive au jeu. Tout comme la partie solo, les règles ayant été implémentées tardivement, elles ne sont pas non plus implémentées.

## Information de déploiement :

Pour lancer le WebSocket, vous pouvez regarder sur le dépôt git suivant : [Chatac by Codefish](https://gitlab.com/codefish42/chatac)

## Bugs et fonctionnalités non présentes :

- Le calcul des scores sémantiques ainsi que l’ajout des mot prend de plus en plus temps à se faire à mesure que le joueur ajoute des mots.
- Au bout d'environ 8 mots insérés dans la chaîne, le graphe disparaît et l'historique des mots aussi.
- Pour le moment, le websocket ne fonctionne qu'en localhost donc il est impossible de lancer le mode multijoueur sans le lancer sur la machine personnelle.
- Il y a de l'optimisation à faire côté Java en utilisant le cours d'Automates ( notamment avec le parcours du graphe par l'algorithme de Prim).
- La messagerie sur l’application mobile n’est pas encore fonctionnelle.
- Sur le site web, le chat ne fonctionne pas à cause du problème avec le websocket (c.f 3ème point)
- Le site n'est pas responsive (à cause des valeurs utilisées en px)
- Comme on a utilisé du SCSS, sur certains navigateurs qui ne sont pas à jour, il y a des bugs d'affichages comme un logo qui masque la page
- Parfois, le graphe se sépare en 2 à cause de mots à similarité trop faible

## Info supplémentaires :
Nous avons fusionné avec un autre groupe de SAE, ce faisant nous avons pu mettre en commun le module en C et nous nous somme principalement inspiré du code du groupe fusionné.

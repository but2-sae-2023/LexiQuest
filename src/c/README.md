Sae But2 Informatique

## Description:
Ce module en C est un module de score dédier au jeu Semantix. Il a pour but de calculer le score similarité entre 2 mots. Il a aussi pour fonction de créer un fichier de partie et d’ajouter les mots saisis par les joueurs dans celui-ci. Pour ce faire, on calcul d’une part la similarité orthographique grâce à Levenshtein et d’autre part, la similarité sémantique grâce aux vecteurs fourni par le fichier .bin. Le score entre 2 mots est la moyenne de ces 2 similarités.  
Le programme permet aussi de créer l’index des mots, sous forme d'un arbre lexicographique, à partir du fichier .bin. L’index est stocké dans un fichier binaire et est utilisé pour rechercher les mots et leurs offsets. C'est grâce à cette index que l'on peut récuperer les vecteurs des mots dans le dictionnaire et calculer la distance sémantique entre 2 mots.  


## Compilation du programme:
Pour exécuter les differents excutables, il faut lancer la ligne de commande  
```gcc ./executable/exec ./src/\*.c -o exec``` (-lm sur linux)  
Exemple: ```gcc ./executable/add_word.c ./src/*.c -o add_word```

Les différents éxecutables sont:   
-build_lex_index:  
Fonction: créer l'index de mots à partir du fichier .bin   
Compilation: ```gcc ./executable/build_lex_index.c ./src/*.c -o build_lex_index```

-new_game:  
créer le fichier de partie avec 2 mots aléatoires  
Compilation: ```gcc ./executable/new_game.c ./src/*.c -o new_game```  

-add_word:  
Fonction: ajoute un mot dans le fichier de partie   
Compilation: ```gcc ./executable/add_word.c ./src/*.c -o add_word```

-dictionary_lookup:  
Fonction: recherche l'offset d'un mot dans l'index  
Compilation: ```gcc ./executable/dictionary_lookup.c ./src/*.c -o dictionary_lookup```

-lev_similarity:  
Fonction: calcul la similarité orthographique entre 2 mots  
Compilation: ```gcc ./executable/lev_similarity.c ./src/*.c -o lev_similarity```

-sem_similarity:  
Fonction: calcul la similarité sémantique entre 2 mots   
Compilation: ```gcc ./executable/sem_similarity.c ./src/*.c -o sem_similarity```  

## Les exécutables
Pour chacun des exécutables, vous pouvez envoyer le paramètre ```--help``` pour avoir  des informations sur les paramètres attendus.  
Si vous envoyez aucun paramètre, le programme exécutera une fonction de test minimaliste pour certains exécutables, car d'autres ont besoins qu'on envoie le dictionnaire dans les parametres pour qu'ils soient fonctionnels, et affichera la auteurs  du projets.

#### build_lex index
```>build_lex file```   
build_lex_index est l'exécutable qui permet de créer l'index de mots à partir du fichier .bin (```file```). Le programme parcours le fichier .bin, récupère les mots et leurs offsets pour les stocker dans un arbre lexicogragraphique. L'arbre est ensuite stocké dans un fichier binaire dans /output/index.lex.  

#### new_game 
```>new_game file gameID word1 word2 ... wordn```  
new_game permet de créer un fichier de partie qui sera stocker dans './games/*id*-game/gameFile.txt'. Dans ce fichier il y aura aussi 2 mots, le mot de départ et de fin, qui auront été choisi aléatoirement parmis les mots envoyés en arguments. Le fichier de partie contient la liste des mots qui ont été ajouté par les joueurs, leur offset, et la moyenne de leur similarité orthographique et sémantique pour chaque mot.

#### add_word
```>add_word file gameID player word```  
L'exécutables add_word lit le fichier de la partie dont l'id est ```gameID```, et insère le nouveau mot(```word```) dans ce fichier en calculant sa distance sémantique et orthographique avec les autres mots déjà présents.

#### dictionary_lookup
```>dictionary_lookup word ```  
dictionary_lookup permet de rechercher un mot (```word```) dans l'index et de renvoyer son offset. Si le mot n'est pas présent dans l'index, l'offset renvoie -1 et un message d'erreur s'affiche.

#### lev_similarity  
```>lev_similarity word1 word2```  
Cette éxecutable permet de calculer la similarité orthographique entre 2 mots (```word1``` et ```word2```) grâce à la distance de Levenshtein.

#### sem_similarity
```>sem_similarity file word1 word2```  
sem_similarity calcule la similarité sémantique entre 2 mots (```word1``` et ```word2```). Le programme parcours l'arbre lexicographique de le l'index.lex pour trouver les offsets des mots. Il récupère ensuite les vecteurs des mots dans le dictionnaire et calcule la similarité sémantique entre les 2 mots.

## Le dictionnaire (.bin)
Le dictionnaire est un fichier binaire qui contient les mots et leurs vecteurs. Il est utilisé pour calculer la similarité sémantique entre 2 mots. Dans notre programme, nous avons défini qu'un mot peut avoir au maximum 300 vecteurs. Nous avons principalement utilisé le modèle de FastText (https://fasttext.cc/docs/en/crawl-vectors.html) lors de nos tests.


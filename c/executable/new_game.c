#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <sys/stat.h>
#include "../include/constante.h"
#include "../include/gamefile.h"
#include "../include/tree.h"
#include "../include/offset.h"
#include "../include/vector.h"
#include "../include/levenshtein.h"


void minimalTest() {
    char *word1, *word2;
    StaticTree stImported = importFromFile("./output/index.lex");
    if(stImported.nodeArray == NULL) {
        printf("Test minimaliste 'index' : echoue\n");
        exit(EXIT_FAILURE);
    }
    srand(time(NULL)); 
    word1 = randomWord(&stImported);
    word2 = randomWord(&stImported);
    long offset1 = stGetOffset(&stImported, word1);
    long offset2 = stGetOffset(&stImported, word2);
    if(offset1 != -1 && offset2 != -1){
        printf("Test minimaliste : succes\n");
    } else {
        printf("Test minimaliste ' recuperer offset': echoue\n");
    }
}

// Crée un fichier de partie gameFile.csv qui contients 2 mots de départs, leurs offsets et la distance entre les 2 mots
int main(int argc, char *argv[])
{
    if (argc == 1)
    {
        printf("Auteurs : Equipe non_alter_3 : \nAmaury BOOMS\nEnzo LETOCART\nLaxhan PUSHPAKUMAR\nLoic MAURITIUS\nRabah CHERAK\nThivakar JEYASEELAN\n ");
        minimalTest();
        exit(255);
    }
    else if (argc == 2 && strcmp("--help", argv[1]) == 0)
    {
        printf("Usage:\n");
        printf(" new_game file gameID word1 word2 ... wordn \n");
        printf("where FILE contains word projections in the BINARY FORMAT\n");
        exit(255);
    }
    else if (argc < 5) {
        printf("Erreur : Nombre d'arguments insuffisant.\n");
        printf("Utilisation : ./new_game <DICO> <GAMEID> <MOT1> <MOT2> ... \n");
        exit(EXIT_FAILURE);
    }


    char *file_name = argv[1];
    char *gameID = argv[2];

    // récupération des offsets des mots dans le dictionnaire
    StaticTree stImported = importFromFile("./output/index.lex");
   
    char word1[max_w];
    char word2[max_w];
    StartWords(word1, word2, argc, argv);
    // recupere leur offset
    long offset1 = stGetOffset(&stImported, word1);
    long offset2 = stGetOffset(&stImported, word2);
    if (offset1 == -1)
    {
        printf("Erreur : Impossible de trouver le premier mot aleatoire '%s' dans le dictionnaire \n", word1);
        exit(EXIT_FAILURE);
    }
    else if (offset2 == -1)
    {
        printf("Erreur : Impossible de trouver le mot aleatoire '%s' dans le dictionnaire \n", word2);
        exit(EXIT_FAILURE);
    }

    // calcul de la similarité sémantique et de la distance de levenshtein pour en faire la moyenne
    double sem_similarity = calculSem(file_name, word1, word2);
    double lev_similarity = levenshtein(word1, word2);
    double score = (sem_similarity + lev_similarity) / 2;

    //creation du dossier
    char gameDir[128];
    sprintf(gameDir, "./games/%s-game", gameID);
    mkdir(gameDir, 0755);

    // creation du fichier gameFile.csv
    char gameFile[128];
    sprintf(gameFile, "./games/%s-game/gameFile.txt", gameID);

    createGameFile(gameFile, word1, word2, offset1, offset2, sem_similarity,lev_similarity);

    FILE *file = fopen(gameFile, "r");
    if (file == NULL)
    {
        printf("Erreur : Impossible d'ouvrir le fichier gameFile.csv\n");
        exit(EXIT_FAILURE);
    }
    else
    {
        printf("Fichier gameFile.csv cree\n mot depart: %s \n mot final: %s \n ", word1, word2);
    }
    fclose(file);
    exit(EXIT_SUCCESS);
}


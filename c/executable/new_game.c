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

void minimalTest()
{
    char *word1, *word2;
    StaticTree stImported = importFromFile("./output/index.lex");
    if (stImported.nodeArray == NULL)
    {
        printf("Test minimaliste 'index' : echoue\n");
        exit(EXIT_FAILURE);
    }
    srand(time(NULL));
    word1 = randomWord(&stImported);
    word2 = randomWord(&stImported);
    long offset1 = stGetOffset(&stImported, word1);
    long offset2 = stGetOffset(&stImported, word2);
    if (offset1 != -1 && offset2 != -1)
    {
        printf("Test minimaliste : succes\n");
    }
    else
    {
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
    else if (argc < 5)
    {
        printf("Erreur : Nombre d'arguments insuffisant.\n");
        printf("Utilisation : ./new_game <DICO> <GAMEID> <MOT1> <MOT2> ... \n");
        exit(EXIT_FAILURE);
    }

    char *file_name = argv[1];
    char *gameID = argv[2];

    char word1[max_w];
    char word2[max_w];
    StartWords(word1, word2, argc, argv);
    // recupere leur offset
    long offset1 = fileGetOffset(word1);
    long offset2 = fileGetOffset(word2);
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

    char buffer[128];
    char command[256];
    double best_score=-1;

    sprintf(command, "python3 solveur.py %s %s", word1, word2);
    FILE *pipe = popen(command, "r");
    if (!pipe)
        return -1;

    while (fgets(buffer, sizeof(buffer), pipe) != NULL)
    {
        //printf("Output: %s", buffer);
        best_score = atof(buffer);
        printf("Score : %f\n", best_score);
        if(best_score==-1){
            printf("Erreur : Impossible de trouver la similarité entre les mots '%s' et '%s' \n", word1, word2);
            exit(EXIT_FAILURE);
        }
    }

    pclose(pipe);

    // creation du dossier
    char gameDir[128];
    sprintf(gameDir, "./games/%s-game", gameID);
    mkdir(gameDir, 0755);

    // creation du fichier gameFile.csv
    char gameFile[128];
    sprintf(gameFile, "./games/%s-game/gameFile.txt", gameID);


    // création du fichier best_score.txt et écriture du score
    char bestScoreFile[128];
    sprintf(bestScoreFile, "./games/%s-game/best_score.txt", gameID);
    FILE *bestScore = fopen(bestScoreFile, "w");
    if (bestScore == NULL)
    {
        printf("Erreur : Impossible de créer le fichier best_score.txt\n");
        exit(EXIT_FAILURE);
    }
    else
    {
        fprintf(bestScore, "%f", best_score);
    }
    fclose(bestScore);

    double score;
    double sem = calculSem(file_name, argv[3], argv[4]);
    double lev= levenshtein(argv[3], argv[4]);
    score = (sem*5+lev)/6;

    createGameFile(gameFile, word1, word2, offset1, offset2, score);

    FILE *file = fopen(gameFile, "r");
    if (file == NULL)
    {
        printf("Erreur : Impossible d'ouvrir le fichier gameFile.txt\n");
        exit(EXIT_FAILURE);
    }
    else
    {
        printf("Fichier '%s' cree\n mot depart: %s \n mot final: %s \n ", gameFile, word1, word2);
    }
    fclose(file);
    exit(EXIT_SUCCESS);
}

#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include "../include/gamefile.h"
#include "../include/tree.h"
#include "../include/offset.h"
#include "../include/vector.h"
#include "../include/levenshtein.h"

void minimalTest()
{
    StaticTree stImported = importFromFile("./output/index.lex");
    long offset = stGetOffset(&stImported, "mot");
    FILE *gamefile = fopen("./output/gameFile.csv", "r");
    if (offset == -1 || stImported.nodeArray == NULL || gamefile == NULL)
    {
        printf("Test minimaliste  : echoue\n");
    }
    else
    {
        printf("Test minimaliste : succès\n");
    }
}

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
        printf(" add_word file gameID player word \n");
        printf("where FILE contains word projections in the BINARY FORMAT and WORD is the word to add \n");
        exit(255);
    }
    else if (argc < 5)
    {
        printf("Erreur : Nombre d'arguments insuffisant.\n");
        printf("Utilisation : ./new_game <DICO> <GAMEID> <PLAYER> <MOT>\n");
        exit(EXIT_FAILURE);
    }

    char *file_name = argv[1];
    char *gameID = argv[2];
    char *player = argv[3];
    char *word = argv[4];

    StaticTree stImported = importFromFile("./output/index.lex");
    long offset = fileGetOffset(word);

    if (offset == -1)
    {
        printf("Erreur : Impossible de trouver le mot '%s' dans le dictionnaire \n", word);
        exit(EXIT_FAILURE);
    }

    char gameFile[128];
    sprintf(gameFile, "./games/%s-game/gameFile.txt", gameID);
    
    add_word(gameFile, file_name, word,offset, player);
    printf("Le mot '%s' a été ajouté au fichier de partie \n", word);

    exit(EXIT_SUCCESS);
}

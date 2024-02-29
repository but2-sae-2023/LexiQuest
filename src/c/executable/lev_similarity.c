#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include "../include/constante.h"
#include "../include/levenshtein.h"


void minimalTest() {
    double l = levenshtein("banane", "ananas");
    double l2= levenshtein("banane", "banane");
    if(l != 0 || l2 ==100){
        printf("Test minimaliste : succes\n");
    } else {
        printf("Test minimaliste : echoue\n");
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
        printf(" lev_similarity word1 word2 \n");
        printf("where WORD1 and WORD2 are two strings to compare with Levenshtein distance \n");
        exit(255);
    }
    else if (argc < 2)
    {
        printf("Erreur : Nombre d'arguments insuffisant.\n");
        printf("Usage: ./distance <WORD1> <WORD2>\n il manque le(s) mot(s) à comparer\n");
        exit(EXIT_FAILURE);
    }

    char *word1 = argv[1];
    char *word2 = argv[2];
    double l= levenshtein(word1, word2);
    printf("Levenshtein distance entre %s et %s: %f\n",word1, word2, l);

    return 0;
}

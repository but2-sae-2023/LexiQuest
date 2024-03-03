#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include "../include/constante.h"
#include "../include/tree.h"
#include "../include/vector.h"
#include "../include/offset.h"

int main(int argc, char *argv[]) {
    if (argc == 2 && strcmp("--help", argv[1]) == 0)
    {
        printf("Usage:\n");
        printf(" sem_similarity file word1 word2 \n");
        printf("where FILE contains word projections in the BINARY FORMAT\n");
        printf("and WORD1 and WORD2 are two strings to compare \n");
        exit(255);
    }
    else if (argc == 1)
    {
        printf("Auteurs : Equipe non_alter_3 : \nAmaury BOOMS\nEnzo LETOCART\nLaxhan PUSHPAKUMAR\nLoic MAURITIUS\nRabah CHERAK\nThivakar JEYASEELAN\n ");
        exit(255);
    }
    else if(argc < 4){
        printf("Utilisation : ./sem_similarity <DICO> <MOT1> <MOT2>\n");
       exit(EXIT_FAILURE);
    }
    char *file_name = argv[1];
    char *word1 = argv[2];
    char *word2 = argv[3];
    double similarity = calculSem(file_name, argv[2], argv[3]);
    printf("La similarité sémantique entre %s et %s est : %f\n", word1, word2, similarity);

    exit(EXIT_SUCCESS);
}

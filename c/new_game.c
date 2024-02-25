#include <stdio.h>
#include <string.h>
#include <stdlib.h>
#include <time.h>
#include <unistd.h>
#include <stdarg.h>
#include "lev_similarity.h"
#include "sem_similarity.h"
#include "dict_lookup.h"

#define MAX_WORD_LENGTH 100
#define MAX_LINE_COUNT 250

void chooseRandomWords(char *mot, ...) {
    va_list args;
    va_start(args, mot);
    char *currentMot = mot;
    while (currentMot != NULL) {
        currentMot = va_arg(args, char*);
        if (currentMot != NULL) {
            printf("Mot : %s\n", currentMot);
        }
    }
    va_end(args);
}

void StartWords(char *mot1, char *mot2, int argc, char **argv)
{
    // Choisir deux mots au hasard parmi ceux entrés en paramètre
    srand(time(NULL));
    int random1 = rand() % (argc - 2) + 2;
    int random2 = rand() % (argc - 2) + 2;
    while (random1 == random2)
    {
        random2 = rand() % (argc - 2) + 2;
    }
    strcpy(mot1, argv[random1]);
    strcpy(mot2, argv[random2]);
    printf("Mot 1 : %s\n", mot1);
    printf("Mot 2 : %s\n", mot2);
}

void writeWords(const char *filename, char *mot1, char *mot2)
{
    // Ouvrir le fichier gameFile.txt pour écriture
    FILE *motFile = fopen("gameFile.txt", "w+");
    if (motFile == NULL)
    {
        printf("Erreur lors de l'ouverture du fichier mot.txt pour écriture\n");
        exit(EXIT_FAILURE);
    }

    // Écrire les mots sélectionnés dans gameFile.txt
    fprintf(motFile, "2,4\n");
    fprintf(motFile, "%s,%s\n", mot1, mot2);
    fprintf(motFile, "%s,depart,%d\n", mot1, getOffset(filename, mot1));
    fprintf(motFile, "%s,fin,%d\n", mot2, getOffset(filename, mot2));
    fprintf(motFile, "%s,%s,%f,%f\n", mot1, mot2, levenshtein_similarity(mot1, mot2), sem_similarity("output.bin", mot1, mot2));

    // Fermer le fichier gameFile.txt
    fclose(motFile);
}

int main(int argc, char **argv)
{
    // Fonctionnement de new_game : 
    // 1. Choisir deux mots au hasard parmi ceux entrés en paramètre
    // 2. Écrire les mots sélectionnés dans gameFile.txt
    // 3. Lancer le jeu
    if (argc < 4) {
        printf("Usage: %s <dictionary> <word1> <word2> ...\n", argv[0]);
        exit(EXIT_FAILURE);
    }

    const char *filename = argv[1];
    
    // Choisir deux mots au hasard parmi ceux entrés en paramètre
    char mot1[MAX_WORD_LENGTH];
    char mot2[MAX_WORD_LENGTH];
    StartWords(mot1, mot2, argc, argv);

    // Écrire les mots sélectionnés dans gameFile.txt
    writeWords(filename, mot1, mot2);

    return 0;
}

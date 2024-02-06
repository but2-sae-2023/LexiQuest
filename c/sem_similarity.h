#include <stdio.h>
#include <stdlib.h>
#include <math.h>
#include <string.h>
#include "dict_lookup.h"

#ifndef SEM_SIMILARITY_H
#define SEM_SIMILARITY_H

const long long max_size = 2000; // max length of strings
const long long N = 40;          // number of closest words that will be shown
const long long max_w = 50;

float sem_similarity(const char *file_name, const char *mot1, const char *mot2)
{
    FILE *f;
    long long words, size, a;
    float len;

    long long offset1 = getOffset("output.lex", mot1);
    long long offset2 = getOffset("output.lex", mot2);

    if (offset1 == -1 || offset2 == -1)
    {
        printf("One or both words not found in the dictionary.\n");
        return -1.0; // Or handle the error in a way that suits your application
    }

    f = fopen(file_name, "rb");
    if (f == NULL)
    {
        printf("Input file not found\n");
        exit(EXIT_FAILURE);
    }

    fscanf(f, "%lld", &words);
    fscanf(f, "%lld", &size);

    float vec1[size], vec2[size];

    // Test de lecture des vecteurs des mots
    fseek(f, offset1, SEEK_SET);

    char word[max_w];
    char *ch;
    word[0] = 0;
    fscanf(f, "%[^ ]", word);
    fscanf(f, "%c", &ch);

//    for (a = 0; a < size; a++) {
//        fread(&vec1[a], sizeof(float), 1, f);
//        printf("%f ", vec1[a]);
//    }

    fseek(f, offset2, SEEK_SET);

    fscanf(f, "%[^ ]", word);
    fscanf(f, "%*c", &ch);

//    for (a = 0; a < size; a++) {
//        fread(&vec2[a], sizeof(float), 1, f);
//        printf("%f ", vec2[a]);
//    }
//    printf("\n");


    fclose(f);


    // Déplacement dans le fichier pour lire les vecteurs des mots
    // Calcul : offset du mot * taille des vecteurs * taille d'un float (car ce sont des float) + 2 * sizeof(long long) (car il y a 2 long long au début du fichier)

    // Normalisation des vecteurs
    float len1 = 0;
    float len2 = 0;
    for (a = 0; a < size; a++)
    {
        len1 += vec1[a] * vec1[a];
        len2 += vec2[a] * vec2[a];
    }
    len1 = sqrt(len1);
    len2 = sqrt(len2);

    // Produit scalaire
    //printf("Produit scalaire...\n");
    len = 0;
    for (a = 0; a < size; a++)
    {
        len += vec1[a] * vec2[a];
    }
    len = len / (len1 * len2);

    return len * 100;
}


#endif
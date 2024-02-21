#ifndef LEVENSHTEIN_H
#define LEVENSHTEIN_H

#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <ctype.h>
#include <assert.h>
#include <math.h>

//structure de tableau à deux dimensions, dédié à l'algorithme de Levenshtein
typedef struct {
    int lenS;
    int lenT;
    int * tab;
}
LevArray;

//minimum de deux entiers
int min(int a, int b);

int max(int a, int b);

//initialiser un tableau pour des chaînes d'une taille donnée
LevArray init(int lenS, int lenT);

//set: insérer une valeur dans le tableau
void set(LevArray a, int indexS, int indexT, int val);

//renvoie la valeur correspondant à des indices donnés
//   i+1 pour les requêtes du type get(a, -1, i) ou get (a, i, -1)
int get(LevArray a, int indexS, int indexT);

//levenshtein: calcule la distance de levenshtein de deux chaînes
double levenshtein(char * S, char * T);
#endif
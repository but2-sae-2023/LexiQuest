#ifndef VECTOR_H
#define VECTOR_H

#include <stdio.h>
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <ctype.h>
#include <assert.h>
#include <math.h>

//Recuperer les vecteur d'un mots (il mets les vecteurs dans le tableau M )
void getVec(FILE *f, int offset, int size, float *M);

// Calculer la norme d'un vecteur
double norm(float *vec, int size);

// Calculer le produit scalaire de deux vecteurs
double dot_product(float *vec1, float *vec2, int size);

// Calculer le cosinus entre deux vecteurs
double semantic(float *vec1, float *vec2, int size);

double calculSem(char *file_name,char *word1, char *word2);

double calculSemFromOffst(char *file_name, int offset1, int offset2);

#endif
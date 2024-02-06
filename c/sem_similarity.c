#include <stdio.h>
#include <string.h>
#include <stdlib.h> // Pour la fonction malloc
#include <math.h>   // Pour la fonction sqrt
#include "sem_similarity.h"

int main(int argc, char *argv[])
{
    if (argc != 4)
    {
        printf("Usage: %s <dictionary> <mot1> <mot2>\n", argv[0]);
        return 1;
    }

    const char *file_name = argv[1];
    const char *mot1 = argv[2];
    const char *mot2 = argv[3];

    printf("La distance sémantique entre %s et %s est de : %f", mot1, mot2, sem_similarity(file_name, mot1, mot2));

    return 0;
}
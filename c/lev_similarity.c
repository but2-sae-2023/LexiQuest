#include <stdio.h>
#include <string.h>
#include "lev_similarity.h"



int main(int argc, char *argv[])
{
    if (argc != 3)
    {
        printf("Usage: %s <mot1> <mot2>\n", argv[0]);
        return 1;
    }

    const char *mot1 = argv[1];
    const char *mot2 = argv[2];

    double distance = levenshtein_similarity(mot1, mot2);
    printf("Distance de Levenshtein entre '%s' et '%s' : %f%%\n", mot1, mot2, distance);

    return 0;
}

#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include "../include/vector.h"
#include "../include/constante.h"

typedef struct
{
    int id;
    int offset;
    double distance;
} Link;

typedef struct
{
    int offset;
    Link *links;
} Sommet;

typedef struct
{
    int offset;
    Link maxDistance;
    int isTreated;
} AllWords;

void updateLinks(Sommet *sommets, AllWords *allWords, int idAMax, int idBMax, double distMax, int *nbSommets)
{
    allWords[idBMax].isTreated = 1;
    sommets[*nbSommets].offset = allWords[idBMax].offset;
}

int processWords(Sommet *sommets, AllWords *allWords, double *distMax, int nbSommets, long words, FILE *fout)
{
    double distance;
    int idAMax, idBMax = -1;

    for (int j = 0; j < words; j++)
    {
        if (allWords[j].isTreated == 0)
        {
            double distance = calculSemFromOffst("./dico.bin", sommets[nbSommets - 1].offset, allWords[j].offset);

            if (distance > allWords[j].maxDistance.distance)
            {
                allWords[j].maxDistance.distance = distance;
                allWords[j].maxDistance.id = nbSommets - 1;
                allWords[j].maxDistance.offset = allWords[j].offset;
            }
            double dist = allWords[j].maxDistance.distance;
            if (dist > *distMax)
            {
                *distMax = dist;
                idAMax = allWords[j].maxDistance.id;
                idBMax = j;

                // printf("distance max : %f\n", distance);
            }
        }
    }

    if (idBMax != -1)
    { // Vérifie si un idBMax a été trouvé

        updateLinks(sommets, allWords, idAMax, idBMax, *distMax, &nbSommets);
        fprintf(fout, "%d,%d,%.2f\n", allWords[idBMax].offset, sommets[idAMax].offset, *distMax);
        printf("%d,%d,%.2f\n", allWords[idBMax].offset, sommets[idAMax].offset, *distMax);

       }

    return idBMax;
}

int main(int argc, char *argv[])
{
    FILE *f;
    FILE *fout;
    int words, size;
    f = fopen(argv[1], "rbc");
    fout = fopen("tree.txt", "w+");
    if (f == NULL || fout == NULL)
    {
        printf("Le fichier '%s' n'a pas ete trouve.\n", argv[1]);
        exit(EXIT_FAILURE);
    }
    fscanf(f, "%d", &words);
    fscanf(f, "%d", &size);
    printf("nb mots : %d\n", words);

    int a, b;
    char tempC;
    int nbSommets = 0, nbNonTraites = 0;
    int line = 0;
    Sommet *sommets = (Sommet *)malloc(words * sizeof(Sommet));
    AllWords *allWords = (AllWords *)malloc(words * sizeof(AllWords));

    for (b = 0; b < words; b++)
    {
        long offst = ftell(f);
        a = 0;
        while (1)
        {
            tempC = fgetc(f);
            if (feof(f) || (tempC == ' '))
                break;
            if ((a < max_w) && (tempC != '\n'))
                a++;
        }
        for (a = 0; a < size; a++)
        {
            float *temp;
            fread(&temp, sizeof(float), 1, f);
        }

        allWords[b].offset = offst;
        allWords[b].isTreated = 0;
        allWords[b].maxDistance.distance = 0;
    }
    fclose(f);
    sommets[0].offset = allWords[0].offset;
    allWords[0].isTreated = 1;
    nbSommets++;
    double distance, distMax = 0;
    int idAMax, idBMax;
    int result;
    do
    {
        result = processWords(sommets, allWords, &distMax, nbSommets, words, fout);
        line++;
        printf("Ligne %d\n", line);
        distMax = 0;
        nbSommets++;
    } while (result != -1);
    fclose(fout);
    return 0;
}

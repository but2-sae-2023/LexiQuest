#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include "../include/vector.h"
#include "../include/constante.h"
#include "../include/levenshtein.h"

typedef struct
{
    int id;
    int offset;
    double distance;
} Link;

typedef struct
{
    char *word;
    int offset;
} Sommet;

typedef struct
{
    char *word;
    int offset;
    Link maxDistance;
    int isTreated;
} AllWords;

void updateLinks(Sommet *sommets, AllWords *allWords, int idBMax, int *nbSommets)
{
    allWords[idBMax].isTreated = 1;
    sommets[*nbSommets].offset = allWords[idBMax].offset;
    sommets[*nbSommets].word = allWords[idBMax].word;
}

int processWords(Sommet *sommets, AllWords *allWords, double *distMax, int nbSommets, long words, FILE *fout)
{
    double distance;
    int idAMax, idBMax = -1;
    int lev=0;
    int sem=0;

    for (int j = 0; j < words ; j++)
    {
        if (allWords[j].isTreated == 0)
        {
            double semDist = calculSemFromOffst("./dico.bin", sommets[nbSommets - 1].offset, allWords[j].offset); 
            double levDist= levenshtein(allWords[j].word, sommets[nbSommets - 1].word);
            distance = (semDist*5+levDist)/6;
            //printf("%s,%s: %f\n",allWords[j].word, sommets[nbSommets - 1].word, semDist);
            //printf("diff : %f\n", semDist-distance);
            //MAX des 2
            /*if(levDist>semDist){
                distance=levDist;
                lev++;
            }
            else {
                distance=semDist;
                sem++;
                }
               */ 
            if (distance > allWords[j].maxDistance.distance)
            {
                //printf("diff : %f\n", semDist-levDist);
                printf("%s-%s lev : %f, sem : %f, score: %f\n",allWords[j].word,sommets[nbSommets-1].word , levDist, semDist, distance);
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
                //printf("%s - %s : %f\n", allWords[j].word, sommets[nbSommets - 1].word,dist);
            }
        }
    }
    if (idBMax != -1)
    { // Vérifie si un idBMax a été trouvé
        
        updateLinks(sommets, allWords, idBMax, &nbSommets);
        fprintf(fout, "%s,%s,%.2f\n", allWords[idBMax].word, sommets[idAMax].word, *distMax);
        printf("--%s,%s,%.2f--\n", allWords[idBMax].word, sommets[idAMax].word, *distMax);
    

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
    char *tempC;
    tempC=(char *)malloc((int)words * max_w * sizeof(char));
    int nbSommets = 0, nbNonTraites = 0;
    int line = 0;
    Sommet *sommets = (Sommet *)malloc(words * sizeof(Sommet));
    AllWords *allWords = (AllWords *)malloc(words * sizeof(AllWords));
    char c;

    for (b = 0; b < words; b++)
    {
        
        long offst = ftell(f);
        a = 0;
        while (1)
        {
            c = fgetc(f);
            if (feof(f) || (c == ' '))
                break;
            if ((a < max_w) && (c != '\n'))
            {
                tempC[b * max_w + a] = c;
                a++;
            }
        }
        for (a = 0; a < size; a++)
        {
            float *temp;
            fread(&temp, sizeof(float), 1, f);
        }
        allWords[b].word = &tempC[b * max_w];
        allWords[b].offset = offst;
        allWords[b].isTreated = 0;
        allWords[b].maxDistance.distance = 0;
    }
    fclose(f);
    sommets[0].offset = allWords[10].offset;
    sommets[0].word = allWords[10].word;
    allWords[10].isTreated = 1;
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
    free(tempC);
    return 0;
}

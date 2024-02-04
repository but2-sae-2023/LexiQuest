#include <math.h>
#ifndef SEM_SIMILARITY_H
#define SEM_SIMILARITY_H

const long long max_size = 2000; // max length of strings
const long long N = 40;          // number of closest words that will be shown
const long long max_w = 50;


double sem_similarity(const char *file_name, const char *mot1, const char *mot2)
{
    FILE *f;
    char st1[max_size];
    char *bestw[N];
    char st[100][max_size];
    float dist, len, bestd[N], vec[max_size];
    long long words, size, a, b, c, d, cn, bi[100];
    char ch;
    float *M;
    char *vocab;

    f = fopen(file_name, "rb");
    if (f == NULL)
    {
        printf("Input file not found\n");
        exit(EXIT_FAILURE);
    }
    fscanf(f, "%lld", &words);
    fscanf(f, "%lld", &size);
    vocab = (char *)malloc((long long)words * max_w * sizeof(char));
    for (a = 0; a < N; a++)
        bestw[a] = (char *)malloc(max_size * sizeof(char));
    M = (float *)malloc((long long)words * (long long)size * sizeof(float));
    if (M == NULL)
    {
        printf("Cannot allocate memory: %lld MB    %lld  %lld\n", (long long)words * size * sizeof(float) / 1048576, words, size);
        exit(EXIT_FAILURE);
    }
    for (b = 0; b < words; b++)
    {
        a = 0;
        while (1)
        {
            vocab[b * max_w + a] = fgetc(f);
            if (feof(f) || (vocab[b * max_w + a] == ' '))
                break;
            if ((a < max_w) && (vocab[b * max_w + a] != '\n'))
                a++;
        }
        vocab[b * max_w + a] = 0;
        for (a = 0; a < size; a++)
            fread(&M[a + b * size], sizeof(float), 1, f);
        len = 0;
        for (a = 0; a < size; a++)
            len += M[a + b * size] * M[a + b * size];
        len = sqrt(len);
        for (a = 0; a < size; a++)
            M[a + b * size] /= len;
    }
    fclose(f);

    // Recherche des indices des mots dans le vocabulaire
    long long offset1 = -1, offset2 = -1;
    for (a = 0; a < words; a++)
    {
        if (!strcmp(&vocab[a * max_w], mot1))
        {
            offset1 = a;
            break;
        }
    }
    for (a = 0; a < words; a++)
    {
        if (!strcmp(&vocab[a * max_w], mot2))
        {
            offset2 = a;
            break;
        }
    }

    if (offset1 == -1 || offset2 == -1)
    {
        fprintf(stderr, "One or both words not found in the vocabulary\n");
        exit(EXIT_FAILURE);
    }

    // Calcul de la similarité cosinus entre les vecteurs de mot1 et mot2
    for (a = 0; a < size; a++)
    {
        vec[a] = M[a + offset1 * size];
    }

    len = 0;
    for (a = 0; a < size; a++)
    {
        len += vec[a] * M[a + offset2 * size];
    }
    len = sqrt(len);

    //printf("Similarité cosinus entre '%s' et '%s' : %f\n", mot1, mot2, 100*len);
    return 100*len;
}

#endif
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include "../include/constante.h"
#include "../include/tree.h"
#include <locale.h>

int main(int argc, char *argv[])
{
    FILE *f;
    float *M;
    long long words, size;
    char *vocab;

    if (argc == 2 && strcmp("--help", argv[1]) == 0)
    {
        printf("Usage:\n");
        printf(" build_lex_index file \n");
        printf("where FILE contains word projections in the BINARY FORMAT \n");
        exit(255);
    }
    else if (argc == 1)
    {
        printf("Auteurs : Equipe non_alter_3 : \nAmaury BOOMS\nEnzo LETOCART\nLaxhan PUSHPAKUMAR\nLoic MAURITIUS\nRabah CHERAK\nThivakar JEYASEELAN\n ");
        exit(255);
    }
    f = fopen(argv[1], "rbc");
    if (f == NULL)
    {
        printf("Le fichier '%s' n'a pas ete trouve.\n", argv[1]);
        exit(EXIT_FAILURE);
    }

    printf("Creation de l'index\n");

    fscanf(f, "%lld", &words);
    fscanf(f, "%lld", &size);
    vocab = (char *)malloc((long long)words * max_w * sizeof(char));
    M = (float *)malloc((long long)words * (long long)size * sizeof(float));
    if (M == NULL)
    {
        printf("Cannot allocate memory: %lld MB    %lld  %lld\n", (long long)words * size * sizeof(float) / 1048576, words, size);
        exit(EXIT_FAILURE);
    }

    // exportation des mots et de leur offset dans le fichier .lex
    CSTree t = createTree(f, M, vocab, size, words);

    StaticTree st = exportStaticTree(t);
    exportToFile(&st, "./output/index.lex");
    printf("Arbre statique exporte dans /output/index.lex\n");
    // printNicePrefixStaticTree(&st);
    //  Libération de la mémoire
    free(vocab);
    free(M);
    fclose(f);

    exit(EXIT_SUCCESS);
}
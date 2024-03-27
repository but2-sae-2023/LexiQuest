#include <stdio.h>
#include <stdlib.h>
#include <string.h>

typedef struct {
    char word[256];
    char joueur[256];
    long offset;

} wordCell;

typedef wordCell *wordList;




void createGameFile(const char *filename, char *word1, char *word2, long offset1, long offset2, double score);

void add_word(const char *filename, char *dictionary, char *newWord, long offset, char *player);

int getNbCouples(int nMots);

char* int_to_string(int number);

void StartWords(char *mot1, char *mot2, int argc, char **argv);
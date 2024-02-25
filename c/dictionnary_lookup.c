#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include "dict_lookup.h"


int main(int argc, char *argv[])
{
    if (argc != 3)
    {
        printf("Usage: %s <dictionary> <mot>\n", argv[0]);
        exit(EXIT_FAILURE);
    }
    printf("Le mot '%s' a un offset de %d dans le dictionnaire.\n", argv[2], getOffset(argv[1], argv[2]));

}
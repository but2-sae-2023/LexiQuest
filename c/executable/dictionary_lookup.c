#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include "../include/constante.h"
#include "../include/tree.h"
#include "../include/offset.h"

void minimalTest() {
    StaticTree stImported = importFromFile("./output/index.lex");
    long o = stGetOffset(&stImported, "mot");
    if(o != -1){
        printf("Test minimaliste : succes\n");
    } else {
        printf("Test minimaliste : echoue\n");
    }
}

int main(int argc, char *argv[])
{
    if (argc == 1)
    {
        printf("Auteurs : Equipe non_alter_3 : \nAmaury BOOMS\nEnzo LETOCART\nLaxhan PUSHPAKUMAR\nLoic MAURITIUS\nRabah CHERAK\nThivakar JEYASEELAN\n ");
        //minimalTest();
        exit(255);
    }
    else if (argc == 2 && strcmp("--help", argv[1]) == 0)
    {
        printf("Usage:\n");
        printf(" dictionary_lookup word \n");
        printf("where WORD is the word to search in the dictionary \n");
        exit(255);
    }
    else if (argc < 2)
    {
        printf("Erreur : Nombre d'arguments insuffisant.\n");
        printf("Utilisation : ./dictionary_lookup <MOT>\n");
        exit(EXIT_FAILURE);
    }

    

    char *word = argv[1];
    
    long o = fileGetOffset(word);
    if (o == -1)
    {
        printf("Erreur : Le mot '%s' n'a pas été trouvé dans le dictionnaire\n", word);
        exit(EXIT_FAILURE);
    }

    printf("Mot trouve \n");
    printf("Offset de %s: %ld\n", word, o);

    return 0;
}

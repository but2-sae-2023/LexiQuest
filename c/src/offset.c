#include "../include/constante.h"
#include "../include/tree.h"
#include "../include/offset.h"

// return l'offset d'un element(le dernier element du mot le contient)
long getElemOfst(StaticTree *st, char lettre, int pos)
{
    int a = pos;
    while (st->nodeArray[a].nSiblings >= 0)
    {
        if (st->nodeArray[a].elem == lettre)
        {
            return st->nodeArray[a].offset;
        }
        a++;
    }
    return -1;
}

// avoir l'offset d'un mot dans l'arbre statique
long stGetOffset(StaticTree *st, char mot[])
{
    int a = 1;
    long offset = -1;

    // Pour chaque lettre du mot
    for (int i = 0; i < strlen(mot); i++)
    {
        // Si derniere lettre on recupere l'offset dans l'arbre
        if (i == strlen(mot) - 1)
        {
            offset = getElemOfst(st, mot[i], a);
        }
        //Sinon on continue de parcourir l'arbre
        else
        {
            a = stGetFirstChild(st, mot[i], a);
            if (a == -1)
            {
                return -1;
            }
        }
    }
    return offset;
}


long fileGetOffset( char *word)
{
    FILE *file = fopen("./output/index.lex", "rb");
    if (file == NULL)
    {
        printf("Unable to open file oui\n");
        exit(EXIT_FAILURE);
    }
    fseek(file, 0, SEEK_SET);
    ArrayCell cell;

    int i = 0;


    while (fread(&cell, sizeof(ArrayCell), 1, file) == 1)
    {
        //si elem = lettre
        if (cell.elem == word[i])
        {
            //si la cellule n'a pas d'enfants
            if(cell.firstChild == -1)
            {
                fclose(file);
                return -1;
            }
            
            //si derniere lettre retourne l'offset
            else if(i == strlen(word) - 1)
            {
                //printf("offset de %s: %ld \n",word, cell.offset);
                fclose(file);
                return cell.offset;
            }
            else{            
            fseek(file, cell.firstChild * sizeof(ArrayCell), SEEK_SET);
            i++;
            }
            
        }
    }
    fclose(file);
    return -1;


}
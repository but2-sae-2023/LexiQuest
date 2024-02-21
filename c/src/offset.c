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


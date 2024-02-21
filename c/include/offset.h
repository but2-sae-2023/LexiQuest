#ifndef OFFSET_H
#define OFFSET_H

#include <stdio.h>
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <ctype.h>
#include <assert.h>
#include <math.h>


// return l'offset d'un element(le dernier element du mot le contient)
long getElemOfst(StaticTree *st, char lettre, int pos);


// avoir l'offset d'un mot dans l'arbre statique
long stGetOffset(StaticTree *st, char mot[]);

#endif
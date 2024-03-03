#ifndef TREE_H
#define TREE_H

#include <stdio.h>
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <ctype.h>
#include <assert.h>
#include <math.h>
#include <time.h>
#include <wctype.h>
#include <locale.h>

// Structures des arbres ------------------------------------------------------------------------------------------------------------------

typedef char Element;

typedef struct node
{
    Element elem;
    long offset;
    struct node *firstChild;
    struct node *nextSibling;
} Node;

typedef Node *CSTree;

typedef struct
{
    Element elem;
    long offset;
    unsigned int firstChild;
    unsigned int nSiblings;
} ArrayCell;

typedef struct
{
    ArrayCell *nodeArray;
    unsigned int nNodes;
} StaticTree;



// Méthodes sur les arbres ----------------------------------------------------------------------------------------------------------------

// Crée un arbre CSTree
CSTree newCSTree(Element elem, CSTree firstChild, CSTree nextSibling);

// Vérifier si un frère est égal à e (inutile pour le moment)
CSTree siblingLookup(CSTree t, Element e);

// Renvoie le premier frère de *t contenant e, un nouveau nœud est créé si absent
// Utilisé pour créer l'arbre à partir du fichier .bin
CSTree sortContinue(CSTree *t, Element e, Element child);

int nChildrenAux(CSTree t);

int nChildren(CSTree t);

// Compte le nombre de nœuds dans l’arbre t.
int size(CSTree t);

// Remplit les cellules du tableau statique avec les informations de l'arbre t.
void fill_array_cells(StaticTree *st, CSTree t, int index_for_t, int nSiblings, int *reserved_cells);

// Crée un arbre statique avec le même contenu que CSTree t.
StaticTree exportStaticTree(CSTree t);

// Fonctions d'impression d'un arbre statique:
void printNicePrefixStaticTree_aux(StaticTree *st, int index, int depth);

void printNicePrefixStaticTree(StaticTree *st);

void printDetailsStaticTree(StaticTree *st);

void addTree(CSTree t, char *mot, long offst);

int stGetFirstChild(StaticTree *st, char lettre, int pos);

void exportToFile(StaticTree *st, const char *filename);

StaticTree importFromFile(const char *filename);

CSTree createTree(FILE *f, float *M, char *vocab, long long size, long long words);

char* randomWord(StaticTree *st);

char* randomLetter(StaticTree *st, int index,char* word);


#endif

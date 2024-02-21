#include "../include/constante.h"
#include "../include/tree.h"

// Crée un arbre CSTree
CSTree newCSTree(Element elem, CSTree firstChild, CSTree nextSibling)
{
    CSTree t = malloc(sizeof(Node));
    if (t == NULL)
        exit(EXIT_FAILURE);
    long offst = -1;
    t->offset = offst;
    t->elem = elem;
    t->firstChild = firstChild;
    t->nextSibling = nextSibling;
    return t;
}

// Vérifier si un frère est égal à e (inutile pour le moment)
CSTree siblingLookup(CSTree t, Element e)
{
    if (t == NULL)
        return NULL;
    if (t->elem == e)
        return t;
    if (t->elem < e)
        return siblingLookup(t->nextSibling, e);
    return NULL;
}

// Renvoie le premier frère de *t contenant e, un nouveau noeud est créé si absent
// Utilisé pour créer l'arbre à partir du fichier .bin
CSTree sortContinue(CSTree *t, Element e, Element child)
{
    if (*t == NULL || (*t)->elem > e)
    {
        CSTree tChild = newCSTree(child, NULL, NULL);
        *t = newCSTree(e, tChild, (*t));
        return (*t);
    }
    if ((*t)->elem == e)
        return ((*t));
    return sortContinue(&(*t)->nextSibling, e, child);
}

int nChildrenAux(CSTree t)
{
    if (t == NULL)
        return 0;
    return nChildrenAux(t->nextSibling) + 1;
}

int nChildren(CSTree t)
{
    if (t == NULL)
        return 0;
    return nChildrenAux(t->firstChild);
}

// Compte le nombre de noeuds dans l’arbre t.
int size(CSTree t)
{
    if (t == NULL)
        return 0;
    return 1 + size(t->firstChild) + size(t->nextSibling);
}

//
void fill_array_cells(StaticTree *st, CSTree t, int index_for_t, int nSiblings, int *reserved_cells)
{
    st->nodeArray[index_for_t].nSiblings = nSiblings;
    st->nodeArray[index_for_t].elem = t->elem;
    st->nodeArray[index_for_t].firstChild = NONE;
    st->nodeArray[index_for_t].offset = t->offset;

    if (t->firstChild != NULL)
    {
        int index_for_firstChild = *reserved_cells;
        st->nodeArray[index_for_t].firstChild = *reserved_cells;
        int children_of_t = nChildren(t);
        *reserved_cells += nChildren(t);
        fill_array_cells(st, t->firstChild, index_for_firstChild, children_of_t - 1, reserved_cells);
    }

    if (t->nextSibling != NULL)
    {
        fill_array_cells(st, t->nextSibling, index_for_t + 1, nSiblings - 1, reserved_cells);
    }
}

// Crée un arbre statique avec le même contenu que CSTree t.
StaticTree exportStaticTree(CSTree t)
{
    StaticTree st = {NULL, 0};
    st.nNodes = size(t);
    st.nodeArray = malloc(sizeof(ArrayCell) * st.nNodes);
    int reserved_cells = nChildrenAux(t);
    fill_array_cells(&st, t, 0, reserved_cells - 1, &reserved_cells);
    if (reserved_cells != st.nNodes && t != NULL)
    {
        printf("erreur lors de la création de l'arbre statique, taille finale incorrecte\n");
        exit(EXIT_FAILURE);
    }
    return st;
}

// Fonctions d'impression d'un arbre statique:
void printNicePrefixStaticTree_aux(StaticTree *st, int index, int depth)
{
    if (index == NONE) return;
    for (int i = 0; i < depth; i++)
        printf("    ");
    printf("%c  --- %ld\n ", st->nodeArray[index].elem, st->nodeArray[index].offset);
    printf("\n");
    printNicePrefixStaticTree_aux(st, st->nodeArray[index].firstChild, depth + 1);
    if (st->nodeArray[index].nSiblings > 0)
        printNicePrefixStaticTree_aux(st, index + 1, depth);
}

//
void printNicePrefixStaticTree(StaticTree *st)
{
    if (st->nNodes > 0)
        printNicePrefixStaticTree_aux(st, 0, 0);
}

//
void printDetailsStaticTree(StaticTree *st)
{
    int i;
    printf("elem     \t");
    for (i = 0; i < st->nNodes; i++)
        printf("%c\t", st->nodeArray[i].elem);
    printf("\nfirstChild\t");
    for (i = 0; i < st->nNodes; i++)
        printf("%d\t", st->nodeArray[i].firstChild);
    printf("\nnSiblings\t");
    for (i = 0; i < st->nNodes; i++)
        printf("%d\t", st->nodeArray[i].nSiblings);
    printf("\n");
}

// ajout d'un mot dans l'arbre CSTree
void addTree(CSTree t, char *mot, long offst)
{
    CSTree t2 = t;
    int i;
    for (i = 0; i < strlen(mot); i++)
    {
        if (i == strlen(mot) - 1)
        {
            t2 = sortContinue(&(t2->firstChild), mot[i], '\0');
            t2->offset = offst;
        }
        else
        {

            t2 = sortContinue(&(t2->firstChild), mot[i], mot[i + 1]);
        }
    }
}

// pour parcourir l'arbre statique
int stGetFirstChild(StaticTree *st, char lettre, int pos)
{
    int a = pos;

    while (st->nodeArray[a].nSiblings >= 0)
    {
        if (st->nodeArray[a].elem == lettre)
        {
             return st->nodeArray[a].firstChild;
        }
        a++;
    }
    return -1;
}

// Fonction pour écrire la structure du staticTree dans un fichier
void exportToFile(StaticTree *st, const char *filename)
{
    FILE *file = fopen(filename, "wb");
    if (file == NULL)
    {
        printf("Unable to open file %s\n", filename);
        return;
    }
    fwrite(st->nodeArray, sizeof(ArrayCell), st->nNodes, file);
    fclose(file);
}

// Fonction pour lire un staticTree a partir d'un fichier
StaticTree importFromFile(const char *filename)
{
    FILE *file = fopen(filename, "rb");
    if (file == NULL)
    {
        printf("Unable to open file %s\n", filename);
        exit(EXIT_FAILURE);
    }

    fseek(file, 0, SEEK_END);
    long long fileSize = ftell(file);
    rewind(file);
    unsigned int nNodes = fileSize / sizeof(ArrayCell);

    ArrayCell *nodeArray = (ArrayCell *)malloc(nNodes * sizeof(ArrayCell));
    if (nodeArray == NULL)
    {
        printf("Cannot allocate memory for node array\n");
        exit(EXIT_FAILURE);
    }

    fread(nodeArray, sizeof(ArrayCell), nNodes, file);

    fclose(file);

    StaticTree st = {nodeArray, nNodes};
    return st;
}

// Fonction pour lire les mots et leurs offset du fichier
CSTree createTree(FILE *f, float *M, char *vocab, long long size, long long words)
{
    CSTree t = newCSTree('\0', NULL, NULL);
    long long a, b;
    for (b = 0; b < words; b++)
    {
        long offst = ftell(f);
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

        addTree(t, &vocab[b * max_w], offst);
    }
    return t;
}

char *randomWord(StaticTree *st)
{
    char *word = malloc(30 * sizeof(char));
    memset(word, '\0', 30);
    randomLetter(st, 1, word);
    //printf("mot random: %s \n", word);
    return word;
}

char *randomLetter(StaticTree *st, int firstChild, char *word)
{
    int r = rand() % (st->nodeArray[firstChild].nSiblings + 1) + firstChild;
    if (st->nodeArray[r].firstChild == NONE)
    {

        strncat(word, &st->nodeArray[r].elem, 1);
        return word;
    }
    else
    {
        strncat(word, &st->nodeArray[r].elem, 1);
        return randomLetter(st, st->nodeArray[r].firstChild, word);
    }
}
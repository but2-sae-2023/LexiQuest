#ifndef DICT_LOOKUP_H
#define DICT_LOOKUP_H

#define NONE -1

typedef char Element;

// Structure pour représenter une cellule du tableau d'arbre statique
typedef struct
{
    char elem;
    int firstChild;
    int nSiblings;
    int offset; // Ajout de l'offset pour représenter les feuilles
} ArrayCell;

// Structure pour représenter l'arbre statique
typedef struct
{
    ArrayCell *nodeArray;
    unsigned int nNodes;
} StaticTree;

// Constante pour firstChild si aucun enfant
#define NONE -1

// Fonction pour libérer la mémoire utilisée par le StaticTree
void freeStaticTree(StaticTree *tree)
{
    free(tree->nodeArray);
}

// Fonctions d'impression d'un arbre statique:
//  * version "jolie" avec un noeud par ligne, chaque noeud indenté sous son parent
void printNicePrefixStaticTree_aux(StaticTree *st, int index, int depth)
{
    if (index == NONE)
        return;
    for (int i = 0; i < depth; i++)
        printf("    ");
    printf("%c\n", st->nodeArray[index].elem);
    printNicePrefixStaticTree_aux(st, st->nodeArray[index].firstChild, depth + 1);
    if (st->nodeArray[index].nSiblings > 0)
        printNicePrefixStaticTree_aux(st, index + 1, depth);
}

void printNicePrefixStaticTree(StaticTree *st)
{
    if (st->nNodes > 0)
        printNicePrefixStaticTree_aux(st, 0, 0);
}

// Fonction pour initialiser l'arbre statique depuis un fichier
void initializeStaticTreeFromFile(StaticTree *tree, const char *filename)
{
    //printf("Ouverture du fichier %s...\n", filename);
    FILE *file = fopen(filename, "rb");

    if (file == NULL)
    {
        perror("Error opening file");
        exit(EXIT_FAILURE);
    }

    //printf("Lecture de l'arbre statique depuis le fichier...\n");

    // Lire le nombre total de nœuds dans le fichier
    fread(&(tree->nNodes), sizeof(unsigned int), 1, file);

    // Allouer de la mémoire pour le tableau de nœuds
    //printf("Allocation de la mémoire pour le tableau de nœuds...\n");
    tree->nodeArray = (ArrayCell *)malloc(tree->nNodes * sizeof(ArrayCell));

    // Lire le tableau de nœuds depuis le fichier
    //printf("Lecture du tableau de nœuds depuis le fichier...\n");
    fread(tree->nodeArray, sizeof(ArrayCell), tree->nNodes, file);

    //printf("Fermeture du fichier %s...\n", filename);
    fclose(file);
}


int siblingDichotomyLookupStatic(StaticTree* st, Element e, int from, int len){
    if (len == NONE) len = st->nodeArray[from].nSiblings+1;
    if (len == 0) return NONE;
    int mid = from+len/2;
    //printf("mid = %d\n", mid);
    //printf("from = %d\n", from);
    //printf("len = %d\n", len);
    //printf("char actuel = %c\n", st->nodeArray[mid].elem);
    if (st->nodeArray[mid].elem == e) return mid;
    if (st->nodeArray[mid].elem > e) return siblingDichotomyLookupStatic(st, e, from, mid-from);
    if (st->nodeArray[mid].elem < e && (from+len) >= (mid+1)) return siblingDichotomyLookupStatic(st, e, mid+1, (from+len)-(mid+1));
    return NONE;
}

int siblingLookupStatic(StaticTree* st, Element e, int from, int len){
    if (len == NONE) len = st->nodeArray[from].nSiblings+1;
    //printf("from = %d\n", from);
    //printf("len = %d\n", len);
    //printf("len+from = %d\n", len+from);
    for (int i = from; i < from+len; i++) {
        //printf("char actuel = %c\n", st->nodeArray[i].elem);
        if (st->nodeArray[i].elem == e) return i;
    }
    return NONE;
}

int searchWordInTree(StaticTree* st, const char* word)
{
    int currentNode = 0; // Commence à la racine de l'arbre

    for (int i = 0; word[i] != '\0'; i++)
    {
        char currentChar = word[i];
        //printf("Vérification du caractère '%c'...\n", currentChar);

        // Recherche du caractère courant dans les enfants du nœud actuel
        int childIndex = siblingLookupStatic(st, currentChar, st->nodeArray[currentNode].firstChild, NONE);

        if (childIndex == NONE)
        {
            // Le caractère courant n'a pas été trouvé dans l'arbre
            return -1;
        }

        // Déplace le nœud actuel vers le nœud enfant correspondant
        currentNode = childIndex;
    }

    // Vérifie si le dernier nœud atteint correspond à la fin d'un mot
    //printf("Current node = %d\n", currentNode);
    //printf("char actuel = %c\n", st->nodeArray[currentNode].elem);
    //printf("nSiblings = %d\n", st->nodeArray[currentNode].nSiblings);
    //printf("firstChild = %d\n", st->nodeArray[currentNode].firstChild);
    //printf("Offset = %d\n", st->nodeArray[currentNode].offset);
    if (st->nodeArray[currentNode].offset != NONE)
    {
        // Le mot complet a été trouvé dans l'arbre
        return st->nodeArray[currentNode].offset;
    }

    // Le dernier nœud atteint n'est pas la fin d'un mot
    return -1;
}

// Fonction pour lancer la lecture d'un mot dans l'arbre statique
int getOffset(const char *filename, const char *word)
{
    StaticTree myTree;
    //printf("Initialisation de l'arbre statique...\n");
    initializeStaticTreeFromFile(&myTree, filename);
    //printf("Arbre statique initialisé.\n");

    //printf("Recherche du mot '%s' dans l'arbre statique...\n", word);

    int offset = searchWordInTree(&myTree, word);
    //printf("Recherche du mot '%s' dans l'arbre statique...\n", word);

    freeStaticTree(&myTree);

    //printf("Le mot '%s' a un offset de %d dans le dictionnaire.\n", word, offset);

    return offset;
}

#endif // DICT_LOOKUP_H
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <math.h>

#define MAX_STRING 100

const long long max_size = 2000; // max length of strings
const long long N = 40;          // number of closest words that will be shown
const long long max_w = 50;

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

// Structure pour représenter un noeud de l'arbre
typedef struct node
{
    char label;
    struct node *firstChild;
    struct node *nextSibling;
    int offset; // Offset associé à la feuille (pour les mots complets)
} Node;

typedef Node *CSTree;

// Prototype de la fonction createNode
Node *createNode(char label);

void printPrefix(CSTree t)
{
    if (t == NULL)
        return;
    printf("%c ", t->label);
    printPrefix(t->firstChild);
    printPrefix(t->nextSibling);
}

Node *createNode(char label)
{
    Node *newNode = (Node *)malloc(sizeof(Node));
    newNode->label = label;
    newNode->firstChild = NULL;
    newNode->nextSibling = NULL;
    newNode->offset = -1; // Initialement, pas d'offset assigné
    return newNode;
}

// Fonction récursive pour exporter le CSTree en StaticTree
void exportCSTreeToStaticTree(Node *node, ArrayCell *array, unsigned int *currentIndex)
{
    if (node != NULL)
    {
        // Si le noeud est une feuille, copie l'offset dans le tableau
        if (node->label == '\0')
        {
            printf("Feuille trouvée\n");
            printf("offset = %d\n", node->offset);
            printf("currentIndex = %d\n", *currentIndex);
            printf("nSiblings = %d\n", node->offset);
            array[*currentIndex].elem = node->label;
            array[*currentIndex].firstChild = NONE; // Aucun enfant pour une feuille
            array[*currentIndex].nSiblings = node->offset;
            array[*currentIndex].offset = node->offset; // Ajout de l'offset
            (*currentIndex)++;
        }
        else
        {
            // Si le noeud n'est pas une feuille, continue la conversion récursivement
            exportCSTreeToStaticTree(node->firstChild, array, currentIndex);
            exportCSTreeToStaticTree(node->nextSibling, array, currentIndex);
        }
    }
}

// Q4 Compte le nombre de noeuds dans l’arbre t.
int size(CSTree t)
{
    if (t == NULL)
    {
        return 0;
    }

    return 1 + size(t->firstChild) + size(t->nextSibling);
}

// Q5 Compte le nombre d’enfants du nœud t.
int siblingsCounter(CSTree t)
{
    if (t == NULL)
        return 0;
    return 1 + siblingsCounter(t->nextSibling);
}

// Q5 Compte le nombre d’enfants du nœud t.
int nChildren(CSTree t)
{
    return siblingsCounter(t->firstChild);
}

// Q6 Fonction récursive auxiliaire pour exportStaticTree
//  paramètres:
//   *st : un static tree partiellement rempli
//   t  : un noeud du CSTree original
//   index_for_t : la position à laquelle t doit être enregistré
//   nSiblings : le nombre de frères du noeud courant
//   *reserved_cells : le nombre de cellules "réservées" à cet état du parcours (passée par pointeur)
//   NB : au moment d'entrer dans la fonction, les cellules pour ce noeud et ses frères sont déjà réservervées, mais pas pour leurs enfants
void fill_array_cells(StaticTree *st, CSTree t, int index_for_t, int nSiblings, int *reserved_cells)
{
    //printf("inserting node %c at position %d (%d siblings, %d reserved cells)\n", t->label, index_for_t, nSiblings, *reserved_cells);
    st->nodeArray[index_for_t].elem = t->label;
    st->nodeArray[index_for_t].firstChild = NONE;
    st->nodeArray[index_for_t].nSiblings = nSiblings;
    st->nodeArray[index_for_t].offset = t->offset; // Ajout de l'offset

    if (t->firstChild != NULL)
    {
        int index_for_firstChild = *reserved_cells;
        st->nodeArray[index_for_t].firstChild = index_for_firstChild;
        int children_of_t = nChildren(t);
        *reserved_cells += children_of_t;

        fill_array_cells(st, t->firstChild, index_for_firstChild, children_of_t - 1, reserved_cells);
    }
    if (t->nextSibling != NULL)
    {
        // assert(nSiblings > 0);
        fill_array_cells(st, t->nextSibling, index_for_t + 1, nSiblings - 1, reserved_cells);
    }
}
// Crée un arbre statique avec le même contenu que t.
StaticTree exportStaticTree(CSTree t)
{
    StaticTree st = {NULL, 0};
    int reserved_cells = 0;
    st.nNodes = size(t);
    st.nodeArray = malloc(st.nNodes * sizeof(ArrayCell));
    reserved_cells = siblingsCounter(t);
    fill_array_cells(&st, t, 0, reserved_cells - 1, &reserved_cells);
    if (reserved_cells != st.nNodes && t != NULL)
    {
        printf("erreur lors de la création de l'arbre statique, taille finale incorrecte\n");
        exit(EXIT_FAILURE);
    }
    return st;
}

// Fonction pour libérer la mémoire utilisée par le CSTree
void freeCSTree(CSTree tree)
{
    if (tree != NULL)
    {
        freeCSTree(tree->firstChild);
        freeCSTree(tree->nextSibling);
        free(tree);
    }
}

// Fonction pour exporter le StaticTree dans un fichier .lex
void exportStaticTreeToFile(const char *filename, StaticTree tree)
{
    FILE *file = fopen(filename, "wb");  // Utilisez "w" au lieu de "wb"

    if (file == NULL)
    {
        perror("Error opening file");
        exit(EXIT_FAILURE);
    }

    // Écrit le nombre total de nœuds dans le fichier
    fwrite(&(tree.nNodes), sizeof(unsigned int), 1, file);

    // Écrit le tableau de nœuds dans le fichier
    fwrite(tree.nodeArray, sizeof(ArrayCell), tree.nNodes, file);

    // Ferme le fichier
    fclose(file);
}

long getNWords() {
    FILE *file = fopen("output.bin", "rb");
    if (file == NULL)
    {
        perror("Error opening file");
        exit(EXIT_FAILURE);
    }
    fseek(file, 0, SEEK_SET);
    long nWords;
    char *c;
    fscanf(file, "%ld", &nWords);
    //printf("nWords = %ld\n", nWords);
    fclose(file);
    return nWords;

}

// Fonction pour insérer un mot avec un offset dans le CSTree
void insertWordWithOffset(CSTree *tree, const char *word, int offset)
{
    if (*tree == NULL)
    {
        *tree = createNode('\0'); // Crée un nouveau noeud pour la racine
    }

    Node *currentNode = *tree;

    for (size_t i = 0; i < strlen(word); i++)
    {
        Node *child = currentNode->firstChild;

        // Parcours les enfants pour trouver le noeud avec la lettre actuelle
        while (child != NULL && child->label != word[i])
        {
            child = child->nextSibling;
        }

        // Si le noeud avec la lettre actuelle n'existe pas, crée un nouveau noeud
        if (child == NULL)
        {
            child = createNode(word[i]);
            child->nextSibling = currentNode->firstChild;
            currentNode->firstChild = child;
        }

        currentNode = child;
        //printf("child = %c\n", child->label);
    }

    // Attribue l'offset à la feuille correspondante
    currentNode->offset = offset;
    //printf("Label Current Node = %c\n", currentNode->label);
    //printf("offset = %d\n", currentNode->offset);
}


// Fonction qui insère tous les mots du fichier dans le CSTree avec leur offset
void insertAllWordOffset(const char *file_name, CSTree *tree)
{
    //printf("Ouverture du fichier %s...\n", file_name);
    FILE *f;
    char st1[max_size];
    float *M;
    char *vocab;
    long long words_total, size, len;

    f = fopen(file_name, "rb");
    if (f == NULL)
    {
        printf("Input file not found\n");
        exit(EXIT_FAILURE);
    }
    fscanf(f, "%lld", &words_total);
    fscanf(f, "%lld", &size);
    long long offsets[words_total];
    vocab = (char *)malloc((long long)words_total * max_w * sizeof(char));
    M = (float *)malloc((long long)words_total * (long long)size * sizeof(float));
    if (M == NULL)
    {
        printf("Cannot allocate memory: %lld MB    %lld  %lld\n", (long long)words_total * size * sizeof(float) / 1048576, words_total, size);
        exit(EXIT_FAILURE);
    }
    for (long long b = 0; b < words_total; b++)
    {
        long long a = 0;
        offsets[b] = ftell(f);
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
        len = 0;
        for (a = 0; a < size; a++)
            len += M[a + b * size] * M[a + b * size];
        len = sqrt(len);
        for (a = 0; a < size; a++)
            M[a + b * size] /= len;
    }
    fclose(f);

    for (long long a = 0; a < words_total; a++)
    {
        //printf("Word %lld: %s\n", a, &vocab[a * max_w]);
        insertWordWithOffset(tree, &vocab[a * max_w], offsets[a]);
    }

    free(vocab);
    free(M);
}

int main(int argc, char *argv[])
{
    if (argc != 2)
    {
        printf("Usage: %s <dictionary>\n", argv[0]);
        exit(EXIT_FAILURE);
    }
    CSTree myTree = NULL;

    const char *filename = argv[1];

    //printf("Insertion des mots depuis le fichier %s...\n", filename);

    insertAllWordOffset(filename, &myTree);

    // Exporte le CSTree en StaticTree
    StaticTree staticTree = exportStaticTree(myTree);

    // Vérifie la conversion de CSTree vers StaticTree
    //printf("Conversion de CSTree vers StaticTree réussie.\n");

    // Exporte le StaticTree dans un fichier .lex
    exportStaticTreeToFile("output.lex", staticTree);

    //printf("Le StaticTree a été écrit dans le fichier output.lex.\n");

    // Libère la mémoire utilisée par les arbres
    freeCSTree(myTree);
    return 0;
}
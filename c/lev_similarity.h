#ifndef LEV_SIMILARITY_H
#define LEV_SIMILARITY_H


int max(int a, int b) {
    return a > b ? a : b;
}

int minimum(int a, int b, int c)
{
    int min = a;
    if (b < min)
        min = b;
    if (c < min)
        min = c;
    return min;
}

double levenshtein_similarity(const char *word1, const char *word2)
{
    int len1 = strlen(word1);
    int len2 = strlen(word2);

    // Créer une matrice pour stocker les distances intermédiaires
    int matrix[len1 + 1][len2 + 1];

    // Initialiser la première ligne et la première colonne de la matrice
    for (int i = 0; i <= len1; i++)
    {
        matrix[i][0] = i;
    }
    for (int j = 0; j <= len2; j++)
    {
        matrix[0][j] = j;
    }

    // Remplir le reste de la matrice
    for (int i = 1; i <= len1; i++)
    {
        for (int j = 1; j <= len2; j++)
        {
            int cost = (word1[i - 1] == word2[j - 1]) ? 0 : 1;
            matrix[i][j] = minimum(
                matrix[i - 1][j] + 1,       // Suppression
                matrix[i][j - 1] + 1,       // Insertion
                matrix[i - 1][j - 1] + cost // Substitution
            );
        }
    }

    // La valeur dans le coin inférieur droit de la matrice est la distance de Levenshtein
    double distance = matrix[len1][len2];
    distance /= max(len1, len2);
    distance = 1 - distance;
    return 100*distance;
}

#endif
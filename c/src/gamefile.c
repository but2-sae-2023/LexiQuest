#include "../include/gamefile.h"
#include "../include/vector.h"
#include "../include/levenshtein.h"
#include "../include/constante.h"
#include "../include/tree.h"

// Choisir deux mots au hasard parmi ceux entrés en paramètre
void StartWords(char *mot1, char *mot2, int argc, char **argv)
{
    srand(time(NULL));
    int random1 = rand() % (argc - 3) + 3;
    int random2 = rand() % (argc - 3) + 3;
    while (random1 == random2)
    {
        random2 = rand() % (argc - 3) + 3;
    }
    strcpy(mot1, argv[random1]);
    strcpy(mot2, argv[random2]);
    // printf("Mot 1 : %s\n", mot1);
    // printf("Mot 2 : %s\n", mot2);
}

// Crée un fichier de partie gameFile.csv qui contients 2 mots de départs, leurs offsets et la distance entre les 2 mots
void createGameFile(const char *filename, char *word1, char *word2, long offset1, long offset2, double score)
{
    FILE *file = fopen(filename, "w+");
    if (file == NULL)
    {
        printf("Unable to open file %s\n", filename);
        return;
    }

    fprintf(file, "2,4\n");
    fprintf(file, "%s,%s\n", word1, word2);
    fprintf(file, "%s,depart,%ld\n", word1, offset1);
    fprintf(file, "%s,fin,%ld\n", word2, offset2);
    fprintf(file, "%s,%s,%.2f\n", word1, word2, score);

    fclose(file);
}
int getNbCouples(int nMots)
{
    /*
    Pour 2 mots : 1 couple
    Pour 3 mots : 3 couples
    Pour 4 mots : 6 couples
    Pour 5 mots : 10 couples
    */
    return nMots * (nMots - 1) / 2;
}

char *int_to_string(int number)
{
    char str[10];
    snprintf(str, sizeof(str), "%d", number);
    return strdup(str);
}

void add_word(const char *filename, char *dictionary, char *newWord, long offset, char *player)
{
    FILE *file = fopen(filename, "r");
    if (file == NULL)
    {
        printf("Unable to open file %s\n", filename);
        perror("Erreur lors de l'ouverture du fichier de partie :");
        exit(EXIT_FAILURE);
    }

    // Ouverture d'un nouveau fichier en mode écriture
    FILE *newFile = fopen("temp_file.txt", "w");
    if (newFile == NULL)
    {
        printf("Unable to create new file\n");
        perror("Erreur lors de la création du nouveau fichier :");
        exit(EXIT_FAILURE);
    }

    int nMots, ligneCouples;
    fscanf(file, "%d,%d\n", &nMots, &ligneCouples);
    char *listeMots[nMots];
    char buffer[1024];

    // Écriture des premières lignes dans le nouveau fichier
    fprintf(newFile, "%d,%d\n", nMots + 1, ligneCouples + nMots);
    fgets(buffer, sizeof(buffer), file);
    fprintf(newFile, "%s", buffer);
    printf("buffer : %s\n", buffer);

    // Copie des lignes existantes du fichier original dans le nouveau fichier
    
    for (int i = 0; i < nMots; i++)
    {
        fgets(buffer, sizeof(buffer), file);
        fprintf(newFile, "%s", buffer);
        char *token = strtok(buffer, ",");
        char *mot = token;
        if (strcmp(mot, newWord) == 0)
        {
            printf("Le mot %s est déjà présent dans le fichier de partie\n", newWord);
            fclose(file);
            fclose(newFile);
            remove("temp_file.txt");
            exit(EXIT_FAILURE);
        }
        else
        {
            listeMots[i] = strdup(mot);
        }
    }

    fprintf(newFile, "%s,%s,%ld\n", newWord, player, offset);

    while (fgets(buffer, sizeof(buffer), file) != NULL)
    {
        fprintf(newFile, "%s", buffer);
    }


    for (int j = 0; j < nMots; j++)
    {
        //printf("mot : %s\n", listeMots[j]);
        double semanticScore = calculSem(dictionary, newWord, listeMots[j]);
        double levenshteinScore = levenshtein(newWord, listeMots[j]);
        double score = (semanticScore * 5 + levenshteinScore) / 6;
        fprintf(newFile, "%s,%s,%f\n", newWord, listeMots[j], score);
    }
    fclose(file);
    fclose(newFile);
    remove(filename);
    rename("temp_file.txt", filename);
}

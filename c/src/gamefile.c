#include "../include/gamefile.h"
#include "../include/vector.h"
#include "../include/levenshtein.h"
#include "../include/constante.h"
#include "../include/tree.h"

#include <stdio.h>
#include <sys/stat.h>
#include <unistd.h>
#include <pwd.h>

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
void createGameFile(const char *filename, char *word1, char *word2, long offset1, long offset2, double sem_similarity, double lev_similarity)
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
    fprintf(file, "%s,%s,%.2f,%.2f\n", word1, word2, lev_similarity, sem_similarity);

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
    FILE *file = fopen(filename, "r+");
    if (file == NULL)
    {
        printf("Unable to open file %s\n", filename);
        perror("Erreur lors de l'ouverture du fichier de partie :");
        exit(EXIT_FAILURE);
    }

    int nMots, ligneCouples;
    // printf("Lecture de la première ligne\n");
    //  Lire les valeurs de la première ligne
    fscanf(file, "%d,%d\n", &nMots, &ligneCouples);
    // printf("nMots = %d, ligneCouples = %d\n", nMots, ligneCouples);

    // Création d'une liste de mots
    char *listeMots[nMots];

    // Création d'une liste pour stocker les mots qu'on doit insérer
    char *listeMotsAInserer[nMots];

    // Création d'une liste pour stocker les anciens couples du fichier
    char *listeCouples[getNbCouples(nMots)];

    // Création d'une liste de couples avec les scores
    char *listeNewCouples[nMots];

    char mot1[max_w];
    char *mot2;
    char *dup;

    // printf("Lecture de la deuxième ligne\n");
    //  Lire la deuxième ligne (les mots existants)
    fgets(mot1, max_w, file);
    // printf("%s\n", mot1);
    dup = strdup(mot1);
    mot2 = strtok(dup, ",");
    int i = 0;

    // Remplissage de la liste de mots
    listeMots[i] = strdup(mot2);
    // printf("%s\n", listeMots[i]);

    i = 0;
    // Remplissage de la liste de mots
    while (mot2 != NULL)
    {
        listeMots[i] = strdup(mot2);
        i++;
        mot2 = strtok(NULL, ",");
    }

    // Récupération des anciens mots insérés
    i = 0;
    // printf("Affichage de la liste de mots à insérer\n");
    while (i < nMots)
    {
        fgets(mot1, max_w, file);
        listeMotsAInserer[i] = strdup(mot1);
        i++;
    }

    // Récupération des anciens couples
    i = 0;
    // printf("Affichage de la liste de couples\n");
    int n = getNbCouples(nMots);
    // printf("nbCouples = %d\n", n);
    while (i < n)
    {
        fgets(mot1, max_w, file);
        listeCouples[i] = strdup(mot1);
        i++;
    }

    // printf("Affichage de la liste de mots\n");
    for (int j = 0; j < nMots; j++)
    {
        if (strchr(listeMots[j], '\n') != NULL)
        {
            listeMots[j][strcspn(listeMots[j], "\n")] = '\0';
        }
        if (strcmp(listeMots[j], newWord) == 0)
        {
            printf("Le mot %s existe déjà dans le fichier de partie\n", newWord);
            exit(EXIT_FAILURE);
        }
    }

    // printf("Affichage de la liste de mots à insérer\n");
    /*for (int j = 0; j < nMots; j++) {
        printf("%s", listeMotsAInserer[j]);
    }*/

    fseek(file, 0, SEEK_SET);
    // printf("Je suis là 1\n");

    // On met à jour le nombre de mots
    ligneCouples += nMots;
    nMots++;
    fprintf(file, "%d,%d\n", nMots, ligneCouples);
    // printf("Je suis là 2\n");

    nMots--;

    // On réécrit les mots existants
    for (int j = 0; j < nMots; j++)
    {
        // printf("listeMots[%d] = %s\n", j, listeMots[j]);
        fprintf(file, "%s,", listeMots[j]);
        // printf("Je suis là 3\n");
    }
    // On écrit le nouveau mot sur la 2e ligne
    fprintf(file, "%s\n", newWord);
    // printf("Je suis là 4\n");

    // On réécrit les mots à insérer
    // printf("Affichage de la liste de mots à insérer\n");
    for (int j = 0; j < nMots; j++)
    {
        // printf("listeMotsAInserer[%d] = %s\n", j, listeMotsAInserer[j]);
        fprintf(file, "%s", listeMotsAInserer[j]);
        // printf("Je suis là 5\n");
    }
    char *newWordWithOffset = malloc(sizeof(newWord) + sizeof("joueur1") + 10);
    sprintf(newWordWithOffset, "%s,%s,%ld", newWord, player, offset); // offset

    // On écrit le nouveau mot avec son offset
    fprintf(file, "%s\n", newWordWithOffset);
    // printf("Je suis là 6\n");

    // On réécrit les anciens couples
    // printf("Nombre d'anciens couples à insérer : %d\n", n);
    for (int j = 0; j < n; j++)
    {
        // printf("listeCouples[%d] = %s\n", j, listeCouples[j]);
        // printf("Position actuelle du curseur : %ld\n", ftell(file));
        fprintf(file, "%s", listeCouples[j]);
        // printf("Je suis là 7\n");
    }

    // On met le curseur à la fin du fichier
    fseek(file, 0, SEEK_END);

    // Affichage de la liste de couples
    // printf("Affichage de la liste de couples\n");
    for (int j = 0; j < nMots; j++)
    {
        // printf("Dans la boucle for : %s\n", listeMots[j]);
        double semanticScore = calculSem(dictionary, newWord, listeMots[j]);
        double levenshteinScore = levenshtein(newWord, listeMots[j]);
        listeNewCouples[j] = malloc(sizeof(newWord) + sizeof(listeMots[j]) + sizeof(levenshteinScore) + sizeof(semanticScore));
        sprintf(listeNewCouples[j], "%s,%s,%lf,%lf", newWord, listeMots[j], levenshteinScore, semanticScore);
        fprintf(file, "%s\n", listeNewCouples[j]);
    }

    fclose(file);

    // Libération de la mémoire
    for (int j = 0; j <= nMots +1; j++)
    {
        free(listeMots[j]);
        free(listeMotsAInserer[j]);
        free(listeCouples[j]);
        free(listeNewCouples[j]);
    }
    free(dup);
    free(newWordWithOffset);
    
}

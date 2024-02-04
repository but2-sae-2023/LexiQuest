#include <stdio.h>
#include <string.h>
#include <stdlib.h>
#include "lev_similarity.h"
#include "sem_similarity.h"
#include "dict_lookup.h"

#define MAX_WORD_LENGTH 100
#define MAX_LINE_COUNT 250

int getNbCouples(int nMots) {
    /*
    Pour 2 mots : 1 couple
    Pour 3 mots : 3 couples
    Pour 4 mots : 6 couples
    Pour 5 mots : 10 couples
    */
    return nMots * (nMots - 1) / 2;
}

char* int_to_string(int number) {
    char str[10];
    snprintf(str, sizeof(str), "%d", number);
    return strdup(str);
}

void add_word(const char *filename, const char *dictionary, char *newWord) {
    FILE *file = fopen(filename, "r+");
    if (file == NULL) {
        perror("Erreur lors de l'ouverture du fichier de partie");
        exit(EXIT_FAILURE);
    }

    int nMots, ligneCouples;
    //printf("Lecture de la première ligne\n");
    // Lire les valeurs de la première ligne
    fscanf(file, "%d,%d\n", &nMots, &ligneCouples);
    //printf("nMots = %d, ligneCouples = %d\n", nMots, ligneCouples);

    // Création d'une liste de mots
    char *listeMots[nMots];

    // Création d'une liste pour stocker les mots qu'on doit insérer
    char *listeMotsAInserer[nMots];

    // Création d'une liste pour stocker les anciens couples du fichier
    char *listeCouples[getNbCouples(nMots)];

    // Création d'une liste de couples avec les scores
    char *listeNewCouples[nMots];

    char mot1[MAX_WORD_LENGTH];
    char *mot2;
    char *dup;

    //printf("Lecture de la deuxième ligne\n");
    // Lire la deuxième ligne (les mots existants)
    fgets(mot1, MAX_WORD_LENGTH, file);
    //printf("%s\n", mot1);
    dup = strdup(mot1);
    mot2 = strtok(dup, ",");
    int i = 0;

    // Remplissage de la liste de mots
    listeMots[i] = strdup(mot2);
    //printf("%s\n", listeMots[i]);

    i = 0;
    // Remplissage de la liste de mots
    while (mot2 != NULL) {
        listeMots[i] = strdup(mot2);
        i++;
        mot2 = strtok(NULL, ",");
    }


    // Récupération des anciens mots insérés
    i = 0;
    //printf("Affichage de la liste de mots à insérer\n");
    while (i < nMots) {
        fgets(mot1, MAX_WORD_LENGTH, file);
        listeMotsAInserer[i] = strdup(mot1);
        i++;
    }

    // Récupération des anciens couples
    i = 0;
    //printf("Affichage de la liste de couples\n");
    int n = getNbCouples(nMots);
    //printf("nbCouples = %d\n", n);
    while (i < n) {
        fgets(mot1, MAX_WORD_LENGTH, file);
        listeCouples[i] = strdup(mot1);
        i++;
    }


    //printf("Affichage de la liste de mots\n");
    for (int j = 0; j < nMots; j++) {
        if (strchr(listeMots[j], '\n') != NULL) {
            listeMots[j][strcspn(listeMots[j], "\n")] = '\0';
        }
        if (strcmp(listeMots[j], newWord) == 0) {
            printf("Le mot %s existe déjà dans le fichier de partie\n", newWord);
            exit(EXIT_FAILURE);
        }
    }

    //printf("Affichage de la liste de mots à insérer\n");
    /*for (int j = 0; j < nMots; j++) {
        printf("%s", listeMotsAInserer[j]);
    }*/
    
    fseek(file, 0, SEEK_SET);
    //printf("Je suis là 1\n");

    // On met à jour le nombre de mots
    ligneCouples += nMots;
    nMots++;
    fprintf(file, "%d,%d\n", nMots, ligneCouples);
    //printf("Je suis là 2\n");

    nMots--;
    
    // On réécrit les mots existants
    for (int j = 0; j < nMots; j++) {
        //printf("listeMots[%d] = %s\n", j, listeMots[j]);
        fprintf(file, "%s,", listeMots[j]);
        //printf("Je suis là 3\n");
    }
    // On écrit le nouveau mot sur la 2e ligne
    fprintf(file, "%s\n", newWord);
    //printf("Je suis là 4\n");

    // On réécrit les mots à insérer
    //printf("Affichage de la liste de mots à insérer\n");
    for (int j = 0; j < nMots; j++) {
        //printf("listeMotsAInserer[%d] = %s\n", j, listeMotsAInserer[j]);
        fprintf(file, "%s", listeMotsAInserer[j]);
        //printf("Je suis là 5\n");
    }
    char *newWordWithOffset = malloc(sizeof(newWord) + sizeof("joueur1") + 10);
    sprintf(newWordWithOffset, "%s,%s,%d", newWord, "joueur1", getOffset(dictionary, newWord));

    // On écrit le nouveau mot avec son offset
    fprintf(file, "%s\n", newWordWithOffset);
    //printf("Je suis là 6\n");

    // On réécrit les anciens couples
    //printf("Nombre d'anciens couples à insérer : %d\n", n);
    for (int j = 0; j < n; j++) {
        //printf("listeCouples[%d] = %s\n", j, listeCouples[j]);
        //printf("Position actuelle du curseur : %ld\n", ftell(file));
        fprintf(file, "%s", listeCouples[j]);
        //printf("Je suis là 7\n");
    }

    // On met le curseur à la fin du fichier
    fseek(file, 0, SEEK_END);

    // Affichage de la liste de couples
    //printf("Affichage de la liste de couples\n");
    for (int j = 0; j < nMots; j++) {
        //printf("Dans la boucle for : %s\n", listeMots[j]);
        double semanticScore = sem_similarity("output.bin", newWord, listeMots[j]);
        double levenshteinScore = levenshtein_similarity(newWord, listeMots[j]);
        listeNewCouples[j] = malloc(sizeof(newWord) + sizeof(listeMots[j]) + sizeof(levenshteinScore) + sizeof(semanticScore));
        sprintf(listeNewCouples[j], "%s,%s,%lf,%lf", newWord, listeMots[j], levenshteinScore, semanticScore);
        fprintf(file, "%s\n", listeNewCouples[j]);
    }

    fclose(file);

    // Libération de la mémoire
    for (int j = 0; j < nMots; j++) {
        free(listeMots[j]);
        free(listeMotsAInserer[j]);
        free(listeCouples[j]);
        free(listeNewCouples[j]);
    }
}

int main(int argc, char **argv) {
    if (argc != 3) {
        printf("Usage: %s <dictionary> <word>\n", argv[0]);
        exit(EXIT_FAILURE);
    }
    const char *dictionary = argv[1];
    char *newWord = argv[2];
    add_word("gameFile.txt", dictionary, newWord);

    return 0;
}
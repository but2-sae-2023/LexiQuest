#include "../include/constante.h"
#include "../include/vector.h"
#include "../include/tree.h"
#include "../include/offset.h"

//Recuperer les vecteur d'un mots (il mets les vecteurs dans le tableau M )
void getVec(FILE *f, int offset, int size, float *M)
{
    //printf("Offset: %ld\n", offset);  // Affiche l'offset

    char mot[10];
    float vec[max_size], len;
    int a;
    char c[50];
    fseek(f, offset, SEEK_SET);

    int i=0;
    while (1)
    { 
       c[i]= fgetc(f); 
      if (feof(f) || (c[i]== ' ')) break; 
      if ( (c[i] != '\n')) i++; 
    }

    for (a = 0; a < max_size; a++){ 
        vec[a] = 0;   
    }
    for (int i = 0; i < max_size; i++) {
        fread(&vec[i], sizeof(float), 1, f);
        M[i] = vec[i];
    }
}


// Calculer la norme d'un vecteur
double norm(float *vec, int size) {
    double sum = 0;
    for (int i = 0; i < size; i++) {
        sum += vec[i] * vec[i];
    }
    return sqrt(sum);
}

// Calculer le produit scalaire de deux vecteurs
double dot_product(float *vec1, float *vec2, int size) {
    double sum = 0;
    for (int i = 0; i < size; i++) {
        sum += vec1[i] * vec2[i];
    }
    return sum;
}

// Calculer le cosinus entre deux vecteurs
double semantic(float *vec1, float *vec2, int size) {
    double norm1 = norm(vec1, size);
    double norm2 = norm(vec2, size);

    double dot = dot_product(vec1, vec2, size);

    double cos_distance = dot / (norm1 * norm2);
    return (cos_distance + 1) * 50;  // Normalisation entre 0 et 100
}

double calculSem(char *file_name,char *word1, char *word2){
     FILE *f = fopen(file_name, "rbc");
    if (f == NULL)
    {
        perror("Error opening file");
        return EXIT_FAILURE;
    }


    long offset1 = fileGetOffset(word1);
    long offset2 = fileGetOffset(word2);

    float vec1[max_size], vec2[max_size];

    getVec(f, offset1, max_size, vec1);
    getVec(f, offset2, max_size, vec2);

    double similarity = semantic(vec1, vec2, max_size);
    fclose(f);
    return similarity;
}

double calculSemFromOffst(char *file_name, int offset1, int offset2){
   
    FILE *f = fopen(file_name, "rbc");
    if (f == NULL)
    {
        perror("Error opening file");
        return EXIT_FAILURE;
    }
    float vec1[max_size], vec2[max_size];

    getVec(f, offset1, max_size, vec1);
    getVec(f, offset2, max_size, vec2);
    double similarity = semantic(vec1, vec2, max_size);
    fclose(f);
    return similarity;

}


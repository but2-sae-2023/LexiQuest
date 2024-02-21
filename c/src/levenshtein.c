#include "../include/constante.h"
#include "../include/levenshtein.h"

//minimum de deux entiers
int min(int a, int b) {
    return a < b ? a : b;
}


int max(int a, int b) {
    return a > b ? a : b;
}


//initialiser un tableau pour des chaînes d'une taille donnée
LevArray init(int lenS, int lenT) {
    LevArray a;
    //on stocke les dimensions
    a.lenS = lenS;
    a.lenT = lenT;
    //allocation d'un tableau (1D) de lenS*lenT entiers
    a.tab = malloc(lenS * lenT * sizeof(int));
    //on vérifie que l'allocation s'est bien passée
    assert(a.tab != NULL); 
    return a;

}


//set: insérer une valeur dans le tableau
void set(LevArray a, int indexS, int indexT, int val) {
    //vérification des indices
    assert(indexS >= 0 && indexS < a.lenS && indexT >= 0 && indexT < a.lenT);
    assert(a.tab!=NULL); 
    a.tab[indexT * a.lenS + indexS] = val;
}


//renvoie la valeur correspondant à des indices donnés
//   i+1 pour les requêtes du type get(a, -1, i) ou get (a, i, -1)
int get(LevArray a, int indexS, int indexT) {

    if (indexS == -1 && indexT == -1) {
        return 0;
    } else if (indexS == -1 && indexT >= 0) {
        return indexT + 1;
    } else if (indexS >= 0 && indexT == -1) {
        return indexS + 1;
    } else if (indexS >= 0 && indexS < a.lenS && indexT >= 0 && indexT < a.lenT) {
        return a.tab[indexT * a.lenS + indexS];
    } else {
        // Handle error: indices out of bounds
        return -1;
    }
}


//levenshtein: calcule la distance de levenshtein de deux chaînes
double levenshtein(char * S, char * T) {
    int lenS = strlen(S);
    int lenT = strlen(T);
    LevArray a = init(lenS, lenT);

    for(int j=0; j<lenT; j++){
        for(int i=0; i<lenS; i++){
            if(S[i] == T[j]){
                set(a, i, j, get(a, i-1, j-1));
            }
            if(S[i] != T[j]){
                int sub=get(a, i-1, j-1);
                int sup=get(a, i-1, j);
                int ins=get(a, i, j-1);
                set(a, i, j, min(min(sub, sup), ins)+1);
            }
        }
    }
    int d = get(a, lenS-1, lenT-1);
    int lenMax=max(lenS, lenT);
    double score = (1 - (double)d/lenMax) * 100;
    //printf("d= %i, lenMax= %i, score= %f\n", d, lenMax, score);

   return score;
}
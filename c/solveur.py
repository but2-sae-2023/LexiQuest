import csv
import sys

def distance(mot1,mot2):
    p={} #tableau des peres
    d={} #tableau des distances
    p[mot1]=mot1
    d[mot1]=0
    newP=False

    with open("tree.txt", 'r', encoding='utf8') as fichier_csv:
        lecteur = csv.reader(fichier_csv)
        data = [(point1, point2, float(distance)) for point1, point2, distance in lecteur]

    #parcours en voisinage de mot1
    while(mot2 not in p):
        for point1, point2, distance in data:
            ##si point1 est dans p (connecté au mot1), on ajoute point2 à p
            if (point1 in p):
                if(point2 not in p):
                    p[point2]=point1
                    d[point2]=distance
                    newP=True
            ##si point2 est dans p (connecté au mot1), on ajoute point1 à p
            elif(point2 in p):
                if(point1 not in p):
                    p[point1]=point2
                    d[point1]=distance
                    newP=True    
        ##si pas de nouveau point ajouté, on sort
        if(not newP):
            ##print("Pas de chemin trouvé")
            return -1
        newP=False
        
    score=float('inf')
    mot=mot2
    ##on parcours le chemin de mot2 à mot1 pour trouver la distance minimale
    while(mot!=mot1):
        if(float(d[mot])<score):
            score=float(d[mot])
        mot=p[mot]
        
    return score

##print(distance("amour","numéro"))
##print(sys.argv[1])
if(len(sys.argv)==3):
    print(distance(sys.argv[1],sys.argv[2]))
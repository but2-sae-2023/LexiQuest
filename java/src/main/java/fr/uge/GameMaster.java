package fr.uge;



import java.io.BufferedWriter;
import java.io.IOException;
import java.nio.file.Files;
import java.nio.file.Path;
import java.util.*;

/**
 * GameReader permet de lire les fichiers de jeu
 */
public record GameMaster() {

    /**
     * Lance le jeu en créant un arbre avec les mots initial et final passés en paramètre
     *
     * @param initialWord le mot de départ
     * @param finalWord   le mot d'arrivée
     */
    public void launchGame(String initialWord, String finalWord, String gameid) {
        MSTree mst = new MSTree(initialWord, finalWord);
        addBridges(mst, gameid);
        writeTree(mst,gameid);
        writeBestPath(mst,gameid);
    }

    /**
     * Lit le fichier passé en paramètre et retourne une liste de String contenant les lignes du fichier
     *
     * @param filename le nom du fichier à lire
     * @return une liste de String contenant les lignes du fichier
     */
    private List<String> read(String filename) {
        Path filePath = Path.of(filename);
        ArrayList<String> fileContent = new ArrayList<>();
        if (!Files.exists(filePath)) {
            return null;
        }
        try {
            List<String> lines = Files.readAllLines(filePath);
            fileContent.addAll(lines);

        } catch (IOException e) {
            e.printStackTrace();
        }
        return fileContent;
    }

    /**
     * Lit le fichier "msTree.txt" et retourne une TreeMap contenant les ponts et leurs scores.
     * Il s'agit des ponts de l'ancien arbre
     *
     * @return une TreeMap contenant les ponts et leurs scores
     */
    private TreeMap<Bridge, Integer> bridgesFromFile() {
        List<String> msTreeTxt = read("msTree.txt");
        if (msTreeTxt == null) {
            return null;
        }
        HashMap<Bridge, Integer> bridges = new HashMap<>();
        for (String line : msTreeTxt) {
            String[] words = line.split(",");
            bridges.put(new Bridge(words[0], words[1]), Integer.parseInt(words[2]));
        }
        // On trie les ponts par ordre croissant de leur score
        TreeMap<Bridge, Integer> sortedBridges = new TreeMap<>(Comparator.comparing(bridges::get).thenComparing(Bridge::compareBridges));
        sortedBridges.putAll(bridges);
        return sortedBridges;

    }

    /**
     * Ajoute les ponts des fichiers "gameFile.txt" et "msTree.txt" à l'arbre passé en paramètre
     *
     * @param mst l'arbre auquel on veut ajouter les ponts
     */
    private void addBridges(MSTree mst, String gameid) {
        // On récupère les ponts de l'ancien arbre
        TreeMap<Bridge, Integer> importedBridges = bridgesFromFile();
        //System.out.println(importedBridges);

        // On récupère les mots du fichier "gameFile.txt"
        List<String> gameFile = read("../C/Games/"+ gameid + "-game/gameFile.txt");

        // On récupère les indexes se trouvant sur la première ligne du fichier "gameFile.txt"
        String[] indexes = gameFile.get(0).split(",");
        System.out.println(Arrays.toString(indexes));

        // On enlève la première ligne du fichier "gameFile.txt" qui contient les indexes
        List<String> wordList = Arrays.stream(gameFile.get(1).split(",")).toList();
        System.out.println(wordList);

        gameFile.remove(0);

        System.out.println(gameFile);

        if (!mst.isStartWordsSet()) {
            mst.setStartWords(wordList.get(0).split(",")[0], wordList.get(1).split(",")[0]);
        }

        // On définit le point de départ de la boucle for avec la valeur de l'index 1 du fichier "gameFile.txt"
        int start = Integer.parseInt(indexes[0])+1;

        // On ajoute les ponts de l'ancien arbre à l'arbre passé en paramètre
        if (importedBridges != null) {
            for (Bridge bridge : importedBridges.keySet()) {
                mst.add(bridge, bridge.firstWord(), importedBridges.get(bridge));
            }
            // On définit le point de départ de la boucle for avec la valeur de l'index 2 du fichier "gameFile.txt" si l'ancien arbre existe
            start = Integer.parseInt(indexes[1]);
        }

        // On récupère les ponts déjà ajoutés à l'arbre passé en paramètre
        List<Bridge> bridges = mst.getAllBridges();
        //System.out.println(start);
        int j = 0;

        // On ajoute les ponts du fichier "gameFile.txt" à l'arbre passé en paramètre
        for (int i = start; i < gameFile.size(); i++) {
            // On sépare les mots et les scores de chaque ligne du fichier "gameFile.txt"
            String[] words = gameFile.get(i).split(",");
            // On crée un nouveau pont avec les deux mots de la ligne du fichier "gameFile.txt"
            Bridge bridge = new Bridge(words[0], words[1]);

            // Le compteur j permet de se placer sur le "bon" mot inséré. C'est en rapport avec la méthode add qui prend
            // en paramètre le nouveau mot inséré
            if (!bridge.isInBridge(wordList.get(j).split(",")[0])) {
                //System.out.println("bridge :" + bridge + " | word : " + wordList.get(j).split(",")[0] + " | score : " + Math.min(Integer.parseInt(words[2]), Integer.parseInt(words[3])));
                j++;
            }

            // On récupère le score du pont en prenant le score le plus élevé entre les deux scores de la ligne du fichier "gameFile.txt"
            int score = Math.max(Integer.parseInt(words[2]), Integer.parseInt(words[3]));

            // On ajoute le pont à l'arbre passé en paramètre si le pont n'est pas déjà présent dans l'arbre
            if (bridges == null || bridges.isEmpty() || !bridges.contains(bridge)) {
                //System.out.println("bridge :" + bridge + " | word : " + wordList.get(j).split(",")[0] + " | score : " + score);
                mst.add(bridge, wordList.get(j).split(",")[0],score);
            }
        }
    }

    /**
     * Ecrit le contenu de l'arbre passé en paramètre dans le fichier "msTree.txt"
     *
     * @param mst l'arbre dont on veut écrire le contenu
     */
    private void writeTree(MSTree mst, String gameid) {
        String treeFile = "../C/Games/"+ gameid + "-game/msTree.txt";
        String content = mst.writableTree();
        Path path = Path.of(treeFile);

        try {
            BufferedWriter writer = Files.newBufferedWriter(path);
            writer.write(content);
            writer.close();
            //System.out.println("Le contenu a été écrit dans le fichier avec succès.");
        } catch (IOException e) {
            e.printStackTrace();
        }

    }

    /**
     * Ecrit le meilleur chemin de l'arbre passé en paramètre dans le fichier "bestPath.txt"
     *
     * @param mst l'arbre dont on veut écrire le meilleur chemin
     */
    private void writeBestPath(MSTree mst, String gameid) {
        String bestPathFile = "../C/Games/"+ gameid + "-game/bestPath.txt";
        String content = mst.writableBestPath();
        Path path = Path.of(bestPathFile);

        try {
            BufferedWriter writer = Files.newBufferedWriter(path);
            writer.write(content);
            writer.close();
            //System.out.println("Le contenu a été écrit dans le fichier avec succès.");
        } catch (IOException e) {
            e.printStackTrace();
        }

    }
}

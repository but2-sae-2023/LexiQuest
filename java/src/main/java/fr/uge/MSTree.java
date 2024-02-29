package fr.uge;

import java.util.Map.Entry;
import java.util.Map;
import java.util.Set;
import java.util.TreeMap;
import java.util.ArrayList;
import java.util.Comparator;
import java.util.HashSet;
import java.util.LinkedHashSet;
import java.util.List;
import java.util.HashMap;
import java.util.Objects;
import java.util.Stack;

/*
 * MSTree représente un arbre utilisé pour trouver le meilleur chemin entre deux mots.
 * Il stocke des ponts (Bridge) avec des scores associés.
 */
public class MSTree {
    private final Map<Bridge, Integer> msMap;
    private TreeMap<Bridge, Integer> sortedMSMap;
    private String initialWord;
    private String finalWord;

    /**
     * Constructeur de MSTree
     *
     * @param initialWord le mot de départ
     * @param finalWord   le mot d'arrivée
     */
    public MSTree(String initialWord, String finalWord) {
        this.initialWord = initialWord;
        this.finalWord = finalWord;
        msMap = new HashMap<>();
        sortedMSMap = new TreeMap<>(Comparator.comparingInt(msMap::get).thenComparing(Bridge::compareBridges));
    }

    /**
     * Fixe les mots de départ et d'arrivée
     *
     * @param initialWord le mot de départ
     * @param finalWord le mot d'arrivée
     */
    public void setStartWords(String initialWord, String finalWord) {
        this.initialWord = initialWord;
        this.finalWord = finalWord;
    }

    /**
     * Retourne le meilleur chemin entre le mot de départ et le mot d'arrivée
     *
     * @return le meilleur chemin entre le mot de départ et le mot d'arrivée
     */
    public List<Bridge> getBestPath() {
        List<Bridge> bestPath = new ArrayList<>();
        searchPath(initialWord, finalWord, bestPath);
        return bestPath.isEmpty() ? null : bestPath;
    }

    /**
     * Retourne le cycle dans lequel se trouve le mot initial
     *
     * @param initialWord le mot initial
     * @return le cycle dans lequel se trouve le mot initial
     */
    public List<Bridge> getCycle(String initialWord) {
        Set<String> visitedWords = new HashSet<>();
        List<Bridge> bridgesInCycle = new ArrayList<>();
        visitedWords.add(initialWord);

        boolean cycleFound = findCycle(initialWord, initialWord, visitedWords, bridgesInCycle);
        if (cycleFound) {
            return bridgesInCycle;
        }
        return null;
    }

    /**
     * Méthode complémentaire à getBestPath qui permet de trouver le meilleur chemin entre deux mots
     *
     * @param startWord le mot de départ
     * @param endWord   le mot d'arrivée
     * @param path      le chemin à construire
     */

    private void searchPath(String startWord, String endWord, List<Bridge> path) {
        Stack<String> wordStack = new Stack<>();
        Set<String> visitedWords = new LinkedHashSet<>();
        Map<String, Bridge> previousBridgeMap = new HashMap<>();

        wordStack.push(startWord);
        visitedWords.add(startWord);

        while (!wordStack.isEmpty()) {
            String currentWord = wordStack.pop();

            if (currentWord.equals(endWord)) {
                buildPath(currentWord, path, previousBridgeMap);
                return;
            }

            List<Bridge> bridges = getBridgesLinkedTo(currentWord);

            for (Bridge bridge : bridges) {
                String nextWord = bridge.getOtherWord(currentWord);
                if (!visitedWords.contains(nextWord)) {
                    visitedWords.add(nextWord);
                    wordStack.push(nextWord);
                    previousBridgeMap.put(nextWord, bridge);
                }
            }
        }
    }

    /**
     * Méthode complémentaire à searchPath qui permet de construire le chemin entre deux mots
     *
     * @param endWord           le mot d'arrivée
     * @param path              le chemin à construire
     * @param previousBridgeMap la map des ponts précédents
     */

    private void buildPath(String endWord, List<Bridge> path, Map<String, Bridge> previousBridgeMap) {
        String word = endWord;
        while (previousBridgeMap.containsKey(word)) {
            Bridge bridge = previousBridgeMap.get(word);
            path.add(0, bridge);
            word = bridge.getOtherWord(word);
        }
    }

    /**
     * Méthode récursive utilisée dans getCycle qui permet de trouver un cycle dans lequel se trouve le mot initial
     *
     * @param initialWord   le mot initial
     * @param currentWord   le mot courant
     * @param visitedWords  les mots déjà visités
     * @param bridgesInCycle les ponts dans le cycle
     * @return true si un cycle a été trouvé, false sinon
     */

    private boolean findCycle(String initialWord, String currentWord, Set<String> visitedWords, List<Bridge> bridgesInCycle) {
        List<Bridge> bridgesLinkedTo = getBridgesLinkedTo(currentWord);
        for (Bridge bridge : bridgesLinkedTo) {
            String otherWord = bridge.getOtherWord(currentWord);
            if (otherWord.equals(initialWord) && bridgesInCycle.size() > 1) {
                bridgesInCycle.add(bridge);
                return true;
            }

            if (!visitedWords.contains(otherWord)) {
                visitedWords.add(otherWord);
                if (!bridgesInCycle.contains(bridge)) {
                    bridgesInCycle.add(bridge);
                }

                boolean cycleFound = findCycle(initialWord, otherWord, visitedWords, bridgesInCycle);
                if (cycleFound) {
                    return true;
                }
                bridgesInCycle.remove(bridge);
            }
        }
        return false;
    }

    /**
     * Retourne les ponts liés à un mot
     *
     * @param word le mot
     * @return les ponts liés à un mot
     */

    public List<Bridge> getBridgesLinkedTo(String word) {
        List<Bridge> bridgesLinkedTo = new ArrayList<>();
        for (Bridge bridge : msMap.keySet()) {
            if (bridge.isInBridge(word)) {
                bridgesLinkedTo.add(bridge);
            }
        }
        return bridgesLinkedTo;
    }


    /**
     * Retourne le score d'un pont
     *
     * @return le score d'un pont
     */
    public List<Bridge> getAllBridges() {
        return new ArrayList<>(msMap.keySet());
    }

    /**
     * Indique si les mots de départ et d'arrivée sont définis
     *
     * @return true si les mots de départ et d'arrivée sont définis, false sinon
     */
    public boolean isStartWordsSet() {
        return initialWord != null && finalWord != null;
    }


    /**
     * Ajoute un pont à l'arbre et vérifie si un cycle est créé par
     * l'ajout de ce pont (en utilisant la méthode getCycle)
     *
     * @param bridge le pont
     * @return le score d'un pont
     */
    public void add(Bridge bridge, String newWord, Integer score) {
        //System.out.println("sortedMSMap : " + sortedMSMap.toString());
        //System.out.println("msMap : " + msMap.toString());
        Objects.requireNonNull(score);
        if (!msMap.isEmpty()) {
            if (score >= sortedMSMap.firstEntry().getValue()) {
                //System.out.println("bridge to add : " + bridge);
                msMap.put(bridge, score);
                sortedMSMap.put(bridge, score);
                //System.out.println("bridge added : " + bridge);
                if (bridge.firstWord().equals(newWord) || bridge.secondWord().equals(newWord)) {
                    List<Bridge> cycle = getCycle(newWord);
                    while (cycle != null) {
                        Bridge bridgeWithMinScore = getMinimalScoreBridge(cycle);
                        remove(bridgeWithMinScore);
                        //System.out.println("bridgeWithMinScore removed : " + bridgeWithMinScore);
                        cycle = getCycle(newWord);
                    }
                }
            }
        } else {
            //System.out.println("------------------------------------------\nFirst bridge added : " + bridge + "\n--------------------------------------");
            System.out.println("bridge to add : " + bridge);
            System.out.println("initialWord : " + initialWord + " | finalWord : " + finalWord);
            if (initialWord == null && finalWord == null) {
                initialWord = bridge.firstWord();
                finalWord = bridge.secondWord();
            }
            msMap.put(bridge, score);
            sortedMSMap.put(bridge, score);
        }
    }

    /**
     * Retire un pont de l'arbre
     *
     * @param bridge le pont
     */
    public void remove(Bridge bridge) {
        sortedMSMap.remove(bridge);
        msMap.remove(bridge);
        //System.out.println("bridge removed : " + bridge);
        //System.out.println("msMap : " + msMap.toString());
        //System.out.println("sortedMSMap : " + sortedMSMap.toString());
    }

    /**
     * Retourne le pont avec le score le plus faible parmi une liste de ponts donnée en paramètre
     *
     * @param bridges la liste de ponts
     * @return le score minimal d'un pont
     */

    public Bridge getMinimalScoreBridge(List<Bridge> bridges) {
        //System.out.println("bridges: " + bridges.toString());
        return bridges.stream().min(Comparator.comparingInt(msMap::get)).orElse(null);
    }

    @Override
    public String toString() {
        StringBuilder sb = new StringBuilder();
        for (Entry<Bridge, Integer> entry : msMap.entrySet()) {
            sb.append(entry.getKey().toString()).append("score: ").append(entry.getValue()).append("\n");
        }
        return sb.toString();
    }

    /**
     * Retourne l'arbre sous forme de String pour pouvoir l'écrire dans un fichier
     *
     * @return l'arbre sous forme de String
     */
    public String writableTree() {
        StringBuilder sb = new StringBuilder();
        // System.out.println("initialWord1 : " + initialWord + " | finalWord : " + finalWord);
        for (Entry<Bridge, Integer> entry : sortedMSMap.entrySet()) {
            sb.append(entry.getKey().firstWord()).append(",").append(entry.getKey().secondWord()).append(",").append(entry.getValue()).append("\n");
        }
        return sb.toString();
    }

    /**
     * Retourne le meilleur chemin sous forme de String pour pouvoir l'écrire dans un fichier
     *
     * @return le meilleur chemin sous forme de String
     */
    public String writableBestPath() {
        StringBuilder sb = new StringBuilder();
        List<Bridge> bestPath = getBestPath();
        System.out.println(bestPath.toString() + "-----------------");
        for (Bridge bridge : bestPath) {
            sb.append(bridge.firstWord()).append(",").append(bridge.secondWord()).append(",").append(msMap.get(bridge)).append("\n");
        }
        return sb.toString();
    }
}

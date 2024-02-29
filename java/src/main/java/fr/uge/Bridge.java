package fr.uge;

import java.util.Objects;

/**
 * Bridge représente un pont entre deux mots.
 * Il est composé de deux mots.
 */
public record Bridge(String firstWord, String secondWord) implements Comparable<Bridge> {

    /**
     * Retourne l'autre mot du pont que celui passé en paramètre
     *
     * @param word le mot dont on veut l'autre mot du pont
     * @return l'autre mot du pont
     */
    public String getOtherWord(String word) {
        if (word.equals(firstWord)) {
            return secondWord;
        }
        return firstWord;
    }

    /**
     * Retourne true si le mot passé en paramètre est dans le pont
     *
     * @param word le mot dont on veut savoir s'il est dans le pont
     * @return true si le mot passé en paramètre est dans le pont
     */
    public boolean isInBridge(String word) {
        return secondWord.equals(word) || firstWord.equals(word);
    }

    @Override
    public boolean equals(Object o) {
        if (this == o) return true;
        if (o == null || getClass() != o.getClass()) return false;
        Bridge bridge = (Bridge) o;
        return Objects.equals(firstWord, bridge.firstWord) && Objects.equals(secondWord, bridge.secondWord);
    }

    @Override
    public int hashCode() {
        return Objects.hash(firstWord, secondWord);
    }

    @Override
    public int compareTo(Bridge o) {
        return Integer.compare(this.firstWord.compareTo(o.firstWord), this.secondWord.compareTo(o.secondWord));
    }

    /**
     * Compare deux ponts
     *
     * @param o1 le premier pont
     * @param o2 le deuxième pont
     * @return le résultat de la comparaison
     */
    public static int compareBridges(Object o1, Object o2) {
        return ((Bridge) o1).compareTo((Bridge) o2);
    }
}

package fr.uge;




import java.io.BufferedWriter;
import java.io.IOException;
import java.nio.file.Files;
import java.nio.file.Path;

public class Main {
    public static void main(String[] args) {
        GameMaster gameMaster = new GameMaster();
        if (args.length >= 2) {
            gameMaster.launchGame(args[0], args[1], args[2]);

            // System.out.println("Après les ajouts de Bridges");
            //System.out.println("MSTree : " + mst);
            //System.out.println("Le meilleur chemin : " + mst.getBestPath());
        } else {
            gameMaster.launchGame(null, null, null);
        }

    }
}

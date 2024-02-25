const uWS = require('uWebSockets.js');

const app = uWS.App();

app.ws('/*', {
  /* Options de configuration */
  compression: uWS.SHARED_COMPRESSOR,
  maxPayloadLength: 16 * 1024 * 1024,
  idleTimeout: 60,
  /* Gestionnaire de connexion */
  open: (ws) => {
    console.log('Un joueur est connecté');
  },
  /* Gestionnaire de messages */
  message: (ws, message, isBinary) => {
    console.log('Message reçu :', message);
    // Traitez le message ici et envoyez une réponse si nécessaire
    ws.send(message); // Par exemple, renvoyez le même message
  },
  /* Gestionnaire de fermeture de la connexion */
  close: (ws, code, message) => {
    console.log('Un joueur s\'est déconnecté');
  }
});

app.listen(3000, (token) => {
  if (token) {
    console.log('Serveur uWebSockets en cours d\'écoute sur le port 3000');
  } else {
    console.log('Échec du démarrage du serveur');
  }
});

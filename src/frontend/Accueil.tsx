import { useContext, useState } from 'react';
import '../style/App.css';
import '../style/header.css';
import Connexion from './Connexion';
import Inscription from './Inscription';
import Home from './Home';
import User from '../class/User';
import { UserContext } from '../data/UserProvider';

const Accueil = () => {
  const [displayConnexion, setdisplayConnexion] = useState(true);
  const { user, setUser } = useContext(UserContext);

  const handleToggle = () => {
    setdisplayConnexion(!displayConnexion);
  };
  
  const fictionalUser = new User(
    1, // user_id
    'toto', // username
    'cc',
    'toto@gmail.com', // email
    2000, // birth_year
    new Date(), // date_last_cnx
    new Date(), // date_signup
    0, // nb_game_played
    0, // avg_score
    0, // min_score
    0 // max_score
  );

  // Initialisez l'utilisateur et définissez l'état de connexion
  // Utilisez setUser et setConnected de l'instance de la classe User
  setUser(fictionalUser);
  fictionalUser.setConnected(true);

  return (
    <>
      {user.getConnected() ? (
            <Home />
        ) : (
            <main>
            {displayConnexion ? <Connexion /> : <Inscription />}
            <button id="connexionChange" onClick={handleToggle}>
                {displayConnexion ? "S'inscrire" : "Se connecter"}
            </button>
            </main>
      )}
    </>
  );
};

export default Accueil;
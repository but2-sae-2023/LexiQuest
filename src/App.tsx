import React, { createContext, ReactNode, useState } from 'react';
import './style/App.css';
import './style/header.css';
import Connexion from './frontend/Connexion';
import Inscription from './frontend/Inscription';
import Home from './frontend/Home';
import User from './class/User';

export const UserContext = createContext<User>(new User());

const App = () => {
  const [displayConnexion, setdisplayConnexion] = useState(true);
  const [user, setUser] = useState<User>(new User());
  const userContext = createContext<User>(user);

  const handleToggle = () => {
    setdisplayConnexion(!displayConnexion);
  };

  /*
  //Utilisateur fictif pour les tests en attendant
  const fictionnalUser = {user_id: 1,
    username: 'toto',
    email: 'toto@gmail.com',
    birth_year: 2000,
    date_last_cnx: new Date(),
    date_signup: new Date(),
    nb_game_played: 0,
    avg_score: 0,
    min_score: 0,
    max_score: 0};

  user.setUser(fictionnalUser);
  user.setConnected(true);*/

  return (
    <UserContext.Provider value={user}>
      {user.getConnected() ? (
        <Home />
      ) : (
        <main>
          {displayConnexion ? <Connexion setUser={setUser} /> : <Inscription setUser={setUser} />}
          <button id="connexionChange" onClick={handleToggle}>
            {displayConnexion ? "S'inscrire" : "Se connecter"}
          </button>
        </main>
      )}
    </UserContext.Provider>
  );
};

export default App;
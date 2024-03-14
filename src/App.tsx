import React, { createContext, ReactNode, useState } from 'react';
import { createBrowserRouter, RouterProvider } from "react-router-dom";
import './style/App.css';
import './style/header.css';
import Accueil from './frontend/Accueil';
import Compte from './frontend/Compte';
import User from './class/User';

export const UserContext = createContext<User>(new User());

const App = () => {
  const [displayConnexion, setdisplayConnexion] = useState(true);
<<<<<<< HEAD
  const [user, setUser] = useState<User>(new User());
  const userContext = createContext<User>(user);

  const handleToggle = () => {
    setdisplayConnexion(!displayConnexion);
  };
=======
 
  const router = createBrowserRouter([
    {
      path: "/",
      element: <Accueil />,
    },
    {
      path: "/compte",
      element: <Compte />,
    }
  ]);
>>>>>>> origin/dev_loic

  //Utilisateur fictif
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
  user.setConnected(true);

  return (
<<<<<<< HEAD
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
=======
    <>
      <RouterProvider router={router} />
    </>
>>>>>>> origin/dev_loic
  );
};

export default App;
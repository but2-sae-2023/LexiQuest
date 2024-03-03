import React, { createContext, ReactNode, useState } from 'react';
import './App.css';
import './style/header.css';
import Connexion from './frontend/Connexion';
import Inscription from './frontend/Inscription';
import Home from './frontend/Home';
import User from './class/User';

const App = () => {
  const [displayConnexion, setdisplayConnexion] = useState(true);
  const [user, setUser] = useState<User>(new User());
  const UserContext = createContext<User | undefined>(undefined);

  // Créer un composant contextuel pour fournir l'utilisateur aux composants enfants
  const UserProvider: React.FC<{ children: ReactNode }> = ({ children }) => {
    return (
      <UserContext.Provider value={user}>
        {children}
      </UserContext.Provider>
    );
  };

  const handleToggle = () => {
    setdisplayConnexion(!displayConnexion);
  };

  return (
    <UserProvider>
      {user.getConnected() ? (
        <Home />
      ) : (
        <main>
          {displayConnexion ? <Connexion setUser={setUser}/> : <Inscription setUser={setUser}/>}
          <button id="connexionChange" onClick={handleToggle}>
            {displayConnexion ? "S'inscrire" : "Se connecter"}
          </button>
        </main>
      )}
    </UserProvider>
  );
};

export default App;
import React from 'react';
import { useState , useEffect } from 'react';
import './App.css';
import './style/header.css';
import Connexion from './frontend/Connexion';
import Inscription from './frontend/Inscription';
import User from './class/User';

const App = () => {
  const [displayConnexion, setdisplayConnexion] = useState(true);

  const handleToggle = () => {
    setdisplayConnexion(!displayConnexion);
  };

  return (
    <div>
      {displayConnexion ? <Connexion /> : <Inscription />}
      <button onClick={handleToggle}>
        {displayConnexion ? "S'inscrire" : "Se connecter"}
      </button>
    </div>
  );
};

export default App;

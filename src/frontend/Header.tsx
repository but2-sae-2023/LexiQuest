import React, { useContext } from 'react';
import { UserContext } from "../App";
import logo from '../data/img/logo.png';

const Header = () => {
  const user = useContext(UserContext);

  // Assurez-vous que user.getImagePath() renvoie le chemin complet de l'image
  const imagePath = user.getImagePath();

  return (
    <header>
      <img src={logo} alt="logo de LexiQuest" />
      <h1>LexiQuest</h1>
      <a href="compte.html">
        {/* Utilisation de l'image provenant de user.getImagePath() */}
        <img id='img_compte' src={imagePath} alt="img_profile" />
        <h2 id='nom_compte'>{user.getUsername()}</h2>
      </a>
    </header>
  );
};

export default Header;
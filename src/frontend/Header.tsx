import React, { useContext } from 'react';
import { UserContext } from "./Accueil";
import '../style/header.css';
import logo from '../data/img/logo.png';

const Header = () => {
  const user = useContext(UserContext);

  const imagePath = user.getImagePath();

  return (
    <>
      <header>
        <img src={logo} alt="logo de LexiQuest" />
        <h1>LexiQuest</h1>

        <a href="Compte.tsx">
          <img id='img_compte' src={imagePath} alt="img_profile" />
          <h2 id='nom_compte'>{user.getUsername()}</h2>
        </a>
        
      </header>
    </>
  );
};

export default Header;
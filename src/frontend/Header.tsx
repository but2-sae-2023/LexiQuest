import React, { useContext } from 'react';
import '../style/header.css';
import logo from '../data/img/logo.png';
import { UserContext } from '../data/UserProvider';
import { Link } from 'react-router-dom';

const Header = () => {
  const { user, setUser } = useContext(UserContext);

  const imagePath = user.getImagePath();

  return (
    <>
      <header>
        <img src={logo} alt="logo de LexiQuest" />
        <h1>LexiQuest</h1>

        <Link to="/compte">
          <img id='img_compte' src={imagePath} alt="img_profile" />
          <h2 id='nom_compte'>{user.getUsername()}</h2>
        </Link>
        
      </header>
    </>
  );
};

export default Header;

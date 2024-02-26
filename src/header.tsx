import React from 'react';

const Header: React.FC = () => {
  return (
    <header>
      <img src="data/img/logo.png" alt="logo de LexiQuest" />
      <h1>LexiQuest</h1>
      <a href="compte.html">
        {/* fetch les données de l'img et du nom d'utilisateur */}
        <img
          id='img_compte'
          src="https://media.discordapp.net/attachments/1180876461889048579/1192913030065950740/furina.jpg?ex=65e22cd5&is=65cfb7d5&hm=3f0536ff85925ce1697dd2acc646bbfcfda95c9a803351234d4bf8a266087213&=&format=webp&width=1173&height=660"
          alt="img_profile"
        />
        <h2 id='nom_compte'>Votre nom</h2>
      </a>
    </header>
  );
};

export default Header;

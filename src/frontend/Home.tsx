import React, { useContext } from 'react';
import { UserContext } from "./Accueil";
import '../style/App.css';
import '../style/home.css';
import Header from './Header';
import StartButton from './StartButton';
import Deconnexion from './Deconnexion';

const Home = () => {
    const user = useContext(UserContext);
    console.log(user);

    return (
        <>
            <Header />
            <main>
                <h1>Bonjour { user.getUsername() }</h1>
                <StartButton />
                <Deconnexion />
            </main>
        </>
    );
};

export default Home;
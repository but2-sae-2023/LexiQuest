import React, { useContext } from 'react';
<<<<<<< HEAD
import { UserContext } from "../App";
import '../style/App.css';
import '../style/header.css';
import Header from './Header';

const Home = () => {
    const user = useContext(UserContext);
=======
import { UserContext } from "./Accueil";
import '../style/App.css';
import '../style/home.css';
import Header from './Header';
import StartButton from './StartButton';
import Deconnexion from './Deconnexion';

const Home = () => {
    const user = useContext(UserContext);
    console.log(user);
>>>>>>> origin/dev_loic

    return (
        <>
            <Header />
            <main>
<<<<<<< HEAD
                { user.getUsername() }
=======
                <h1>Bonjour { user.getUsername() }</h1>
                <StartButton />
                <Deconnexion />
>>>>>>> origin/dev_loic
            </main>
        </>
    );
};

export default Home;
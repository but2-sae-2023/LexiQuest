import React, { useContext } from 'react';
import { UserContext } from "./Accueil";
import '../style/App.css';


const StartButton = () => {
    const user = useContext(UserContext);

    return (
        <>
            <button>Commencer la partie</button>
        </>
    );
};

export default StartButton;
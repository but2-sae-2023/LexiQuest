import React, { useContext } from 'react';
import { UserContext } from "../App";
import '../style/App.css';


const StartButton = () => {
    const user = useContext(UserContext);

    return (
        <>
            <button>Se déconnecter</button>
        </>
    );
};

export default StartButton;
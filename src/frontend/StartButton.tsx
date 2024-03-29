import { useContext, useState } from 'react';
import '../style/App.css';
import ChooseMod from './ChooseMod';
import { UserContext } from '../data/UserProvider';

const StartButton = () => {
    const { user, setUser } = useContext(UserContext);
    const [startParty, setStartParty ] = useState(false);

    return (
        <>
            {!startParty ? <><h1>Bonjour { user.getUsername() }</h1><button onClick={ () => setStartParty(true) }>Lancer une partie</button></> : <ChooseMod />}
        </>
    );
};

export default StartButton;
import { useContext} from 'react';
import '../style/compte.css';
import { User } from '../class/User';
import Header from './Header';
import { UserContext } from '../data/UserProvider';

function Compte() {
    const { user } = useContext(UserContext);

    console.log("User information:", user);
    
    return (
        <>
            <Header />
            <section className='compte_contener'>
                <Infos user={ user } />
                <Stats user={ user } />
            </section>
        </>
    );
}
  
function Infos( { user }: { user: User } ) {

    return (
        <>
            <section id='infos'>
                <h1>Vos informations</h1>
                <ul>
                    <li>
                        <h2>Pseudo: { user.getUsername() }</h2>
                        <img src="../data/img/bouton-modifier.png" alt="modifier" />
                    </li>
                    <li>
                        <h2>Mot de passe: ************ </h2>
                        <img src="../data/img/bouton-modifier.png" alt="modifier" />
                    </li>
                    <li>
                        <h2>Adresse mail: { user.getEmail() }</h2>
                        <img src="../data/img/bouton-modifier.png" alt="modifier" />
                    </li>
                    <li>
                        <h2>Année de naissance: { user.getBirth_year() }</h2>
                        <img src="../data/img/bouton-modifier.png" alt="modifier" />
                    </li>
                    <li>
                        <h2>Date d'inscription: { user.getDate_signup().getUTCDay() + '/' + user.getDate_signup().getUTCMonth() + '/' + user.getDate_signup().getFullYear() }</h2>
                        <img src="../data/img/bouton-modifier.png" alt="modifier" />
                    </li>
                </ul>
            </section>
            
        </>
    );
}

function Stats( { user }: { user: User } ) {

    return (
        <>
            <section id='stats'>
                <h1>Vos stats</h1>
                <h2>Parties jouées: { user.getNb_game_played() } </h2>
                <figure>
                    <div id='chart'></div>
                </figure>
            </section>
        </>
    );
}

export default Compte;
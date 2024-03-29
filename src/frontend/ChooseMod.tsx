import '../style/chooseMod.css';
import deathMatch from '../data/img/logo_mods/death.png';
import tranquille from '../data/img/logo_mods/chill.png';
import MulThiv from '../data/img/logo_mods/manettes.png';
import Solo from '../data/img/logo_mods/manette.png';
import { useState } from 'react';
import OptionMenu from './OptionMenu';

const ChooseMod = () => {
    //useContext(UserContext);
    const [gotoOptions, setgotoOptions ] = useState(false);
    
    return (
        <>
        {!gotoOptions ?
        <>
            <section className='chooseMod_container'>
                <button onClick={ () => setgotoOptions(true) } style={ { backgroundColor:'white', background: `url(${MulThiv})`, backgroundSize: '100%', backgroundRepeat: 'no-repeat', backgroundPosition: 'center'} }>
                    <h1>Multijoueur</h1>
                </button>
                <button onClick={ () => setgotoOptions(true) } style={ { backgroundColor:'white',background: `url(${deathMatch})`, backgroundSize: '100%', backgroundRepeat: 'no-repeat', backgroundPosition: 'center'} }>
                    <h1>Match à mort</h1>
                </button>
                <button onClick={ () => setgotoOptions(true) } style={ { backgroundColor:'white',background: `url(${Solo})`, backgroundSize: '100%', backgroundRepeat: 'no-repeat', backgroundPosition: 'center'} }>
                    <h1>Solo</h1>
                </button>
                <button onClick={ () => setgotoOptions(true) } style={ { backgroundColor:'white',background: `url(${tranquille})`, backgroundSize: '100%', backgroundRepeat: 'no-repeat', backgroundPosition: 'center'} }>
                    <h1>Tranquille</h1>
                </button>
            </section>
        </>:
            <OptionMenu />
        }
        </>
    
    );
};

export default ChooseMod;
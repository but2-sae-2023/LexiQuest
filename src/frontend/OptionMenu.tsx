import { useState } from "react";
import OnePlayer from "./OnePlayer";

const OptionMenu = () => {
    const [startGame, setStartGame ] = useState(false);
    
    return (
        <>
        {!startGame ?
        <>
            <section className='option_container'>
                <button onClick={ () => setStartGame(true) }></button>
                <button onClick={ () => setStartGame(true) }></button>
            </section>
        </>:
            <OnePlayer />
        }
        </>
    
    );
};

export default OptionMenu;
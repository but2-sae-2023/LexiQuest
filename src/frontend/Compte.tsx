import React, { useEffect, useMemo, useState } from 'react';
import '../style/compte.css';
import { User } from '../class/User';

function Compte() {
    const [user,setUser] = useState<User>(new User());

    return (
        <>
            <main>
                <Infos user={ user } />
                <Stats user={ user } />
            </main>
        </>
    );
}
  
function Infos( { user }: { user: User } ) {

    return (
        <>
        </>
    );
}

function Stats( { user }: { user: User } ) {

    return (
        <>
        </>
    );
}

export default Compte;
import React, { useEffect, useMemo, useState } from 'react';
import { User } from '../class/User';

function Compte() {
    const [user,setUser] = useState<User>(new User());

    return (
        <>
            <main>
                <h1>Page de compte</h1>
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
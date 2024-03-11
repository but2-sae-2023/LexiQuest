import React, { useContext } from 'react';
import { UserContext } from "../App";
import '../style/App.css';
import '../style/header.css';
import Header from './Header';

const Home = () => {
    const user = useContext(UserContext);

    return (
        <>
            <Header />
            <main>
                { user.getUsername() }
            </main>
        </>
    );
};

export default Home;